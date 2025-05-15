<?php
require_once __DIR__ . '/../services/composes_service.php';
require_once __DIR__ . '/../utils/controller.php';
require_once __DIR__ . '/../models/enum/role.php';
require_once __DIR__ . '/../models/enum/condition.php';

class ComposesController extends Controller {
    public static function getAll() {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $composes = ComposesService::getAll();
        return self::sendResponse($composes);
    }

    public static function getByProjectId($project_id) {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $composes = ComposesService::getByProjectId($project_id);
        return self::sendResponse($composes);
    }

    public static function getByAssetId($asset_id) {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $composes = ComposesService::getByAssetId($asset_id);
        return self::sendResponse($composes);
    }

    public static function getByIds($project_id, $asset_id) {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $compose = ComposesService::getByIds($project_id, $asset_id);
        if (!$compose) {
            return self::sendError('Compose not found', 404);
        }
        return self::sendResponse($compose);
    }

    public static function create() {
        if (!self::userAuthenticated() || !self::roleGranted([ROLE::ADMIN->value, ROLE::TEACHER->value])) {
            return self::sendError('Unauthorized', 401);
        }

        $id_project = self::getRequestDataByKey('id_project');
        $id_asset = self::getRequestDataByKey('id_asset');
        $condition = self::getRequestDataByKey('condition');
        $condition = isset($condition) ? Condition::from($condition) : null;
        $comment = self::getRequestDataByKey('comment');

        $compose = new Composes($id_project, $id_asset, $condition, $comment);
        $compose = ComposesService::create($compose);
        return self::sendResponse($compose);
    }

    public static function update($project_id, $asset_id) {
        if (!self::userAuthenticated() || !self::roleGranted([ROLE::ADMIN->value, ROLE::TEACHER->value])) {
            return self::sendError('Unauthorized', 401);
        }
        $compose = ComposesService::getByIds($project_id, $asset_id);
        if (!$compose) {
            return self::sendError('Asset not found', 404);
        }
        $compose->update(self::getRequestData());
        $compose = ComposesService::update($compose);
        return self::sendResponse($compose);
    }

    public static function delete($project_id, $asset_id) {
        if (!self::userAuthenticated() || !self::roleGranted([ROLE::ADMIN->value, ROLE::TEACHER->value])) {
            return self::sendError('Unauthorized', 401);
        }
        $compose = ComposesService::getByIds($project_id, $asset_id);
        if (!$compose) {
            return self::sendError('Asset not found', 404);
        }
        $compose = ComposesService::delete($compose);
        return self::sendResponse($compose);
    }
}