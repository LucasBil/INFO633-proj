<?php
require_once __DIR__ . '/../utils/token.php';

abstract class Controller {
    public static function getRequestData(): array {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($contentType, 'application/json') === 0) {
            return Controller::getJsonRequestData();
        } elseif (stripos($contentType, 'application/x-www-form-urlencoded') === 0) {
            return $_POST;
        } elseif (stripos($contentType, 'multipart/form-data') === 0) {
            return array_merge($_POST, $_FILES); // Handle file uploads separately if needed
        } else {
            return $_REQUEST; // Fallback to default request data
        }
        $json = file_get_contents('php://input');
        return json_decode($json, true);
    }

    public static function getToken(): ?string {
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            list($type, $token) = explode(' ', $headers['Authorization'], 2);
            if (strcasecmp($type, 'Bearer') === 0) {
                return $token;
            }
        }
        return null;
    }

    public static function userAuthenticated(): bool {
        $token = self::getToken();
        if ($token) {
            $tokenManager = TokenManager::getInstance();
            if ($tokenManager->validateToken($token)) {
                return true;
            }
        }
        return false;
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

    public static function roleGranted(array $roles): bool {
        $token = self::getToken();
        if ($token) {
            $tokenManager = TokenManager::getInstance();
            if ($tokenManager->validateToken($token)) {
                $userData = $tokenManager->getTokenData($token);
                foreach ($roles as $role) {
                    if (in_array($role, $userData['roles'])) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}