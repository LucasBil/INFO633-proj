<?php
require_once __DIR__ . '/../utils/pdo.php';
require_once __DIR__ . '/../utils/service.php';

require_once __DIR__ . '/../models/work.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/project.php';
require_once __DIR__ . '/user_service.php';
require_once __DIR__ . '/project_service.php';

class WorkService extends Service
{
    private static function workModel($id_user, $id_project): Work {
        $user = UserService::getById($id_user);
        $project = ProjectService::getById($id_project);
        $work = new Work($id_user, $id_project);
        $work->setUser($user);
        $work->setProject($project);
        return $work;
    }

    public static function getAll(): array
    {
        $table = Work::getTableName();
        $db = DBAManager::getInstance();
        $query = "SELECT * FROM `$table`;";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $works = $stmt->fetchAll(PDO::FETCH_FUNC, function($id_user	, $id_project) {
            return self::workModel($id_user, $id_project);
        });
        return $works;
    }

    public static function getByUserId(int $id): array
    {
        $table = Work::getTableName();
        $db = DBAManager::getInstance();
        $query = "SELECT * FROM `$table` WHERE id_user = ?;";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $id
        ]);
        $works = $stmt->fetchAll(PDO::FETCH_FUNC, function($id_user, $id_project) {
            return self::workModel($id_user, $id_project);
        });
        return $works;
    }

    public static function getByProjectId(int $id): array
    {
        $table = Work::getTableName();
        $db = DBAManager::getInstance();
        $query = "SELECT * FROM `$table` WHERE id_project = ?;";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $id
        ]);
        $works = $stmt->fetchAll(PDO::FETCH_FUNC, function($id_user, $id_project) {
            return self::workModel($id_user, $id_project);
        });
        return $works;
    }

    public static function getByIds($user_id, $project_id): ?Work
    {
        $table = Work::getTableName();
        $db = DBAManager::getInstance();
        $query = "SELECT * FROM `$table` WHERE id_user = ? AND id_project = ?;";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $user_id,
            $project_id
        ]);
        $works = $stmt->fetchAll(PDO::FETCH_FUNC, function($id_user, $id_project) {
            return self::workModel($id_user, $id_project);
        });
        return $works[0] ?? null;
    }

    public static function create(Work $work): Work
    {
        $table = Work::getTableName();
        $db = DBAManager::getInstance();
        $query = "INSERT INTO `$table` (id_user, id_project) VALUES (?, ?);";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $work->getIdUser(),
            $work->getIdProject()
        ]);
        $work = self::getByIds($work->getIdUser(), $work->getIdProject());
        return $work;
    }

    public static function delete(Work $work): Work
    {
        $table = Work::getTableName();
        $db = DBAManager::getInstance();
        $query = "DELETE FROM `$table` WHERE id_user = ? AND id_project = ?;";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $work->getIdUser(),
            $work->getIdProject()
        ]);
        return $work;
    }
}