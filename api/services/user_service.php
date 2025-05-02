<?php
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/enum/role.php';
require_once __DIR__ . '/../utils/pdo.php';
require_once __DIR__ . '/../utils/service.php';

class UserService extends Service {
    public static function create(User $user): User {
        $table = User::getTableName();
        $db = DBAManager::getInstance();
        $stmt = $db->prepare("INSERT INTO $table (email, password, first_name, last_name, roles) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $user->getEmail(),
            $user->getPassword(),
            $user->getFirstName(),
            $user->getLastName(),
            json_encode($user->getRoles())
        ]);
        $user->setId($db->lastInsertId());
        return $user;
    }

    public static function update(User $user) : User {
        $table = User::getTableName();
        $db = DBAManager::getInstance();
        $stmt = $db->prepare("UPDATE $table SET email = ?, password = ?, first_name = ?, last_name = ?, roles = ? WHERE id = ?");
        $stmt->execute([
            $user->getEmail(),
            $user->getPassword(),
            $user->getFirstName(),
            $user->getLastName(),
            json_encode($user->getRoles()),
            $user->getId()
        ]);
        return $user;
    }

    public static function delete(User $user) : User {
        $table = User::getTableName();
        $db = DBAManager::getInstance();
        $stmt = $db->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->execute([$user->getId()]);
        return $user;
    }

    public static function getAll() : array {
        $table = User::getTableName();
        $db = DBAManager::getInstance();
        $stmt = $db->prepare("SELECT * FROM $table");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_FUNC, function($id, $email, $password, $first_name, $last_name, $roles) {
            return new User($email, $password, $first_name, $last_name, json_decode($roles), $id);
        });
        return $users;
    }

    public static function getById(int $id) : ?User {
        $table = User::getTableName();
        $db = DBAManager::getInstance();

        $stmt = $db->prepare("SELECT * FROM $table WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_FUNC, function($id, $email, $password, $first_name, $last_name, $roles) {
            return new User($email, $password, $first_name, $last_name, json_decode($roles), $id);
        });
        return $users[0] ?? null;
    }
}