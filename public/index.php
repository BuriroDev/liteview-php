<?php
session_start();

$url = $_SERVER['PATH_INFO'] ?? '/';

$urlPieces = explode('/', trim($url, '/'));

if ($url === '/upload') {
    require __DIR__ . '/../src/Controllers/UploadController.php';
    $controller = new UploadController();
    $controller->upload();
} elseif ($url === '/remove-db') {
    require __DIR__ . '/../src/Controllers/UploadController.php';
    $controller = new UploadController();
    $controller->remove();
} elseif (isset($urlPieces[0]) && $urlPieces[0] === 'view' && isset($urlPieces[1])) {
    
    $tableName = $urlPieces[1]; 
    
    require __DIR__ . '/../src/Controllers/TableViewerController.php';
    $controller = new TableViewerController();
    
    $controller->showTable($tableName);

} elseif ($url === '/sql') {
    require __DIR__ . '/../src/Controllers/SqlController.php';
    $controller = new SqlController();
    $controller->executeQuery();
} elseif ($url === '/ai') {
    require __DIR__ . '/../src/Controllers/AiController.php';
    $controller = new AiController();
    $controller->ask();
} elseif ($url === '/') {
    require __DIR__ . '/../src/Controllers/HomeController.php';
    $controller = new HomeController();
    $controller->index();
} else {
    http_response_code(404);
    echo "404 - Page not found";
}