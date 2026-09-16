<?php
class AiController {
    public function ask() {
        require_once __DIR__ . '/../Database.php';

        $tablesList = [];
        $queryResults = [];
        $queryError = null;
        $query = null;
        $userQuestion = isset($_POST['question']) ? trim($_POST['question']) : "";
        $apiKey = "";

        $envPath = getenv('HOME') . '/secrets/.env';
        if (file_exists($envPath)) {
            $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                $parts = explode('=', $line, 2);
                if (count($parts) === 2 && trim($parts[0]) === 'GEMINI_API_KEY') {
                    $apiKey = trim(trim($parts[1]), "\"'");
                    break;
                }
            }
        }

        if ($db) {
            $stmtTables = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name;");
            $tablesList = $stmtTables->fetchAll(PDO::FETCH_ASSOC);
        }

        if ($db && $userQuestion && $apiKey) {
            // Get Schema
            $stmtSchema = $db->query("SELECT name, sql FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%';");
            $schemaRows = $stmtSchema->fetchAll(PDO::FETCH_ASSOC);
            $schemaString = "";
            foreach ($schemaRows as $row) {
                $schemaString .= $row['sql'] . "\n\n";
            }

            // Call Gemini
            $prompt = "Given the following SQLite schema:\n$schemaString\n\nWrite a single valid SQLite SQL query to answer the user's question: \"$userQuestion\"\nOnly return the raw SQL string without formatting, markdown, or explanation. It should start with SELECT.";
            
            $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash-lite:generateContent?key=' . $apiKey;
            $data = [
                "contents" => [
                    ["parts" => [["text" => $prompt]]]
                ],
                "systemInstruction" => [
                    "parts" => [["text" => "You are an expert SQL assistant. Return only the raw SQLite SQL query without markdown blocks like ```sql."]]
                ]
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); // Force IPv4
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            
            $response = curl_exec($ch);
            $curlError = curl_error($ch);
            curl_close($ch);
            
            if ($response !== false && empty($curlError)) {
                $json = json_decode($response, true);
                if (isset($json['candidates'][0]['content']['parts'][0]['text'])) {
                    $generatedSql = trim($json['candidates'][0]['content']['parts'][0]['text']);
                    // Remove markdown if Gemini still adds it
                    $generatedSql = preg_replace('/^```sql\s*|\s*```$/i', '', $generatedSql);
                    $query = $generatedSql;
                    
                    try {
                        $stmt = $db->prepare($query);
                        $stmt->execute();
                        if (preg_match('/^\s*(SELECT|PRAGMA|EXPLAIN)/i', $query)) {
                            $queryResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        } else {
                            $queryResults = [['Status' => 'Query executed successfully. Rows affected: ' . $stmt->rowCount()]];
                        }
                    } catch (Exception $e) {
                        $queryError = "AI Generated SQL Error: " . $e->getMessage() . "\nGenerated SQL: $query";
                    }
                } else if (isset($json['error'])) {
                    $queryError = "Gemini API Error: " . ($json['error']['message'] ?? 'Unknown error');
                } else {
                    $queryError = "Failed to parse AI response. Check API key and quota.";
                }
            } else {
                $queryError = "Failed to connect to AI service. " . $curlError;
            }
        } else if (!$apiKey && $userQuestion) {
            $queryError = "API Key is required to use AI Query. Please set GEMINI_API_KEY in .env file.";
        }

        $isAiQueryActive = true;
        require __DIR__ . '/../view/generic_table.php';
    }
}
