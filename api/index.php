<?php
require 'routes.php';

# CORS allow *
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if (isset($routes[$method][$uri])) {
    call_user_func($routes[$method][$uri]);
    exit;
}

foreach ($routes[$method] as $pattern => $handler) {
    if (preg_match($pattern, $uri, $matches)) {
        array_shift($matches);
        call_user_func_array($handler, $matches);
        exit;
    }
}

http_response_code(404);
header('Content-Type: application/json');
echo json_encode(["message" => "404 - Not Found"]);