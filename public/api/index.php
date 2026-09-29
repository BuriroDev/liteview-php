<?php
// Set headers to output JSON and allow Cross-Origin requests (important for API monitors)
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

$jsonFile = __DIR__ . '/employees.json';

if (file_exists($jsonFile)) {
    // Read and output the JSON data with 200 OK
    http_response_code(200);
    echo file_get_contents($jsonFile);
} else {
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "code" => 404,
        "message" => "Employees data file not found."
    ], JSON_PRETTY_PRINT);
}
