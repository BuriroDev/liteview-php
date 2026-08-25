<?php
$url = $_SERVER['PATH_INFO'] ?? '/';

$urlPieces = explode('/', trim($url, '/'));

if (isset($urlPieces[0]) && $urlPieces[0] === 'view' && isset($urlPieces[1])) {
    
    $tableName = $urlPieces[1]; 
    
    require __DIR__ . '/../src/Controllers/TableViewerController.php';
    $controller = new TableViewerController();
    
    $controller->showTable($tableName);

} elseif ($url === '/') {
    require __DIR__ . '/../src/Controllers/HomeController.php';
    $controller = new HomeController();
    $controller->index();
} else {
    http_response_code(404);
    echo "404 - Page not found";
}