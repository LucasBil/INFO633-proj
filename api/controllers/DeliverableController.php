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
        $name = self::getRequestDataByKey('name');
        $description = self::getRequestDataByKey('description');
        $date_creation = self::getRequestDataByKey('date_creation');
        $date_closure = self::getRequestDataByKey('date_closure');
        $id_project = self::getRequestDataByKey('id_project');

        $deliverable = new Deliverable(
            $name,
            $description,
            new DateTime($date_creation),
            $date_closure ? new DateTime($date_closure) : null,
            $id_project
        );
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
        $deliverable->update(self::getRequestData());
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