<?php
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../services/user_service.php';
require_once __DIR__ . '/../utils/controller.php';

class UserController extends Controller {
    public static function getAll() {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $users = UserService::getAll();
        return self::sendResponse($users);
    }

    public static function getById(int $id) {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $user = UserService::getById($id);
        if ($user) {
            return self::sendResponse($user);
        }
        return self::sendError('User not found', 404);
    }

    public static function create() {
        try {
            [
                'email' => $email,
                'password' => $password,
                'first_name' => $first_name,
                'last_name' => $last_name,
            ] = self::getRequestData();
            $user = new User($email, null, $first_name, $last_name, [Role::STUDENT]);
            $user->setPassword($password);
            $user = UserService::create($user);
            return self::sendResponse($user, 201);
        } catch (PDOException $e) {
            return self::sendError('Faild to create user', 500);
        } catch (Exception $e) {
            return self::sendError('Invalid request data', 400);
        }
    }

    public static function update(int $id) {
        $tokenUserId = TokenManager::getInstance()->getTokenData(self::getToken() ?? '')['id'];
        if ($tokenUserId != $id) {
            return self::sendError('Unauthorized', 401);
        }
        try {
            $user = UserService::getById($id);
            if (!$user) {
                return self::sendError('User not found', 404);
            }
            foreach (self::getRequestData() as $key => $value) {
                if (property_exists($user, $key)) {
                    $setter = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
                    if (!method_exists($user, $setter)) {
                        return self::sendError('Invalid request data', 400);
                    }
                    $user->$setter($value);
                }
            }
            $user = UserService::update($user);
            return self::sendResponse($user);
        } catch (PDOException $e) {
            return self::sendError('Failed to update user', 500);
        } catch (Exception $e) {
            return self::sendError('Invalid request data', 400);
        }
    }

    public static function delete(int $id) {
        $tokenUserId = TokenManager::getInstance()->getTokenData(self::getToken() ?? '')['id'];
        if ($tokenUserId != $id) {
            return self::sendError('Unauthorized', 401);
        }
        try {
            $user = UserService::getById($id);
            if (!$user) {
                return self::sendError('User not found', 404);
            }
            UserService::delete($user);
            return self::sendResponse(null, 204);
        } catch (PDOException $e) {
            return self::sendError('Failed to delete user', 500);
        } catch (Exception $e) {
            return self::sendError('Invalid request data', 400);
        }
    }
}