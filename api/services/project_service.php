<?php

require_once __DIR__ . '/../utils/service.php';
require_once __DIR__ . '/../models/project.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/user_service.php';

class ProjectService extends Service
{
    private static function projectModel($id, $name, $description, $status, $year, $duration, $id_creator): Project {
        $user = UserService::getById($id_creator);
        $project = new Project($name, $description, $status, $year, $duration, $id_creator, $id);
        $project->setCreator($user);
        return $project;
    }

    public static function getAll(): array
    {
        $table = Project::getTableName();
        $db = DBAManager::getInstance();
        $query = "SELECT * FROM $table;";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $projects = $stmt->fetchAll(PDO::FETCH_FUNC, function($id, $name, $description, $status, $year, $duration, $id_creator) {
            return self::projectModel($id, $name, $description, $status, $year, $duration, $id_creator);
        });
        return $projects;
    }

    public static function getById(int $id): ?Project
    {
        $table = Project::getTableName();
        $db = DBAManager::getInstance();
        $query = "SELECT * FROM $table WHERE id = :id;";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $projects = $stmt->fetchAll(PDO::FETCH_FUNC, function($id, $name, $description, $status, $year, $duration, $id_creator) {
            return self::projectModel($id, $name, $description, $status, $year, $duration, $id_creator);
        });
        return $projects[0] ?? null;
    }

    public static function create(Project $project): Project
    {
        $table = Project::getTableName();
        $db = DBAManager::getInstance();
        $query = "INSERT INTO $table (name, description, status, year, duration, id_creator) VALUES (?, ?, ?, ?, ?, ?);";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $project->getName(),
            $project->getDescription(),
            $project->getStatus(),
            $project->getYear(),
            $project->getDuration(),
            $project->getIdCreator()
        ]);
        $project->setId($db->lastInsertId());
        return $project;
    }

    public static function update(Project $project): Project
    {
        $table = Project::getTableName();
        $db = DBAManager::getInstance();
        $query = "UPDATE $table SET name = ?, description = ?, status = ?, year = ?, duration = ? WHERE id = ?;";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $project->getName(),
            $project->getDescription(),
            $project->getStatus(),
            $project->getYear(),
            $project->getDuration(),
            $project->getId()
        ]);
        return $project;
    }

    public static function delete(Project $project): Project
    {
        $table = Project::getTableName();
        $db = DBAManager::getInstance();
        $query = "DELETE FROM $table WHERE id = ?;";
        $stmt = $db->prepare($query);
        $stmt->execute([$project->getId()]);
        return $project;
    }
}