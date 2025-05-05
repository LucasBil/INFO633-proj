<?php

require_once __DIR__ . '/../services/work_service.php';
require_once __DIR__ . '/../utils/controller.php';

class WorkController extends Controller
{
    public static function getAll() {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $works = WorkService::getAll();
        return self::sendResponse($works);
    }

    public static function getByUserId($id) {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $works = WorkService::getByUserId($id);
        return self::sendResponse($works);
    }

    public static function getByProjectId($id) {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $works = WorkService::getByProjectId($id);
        return self::sendResponse($works);
    }

    public static function getByIds($user_id, $project_id) {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $work = WorkService::getByIds($user_id, $project_id);
        return self::sendResponse($work);
    }

    public static function create() {
        if (!self::userAuthenticated() || !self::roleGranted([ROLE::ADMIN->value, ROLE::TEACHER->value])) {
            return self::sendError('Unauthorized', 401);
        }
        [
            'user_id' => $user_id,
            'project_id' => $project_id
        ] = self::getRequestData();
        $work = new Work($user_id, $project_id);
        $work = WorkService::create($work);
        return self::sendResponse($work, 201);
    }

    public static function delete($user_id, $project_id) {
        if (!self::userAuthenticated() || !self::roleGranted([ROLE::ADMIN->value, ROLE::TEACHER->value])) {
            return self::sendError('Unauthorized', 401);
        }
        $work = WorkService::getByIds($user_id, $project_id);
        if (!$work) {
            return self::sendError('Work not found', 404);
        }
        $work = WorkService::delete($work);
        return self::sendResponse($work, 200);
    }
}