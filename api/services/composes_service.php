<?php
require_once __DIR__ . '/../utils/pdo.php';
require_once __DIR__ . '/../utils/service.php';

require_once __DIR__ . '/../models/composes.php';
require_once __DIR__ . '/../models/asset.php';
require_once __DIR__ . '/../models/project.php';
require_once __DIR__ . '/../models/enum/condition.php';
require_once __DIR__ . '/asset_service.php';
require_once __DIR__ . '/project_service.php';

class ComposesService extends Service {

    private static function composesModel($id_project, $id_asset, $condition, $comment) : Composes {
        $project = ProjectService::getById($id_project);
        $asset = AssetService::getById($id_asset);
        $condition = Condition::from($condition);
        $compose = new Composes($id_project, $id_asset, $condition, $comment);
        $compose->setProject($project);
        $compose->setAsset($asset);
        return $compose;
    }

    public static function getAll() : array {
        $table = Composes::getTableName();
        $db = DBAManager::getInstance();
        $query = "SELECT * FROM `$table`;";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $composes = $stmt->fetchAll(PDO::FETCH_FUNC, function($id_project, $id_asset, $condition, $comment) {
            return self::composesModel($id_project, $id_asset, $condition, $comment);
        });
        return $composes;
    }

    public static function getByProjectId(int $project_id) : array {
        $table = Composes::getTableName();
        $db = DBAManager::getInstance();
        $query = "SELECT * FROM `$table` WHERE id_project = ?;";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $project_id
        ]);
        $composes = $stmt->fetchAll(PDO::FETCH_FUNC, function($id_project, $id_asset, $condition, $comment) {
            return self::composesModel($id_project, $id_asset, $condition, $comment);
        });
        return $composes;
    }

    public static function getByAssetId(int $asset_id) : array {
        $table = Composes::getTableName();
        $db = DBAManager::getInstance();
        $query = "SELECT * FROM `$table` WHERE id_asset = ?;";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $asset_id
        ]);
        $composes = $stmt->fetchAll(PDO::FETCH_FUNC, function($id_project, $id_asset, $condition, $comment) {
            return self::composesModel($id_project, $id_asset, $condition, $comment);
        });
        return $composes;
    }

    public static function getByIds(int $project_id, int $asset_id) : ?Composes {
        $table = Composes::getTableName();
        $db = DBAManager::getInstance();
        $query = "SELECT * FROM `$table` WHERE id_asset = ? AND id_project = ?;";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $asset_id,
            $project_id
        ]);
        $composes = $stmt->fetchAll(PDO::FETCH_FUNC, function($id_project, $id_asset, $condition, $comment) {
            return self::composesModel($id_project, $id_asset, $condition, $comment);
        });
        return $composes[0] ?? null;
    }

    public static function create(Composes $compose) : Composes {
        $table = Composes::getTableName();
        $db = DBAManager::getInstance();
        $query = "INSERT INTO `$table`(`id_project`, `id_asset`, `condition`, `comment`) VALUES (?,?,?,?);";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $compose->getIdProject(),
            $compose->getIdAsset(),
            $compose->getCondition(),
            $compose->getComment()
        ]);
        $compose->setId($db->lastInsertId());
        return $compose;
    }

    public static function update(Composes $compose) : Composes {
        $table = Composes::getTableName();
        $db = DBAManager::getInstance();
        $query = "UPDATE `$table` SET condition = ?, comment = ? WHERE id_project = ? AND id_asset = ?;";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $compose->getCondition(),
            $compose->getComment(),
            $compose->getIdProject(),
            $compose->getIdAsset(),
        ]);
        return $compose;
    }

    public static function delete(Composes $compose) : Composes {
        $table = Asset::getTableName();
        $db = DBAManager::getInstance();
        $query = "DELETE FROM `$table` WHERE id_project = ? AND id_asset = ?;";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $compose->getIdProject(),
            $compose->getIdAsset(),
        ]);
        return $compose;
    }
}
