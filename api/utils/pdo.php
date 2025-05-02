<?php

class DBAManager {
    # Singleton instance
    private static ?DBAManager $instance = null;
    private ?PDO $pdo;

    private function __construct($host, $user, $password, $database) {
        $this->pdo = new PDO(
            "$host;dbname=$database;charset=utf8mb4",
            $user,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }

    public static function getInstance(): DBAManager {
        if (self::$instance === null) {
            self::$instance = new DBAManager(
                $_ENV['DB_HOST'] ?? 'mysql:host=mariadb',
                $_ENV['DB_USER'] ?? 'root',
                $_ENV['DB_PASSWORD'] ?? 'root',
                $_ENV['DB_DATABASE'] ?? 'info633'
            );
        }
        return self::$instance;
    }

    public function query(string $query) {
        $statement = self::getInstance()->pdo->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }
    public function exec(string $query) {
        return self::getInstance()->pdo->exec($query);
    }
    public function prepare(string $query) {
        return self::getInstance()->pdo->prepare($query);
    }
    public function lastInsertId() {
        return self::getInstance()->pdo->lastInsertId();
    }
    public function beginTransaction() {
        return self::getInstance()->pdo->beginTransaction();
    }
    public function commit() {
        return self::getInstance()->pdo->commit();
    }
    public function rollBack() {
        return self::getInstance()->pdo->rollBack();
    }
}