<?php
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/enum/role.php';
require_once __DIR__ . '/../utils/pdo.php';
require_once __DIR__ . '/../utils/service.php';
require_once __DIR__ . '/../utils/token.php';

class AuthService extends Service {
    public static function login(string $email, string $password): array {
        $table = User::getTableName();
        $db = DBAManager::getInstance();

        $stmt = $db->prepare("SELECT * FROM $table WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        $users = $stmt->fetchAll(PDO::FETCH_FUNC, function($id, $email, $password, $first_name, $last_name, $roles) {
            return new User($email, $password, $first_name, $last_name, json_decode($roles), $id);
        });
        if (empty($users) || $users[0]->getPassword() !==  hash('sha256', $password)) {
            return [];
        }

        $token = TokenManager::getInstance()->generateToken($email, [
            'id' => $users[0]->getId(),
            'email' => $users[0]->getEmail(),
            'roles' => $users[0]->getRoles(),
        ]);
        return ['token' => $token, 'expr' => time() + 3600];
    }
}