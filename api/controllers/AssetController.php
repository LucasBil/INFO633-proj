<?php
require_once __DIR__ . '/../services/asset_service.php';
require_once __DIR__ . '/../utils/controller.php';
require_once __DIR__ . '/../models/enum/role.php';
require_once __DIR__ . '/../models/enum/assetState.php';

class AssetController extends Controller {
    public static function getAll() {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $assets = AssetService::getAll();
        return self::sendResponse($assets);
    }

    public static function getById($id) {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $asset = AssetService::getById($id);
        if (!$asset) {
            return self::sendError('Asset not found', 404);
        }
        return self::sendResponse($asset);
    }

    public static function create() {
        if (!self::userAuthenticated() || !self::roleGranted([ROLE::ADMIN->value, ROLE::TECHNICIAN->value])) {
            return self::sendError('Unauthorized', 401);
        }
        $data = self::getRequestData();
        [
            'name' => $name,
            'state' => $state,
        ] = $data;
        $numSerie = isset($data['numSerie']) ? $data['numSerie'] : null;
        $state = AssetState::from($state);
        $asset = new Asset($name, $state, $numSerie);
        $asset = AssetService::create($asset);
        return self::sendResponse($asset);
    }

    public static function update($id) {
        if (!self::userAuthenticated() || !self::roleGranted([ROLE::ADMIN->value, ROLE::TECHNICIAN->value])) {
            return self::sendError('Unauthorized', 401);
        }
        $asset = AssetService::getById($id);
        if (!$asset) {
            return self::sendError('Asset not found', 404);
        }
        $asset->update(self::getRequestData());
        $asset = AssetService::update($asset);
        return self::sendResponse($asset);
    }

    public static function delete($id) {
        if (!self::userAuthenticated() || !self::roleGranted([ROLE::ADMIN->value, ROLE::TECHNICIAN->value])) {
            return self::sendError('Unauthorized', 401);
        }
        $asset = AssetService::getById($id);
        if (!$asset) {
            return self::sendError('Asset not found', 404);
        }
        $asset = AssetService::delete($asset);
        return self::sendResponse($asset);
    }
}