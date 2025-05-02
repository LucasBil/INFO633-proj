<?php
require 'routes.php';

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
echo json_encode(["message" => "404 - Not Found"]);