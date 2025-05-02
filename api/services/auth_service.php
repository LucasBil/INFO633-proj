<?php
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/enum/role.php';
require_once __DIR__ . '/../utils/pdo.php';
require_once __DIR__ . '/../utils/service.php';

class AuthService extends Service {
    public static function login(string $email, string $password): ?User {
        $table = User::getTableName();
        $db = DBAManager::getInstance();

        $stmt = $db->prepare("SELECT * FROM $table WHERE email = :email AND password = :password");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', password_hash($password, PASSWORD_BCRYPT), PDO::PARAM_STR);
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_FUNC, function($id, $email, $password, $first_name, $last_name, $roles) {
            return new User($email, $password, $first_name, $last_name, json_decode($roles), $id);
        });
        if (empty($users)) {
            return null;
        }

        $token = bin2hex(random_bytes(16));
        return $token;
    }
}