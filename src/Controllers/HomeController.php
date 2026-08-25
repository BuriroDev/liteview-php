<?php

class HomeController {
    
    public function index() {
        require_once __DIR__ . '/../Database.php';

        $query = "SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name;";
        
        $stmt = $db->query($query);
        $tablesList = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../view/home.php';
    }
    
}