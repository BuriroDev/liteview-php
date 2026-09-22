<?php
// Set headers to output JSON and allow Cross-Origin requests (important for API monitors)
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

$jsonFile = __DIR__ . '/employees.json';

if (file_exists($jsonFile)) {
    // Read the JSON file
    $data = file_get_contents($jsonFile);
    
    // Optional: You can decode/manipulate the data here if needed
    // $decodedData = json_decode($data, true);
    
    // Output the JSON data
    http_response_code(200);
    echo $data;
} else {
    // Return a 404 error if the file doesn't exist
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "message" => "Employees data file not found."
    ]);
}
