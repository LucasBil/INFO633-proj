<?php
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../services/auth_service.php';
require_once __DIR__ . '/../utils/controller.php';

class AuthController extends Controller {
    public static function login() {
        try {
            [
                'email' => $email,
                'password' => $password,
            ] = self::getRequestData();
            $token = ""; // AuthService::login($email, $password);
            // if ($token === null) {
            //     return self::sendError('Invalid email or password', 401);
            // }
            return self::sendResponse(['token' => $token]);
        } catch (PDOException $e) {
            return self::sendError('Failed to login', 500);
        } catch (Exception $e) {
            return self::sendError('Invalid request data', 400);
        }
    }
}