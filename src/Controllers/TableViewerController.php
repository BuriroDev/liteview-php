<?php

class TableViewerController {

    public function showTable($tableName) {
        
        $allowedTables = ['students', 'departments', 'colleges'];
        if (!in_array($tableName, $allowedTables)) {
            die("Table not allowed!");
        }

        require_once __DIR__ . '/../Database.php';
        
        $stmtTables = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name;");
        $tablesList = $stmtTables->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $db->query("SELECT * FROM $tableName");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../view/generic_table.php';
    }
}