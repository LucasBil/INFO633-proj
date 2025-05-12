<?php

require_once __DIR__ . '/../models/project.php';
require_once __DIR__ . '/../services/project_service.php';
require_once __DIR__ . '/../utils/controller.php';
require_once __DIR__ . '/../models/enum/role.php';
require_once __DIR__ . '/../models/enum/projectStatus.php';

class ProjectController extends Controller
{
    public static function getAll()
    {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $projects = ProjectService::getAll();
        return self::sendResponse($projects);
    }

    public static function getById(int $id)
    {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $project = ProjectService::getById($id);
        if ($project) {
            return self::sendResponse($project);
        } else {
            return self::sendError('Project not found', 404);
        }
    }

    public static function getByUserId(int $user_id)
    {
        if (!self::userAuthenticated()) {
            return self::sendError('Unauthorized', 401);
        }
        $projects = ProjectService::getByUserId($user_id);
        return self::sendResponse($projects);
    }

    public static function create()
    {
        if (!self::userAuthenticated() || !self::roleGranted([ROLE::ADMIN->value, ROLE::TEACHER->value])) {
            return self::sendError('Unauthorized', 401);
        }
        [
            'name' => $name,
            'status' => $status,
            'year' => $year,
            'duration' => $duration
        ] = self::getRequestData();
        $description = self::getRequestDataByKey('description');
        $status = ProjectStatus::from($status) ?? null;

        $userId = TokenManager::getInstance()->getTokenData(self::getToken() ?? '')['id'];
        $project = new Project($name, $description, $status, $year, $duration, $userId);
        $project = ProjectService::create($project);
        return self::sendResponse($project, 201);
    }

    public static function update(int $id)
    {
        if (!self::userAuthenticated() || !self::roleGranted([ROLE::ADMIN->value, ROLE::TEACHER->value]))
            return self::sendError('Unauthorized', 401);
        
        $project = ProjectService::getById($id);
        if ($project == null)
            return self::sendError('Project not found', 404);
        $project->update(self::getRequestData());
        $project = ProjectService::update($project);
        return self::sendResponse($project, 200);
    }

    public static function delete(int $id)
    {
        if (!self::userAuthenticated() || !self::roleGranted([ROLE::ADMIN->value, ROLE::TEACHER->value])) {
            return self::sendError('Unauthorized', 401);
        }
        $project = ProjectService::getById($id);
        if ($project) {
            ProjectService::delete($project);
            return self::sendResponse(['message' => 'Project deleted successfully'], 200);
        } else {
            return self::sendError('Project not found', 404);
        }
    }
}