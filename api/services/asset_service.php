<?php
require_once __DIR__ . '/../utils/pdo.php';
require_once __DIR__ . '/../utils/service.php';

require_once __DIR__ . '/../models/asset.php';
require_once __DIR__ . '/../models/enum/assetState.php';

class AssetService extends Service {
    private static function assetModel($id, $name, $state, $numSerie)
    {
        $state = AssetState::from($state);
        return new Asset($name, $state, $numSerie, $id);
    }

    public static function getAll() : array {
        $table = Asset::getTableName();
        $db = DBAManager::getInstance();
        $query = "SELECT * FROM `$table`;";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $assets = $stmt->fetchAll(PDO::FETCH_FUNC, function($id, $name, $state, $numSerie) {
            return self::assetModel($id, $name, $state, $numSerie);
        });
        return $assets;
    }

    public static function getById(int $id) : ?Asset {
        $table = Asset::getTableName();
        $db = DBAManager::getInstance();
        $query = "SELECT * FROM `$table` WHERE id = ?;";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $id
        ]);
        $assets = $stmt->fetchAll(PDO::FETCH_FUNC, function($id, $name, $state, $numSerie) {
            return self::assetModel($id, $name, $state, $numSerie);
        });
        return $assets[0] ?? null;
    }

    public static function create(Asset $asset) : Asset {
        $table = Asset::getTableName();
        $db = DBAManager::getInstance();
        $query = "INSERT INTO `$table`(`name`, `state`, `numSerie`) VALUES (?,?,?);";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $asset->getName(),
            $asset->getState(),
            $asset->getNumSerie()
        ]);
        $asset->setId($db->lastInsertId());
        return $asset;
    }

    public static function update(Asset $asset) : Asset {
        $table = Asset::getTableName();
        $db = DBAManager::getInstance();
        $query = "UPDATE `$table` SET name = ?, state = ?, numSerie = ? WHERE id = ?;";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $asset->getName(),
            $asset->getState(),
            $asset->getNumSerie(),
            $asset->getId()
        ]);
        return $asset;
    }

    public static function delete(Asset $asset) : Asset {
        $table = Asset::getTableName();
        $db = DBAManager::getInstance();
        $query = "DELETE FROM `$table` WHERE id = ?;";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $asset->getId()
        ]);
        return $asset;
    }
}