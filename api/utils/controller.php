<?php

abstract class Controller {
    public static function getRequestData(): array {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($contentType, 'application/json') === 0) {
            return Request::getJsonRequestData();
        } elseif (stripos($contentType, 'application/x-www-form-urlencoded') === 0) {
            return $_POST;
        } elseif (stripos($contentType, 'multipart/form-data') === 0) {
            return $_POST; // Handle file uploads separately if needed
        } else {
            return $_REQUEST; // Fallback to default request data
        }
        $json = file_get_contents('php://input');
        return json_decode($json, true);
    }

    private static function getJsonRequestData(): array {
        $json = file_get_contents('php://input');
        return json_decode($json, true) ?? [];
    }

    public static function sendResponse($data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public static function sendError($message, int $statusCode = 400): void {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode(['error' => $message]);
    }
}