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
                "mysql:host=" . getenv('DB_HOST'),
                getenv('DB_USER'),
                getenv('DB_PASSWORD'),
                getenv('DB_DATABASE')
            );
            self::generateDatabase();
        }
        return self::$instance;
    }

    private static function generateDatabase() {
        # Get all class in models folder
        $models = glob(__DIR__ . '/../models/*.php');
        
        # Load all classes and build dependency graph
        $classes = [];
        $dependencyGraph = [];
        
        foreach ($models as $model) {
            require_once $model;
            $className = basename($model, '.php');
            
            if (method_exists($className, 'getDependencies') && 
                method_exists($className, 'createTable')) {
                $classes[] = $className;
                $dependencyGraph[$className] = $className::getDependencies();
            }
        }
        
        # Topological sort to handle dependencies
        $sortedClasses = [];
        $visited = [];
        
        $visit = function ($className) use (&$visit, &$sortedClasses, &$visited, $dependencyGraph) {
            if (isset($visited[$className])) {
                return;
            }
            $visited[$className] = true;
            
            foreach ($dependencyGraph[$className] as $dependency) {
                if (class_exists($dependency) && 
                    method_exists($dependency, 'createTable')) {
                    $visit($dependency);
                }
            }
            
            $sortedClasses[] = $className;
        };
        
        foreach ($classes as $className) {
            $visit($className);
        }
        
        # Create tables in correct order
        foreach ($sortedClasses as $className) {
            $className::createTable();
        }
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