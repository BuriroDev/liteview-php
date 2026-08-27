<?php

class TableViewerController {

    public function showTable($tableName) {
        
        // $allowedTables = ['students', 'departments', 'colleges'];
        // if (!in_array($tableName, $allowedTables)) {
        //     die("Table not allowed!");
        // }

        require_once __DIR__ . '/../Database.php';
        
        $tablesList = [];
        $results = [];

        if ($db) {
            $stmtTables = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name;");
            $tablesList = $stmtTables->fetchAll(PDO::FETCH_ASSOC);

            // Fetch table data securely
            $stmt = $db->query("SELECT * FROM " . preg_replace('/[^a-zA-Z0-9_]/', '', $tableName));
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        require __DIR__ . '/../view/generic_table.php';
    }
}