<?php
// Set headers to output JSON and allow Cross-Origin requests (important for API monitors)
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

// Respond with a solid 500 Internal Server Error
http_response_code(500);

echo json_encode([
    "status" => "error",
    "code" => 500,
    "error" => "Internal Server Error",
    "message" => "Critical API Error: Database connection failed. SQLSTATE[HY000] [2002] Connection refused.",
    "exception" => "PDOException: SQLSTATE[HY000] [2002] Connection refused in /var/www/liteview/src/Database.php:24",
    "details" => "Failed to establish a connection to the primary database service. Host unreachable.",
    "timestamp" => date('c')
], JSON_PRETTY_PRINT);
exit;
