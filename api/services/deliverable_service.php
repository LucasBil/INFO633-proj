<?php
require_once __DIR__ . '/../utils/pdo.php';
require_once __DIR__ . '/../utils/service.php';

require_once __DIR__ . '/../models/deliverable.php';
require_once __DIR__ . '/../models/project.php';

class DeliverableService extends Service {
    private static function deliverableModel($id, $name, $description, $date_creation, $date_closure, $id_project): Deliverable {
        $project = ProjectService::getById($id_project);
        $date_closure = $date_closure ? new DateTime($date_closure) : null;
        $deliverable = new Deliverable($name, $description, new DateTime($date_creation), $date_closure, $id_project, $id);
        $deliverable->setProject($project);
        return $deliverable;
    }

    public static function getAll() : array {
        $table = Deliverable::getTableName();
        $db = DBAManager::getInstance();
        $query = "SELECT * FROM `$table`;";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $deliverables = $stmt->fetchAll(PDO::FETCH_FUNC, function($id, $name, $description, $date_creation, $date_closure, $id_project) {
            return self::deliverableModel($id, $name, $description, $date_creation, $date_closure, $id_project);
        });
        return $deliverables;
    }

    public static function getById(int $id) : ?Deliverable {
        $table = Deliverable::getTableName();
        $db = DBAManager::getInstance();
        $query = "SELECT * FROM `$table` WHERE id = ?;";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $id
        ]);
        $deliverables = $stmt->fetchAll(PDO::FETCH_FUNC, function($id, $name, $description, $date_creation, $date_closure, $id_project) {
            return self::deliverableModel($id, $name, $description, $date_creation, $date_closure, $id_project);
        });
        return $deliverables[0] ?? null;
    }

    public static function getByProjectId(int $id_project) {
        $table = Deliverable::getTableName();
        $db = DBAManager::getInstance();
        $query = "SELECT * FROM `$table` WHERE id_project = ?;";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $id_project
        ]);
        $deliverables = $stmt->fetchAll(PDO::FETCH_FUNC, function($id, $name, $description, $date_creation, $date_closure, $id_project) {
            return self::deliverableModel($id, $name, $description, $date_creation, $date_closure, $id_project);
        });
        return $deliverables;
    }

    public static function create(Deliverable $deliverable) : Deliverable {
        $table = Deliverable::getTableName();
        $db = DBAManager::getInstance();
        $query = "INSERT INTO `$table` (name, description, date_creation, date_closure, id_project) VALUES (?, ?, ?, ?, ?);";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $deliverable->getName(),
            $deliverable->getDescription(),
            $deliverable->getDateCreation()->format('Y-m-d H:i:s'),
            $deliverable->getDateClosure()?->format('Y-m-d H:i:s'),
            $deliverable->getIdProject()
        ]);
        $deliverable->setId($db->lastInsertId());
        return $deliverable;
    }

    public static function update(Deliverable $deliverable) : Deliverable {
        $table = Deliverable::getTableName();
        $db = DBAManager::getInstance();
        $query = "UPDATE `$table` SET name = ?, description = ?, date_creation = ?, date_closure = ?, id_project = ? WHERE id = ?;";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $deliverable->getName(),
            $deliverable->getDescription(),
            $deliverable->getDateCreation()->format('Y-m-d H:i:s'),
            $deliverable->getDateClosure()->format('Y-m-d H:i:s'),
            $deliverable->getIdProject(),
            $deliverable->getId()
        ]);
        return $deliverable;
    }

    public static function delete(Deliverable $deliverable) : Deliverable {
        $table = Deliverable::getTableName();
        $db = DBAManager::getInstance();
        $query = "DELETE FROM `$table` WHERE id = ?;";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $deliverable->getId()
        ]);
        return $deliverable;
    }
}