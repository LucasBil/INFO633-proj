<?php
require_once __DIR__ . '/../services/deliverable_service.php';
require_once __DIR__ . '/../utils/controller.php';
require_once __DIR__ . '/../models/enum/role.php';

class DeliverableController extends Controller {
    public static function getAll() {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $deliverables = DeliverableService::getAll();
        return self::sendResponse($deliverables);
    }

    public static function getById($id) {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $deliverable = DeliverableService::getById($id);
        if (!$deliverable) {
            return self::sendError('Deliverable not found', 404);
        }
        return self::sendResponse($deliverable);
    }

    public static function getByProjectId($id_project) {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $deliverables = DeliverableService::getByProjectId($id_project);
        return self::sendResponse($deliverables);
    }

    public static function create() {
        if (!self::userAuthenticated() || !self::roleGranted([ROLE::ADMIN->value, ROLE::TEACHER->value])) {
            return self::sendError('Unauthorized', 401);
        }
        [
            'name' => $name,
            'description' => $description,
            'date_creation' => $date_creation,
            'date_closure' => $date_closure,
            'id_project' => $id_project,
        ] = self::getRequestData();
        $deliverable = new Deliverable($name, $description, new DateTime($date_creation), new DateTime($date_closure), $id_project);
        $deliverable = DeliverableService::create($deliverable);
        return self::sendResponse($deliverable, 201);
    }

    public static function update($id) {
        if (!self::userAuthenticated() || !self::roleGranted([ROLE::ADMIN->value, ROLE::TEACHER->value])) {
            return self::sendError('Unauthorized', 401);
        }
        $deliverable = DeliverableService::getById($id);
        if (!$deliverable) {
            return self::sendError('Deliverable not found', 404);
        }
        [
            'name' => $name,
            'description' => $description,
            'date_creation' => $date_creation,
            'date_closure' => $date_closure,
            'id_project' => $id_project,
        ] = self::getRequestData();
        $deliverable->setName($name)
            ->setDescription($description)
            ->setDateCreation(new DateTime($date_creation))
            ->setDateClosure(new DateTime($date_closure))
            ->setIdProject($id_project);
        $deliverable = DeliverableService::update($deliverable);
        return self::sendResponse($deliverable);
    }

    public static function delete($id) {
        if (!self::userAuthenticated() || !self::roleGranted([ROLE::ADMIN->value, ROLE::TEACHER->value])) {
            return self::sendError('Unauthorized', 401);
        }
        $deliverable = DeliverableService::getById($id);
        if (!$deliverable) {
            return self::sendError('Deliverable not found', 404);
        }
        $deliverable = DeliverableService::delete($id);
        return self::sendResponse($deliverable, 200);
    }
}