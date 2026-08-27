<?php
class SqlController {
    public function __construct() {
    }

    public function executeQuery() {
        require_once __DIR__ . '/../Database.php';

        $tablesList = [];
        $results = []; 
        $queryResults = []; 
        $queryError = null;

        if ($db) {
            $stmtTables = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name;");
            $tablesList = $stmtTables->fetchAll(PDO::FETCH_ASSOC);
        }

        $query = isset($_POST['query']) ? trim($_POST['query']) : "SELECT * FROM sqlite_master";
        
        if ($db && $query) {
            try {
                $stmt = $db->prepare($query);
                $stmt->execute();
                
                if (preg_match('/^\s*(SELECT|PRAGMA|EXPLAIN)/i', $query)) {
                    $queryResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    $queryResults = [['Status' => 'Query executed successfully. Rows affected: ' . $stmt->rowCount()]];
                }
            } catch (Exception $e) {
                $queryError = $e->getMessage();
            }
        }

        $isSqlQueryActive = true;

        require __DIR__ . '/../view/generic_table.php';
    }
}