<?php
class AiController {
    public function ask() {
        require_once __DIR__ . '/../Database.php';

        $tablesList = [];
        $queryResults = [];
        $queryError = null;
        $query = null;
        $userQuestion = isset($_POST['question']) ? trim($_POST['question']) : "";
        $postedApiKey = isset($_POST['api_key']) ? trim($_POST['api_key']) : "";
        $apiKey = $postedApiKey;

        if (empty($apiKey)) {
            $envPath = __DIR__ . '/../../.env';
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
            
            $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey;
            $data = [
                "contents" => [
                    ["parts" => [["text" => $prompt]]]
                ],
                "systemInstruction" => [
                    "parts" => [["text" => "You are an expert SQL assistant. Return only the raw SQLite SQL query without markdown blocks like ```sql."]]
                ]
            ];

            $options = [
                'http' => [
                    'header'  => "Content-type: application/json\r\n",
                    'method'  => 'POST',
                    'content' => json_encode($data),
                    'ignore_errors' => true
                ]
            ];
            $context  = stream_context_create($options);
            $response = file_get_contents($url, false, $context);
            
            if ($response !== false) {
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
                } else {
                    $queryError = "Failed to parse AI response. " . ($json['error']['message'] ?? 'Unknown error');
                }
            } else {
                $queryError = "Failed to connect to AI service.";
            }
        } else if (!$apiKey && $userQuestion) {
            $queryError = "API Key is required to use AI Query.";
        }

        $isAiQueryActive = true;
        require __DIR__ . '/../view/generic_table.php';
    }
}
