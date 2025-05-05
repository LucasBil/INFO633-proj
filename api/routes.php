<?php
# Load all controllers (automatically)
$controllers = glob(__DIR__ . '/controllers/*.php');
foreach ($controllers as $controller) {
    require_once $controller;
}

$routes = [
    'GET' => [
        '#^/users$#' => function () { UserController::getAll(); },
        '#^/user/(\d+)$#' => function ($id) { UserController::getById((int)$id); },
        '#^/projects$#' => function () { ProjectController::getAll(); },
        '#^/project/(\d+)$#' => function ($id) { ProjectController::getById((int)$id); },
        '#^/works$#' => function () { WorkController::getAll(); },
        '#^/work/user/(\d+)$#' => function ($id) { WorkController::getByUserId((int)$id); },
        '#^/work/project/(\d+)$#' => function ($id) { WorkController::getByProjectId((int)$id); },
        '#^/work/user/(\d+)/project/(\d+)$#' => function ($user_id, $project_id) { WorkController::getByIds((int)$user_id, (int)$project_id); },
        '#^/deliverables$#' => function () { DeliverableController::getAll(); },
        '#^/deliverable/(\d+)$#' => function ($id) { DeliverableController::getById((int)$id); },
    ],
    'POST' => [
        '#^/user$#' => function() { UserController::create(); },
        '#^/login$#' => function() { AuthController::login(); },
        '#^/project$#' => function() { ProjectController::create(); },
        '#^/work$#' => function() { WorkController::create(); },
        '#^/deliverable$#' => function() { DeliverableController::create(); },
    ],
    'PUT' => [
        '#^/user/(\d+)$#' => function ($id) { UserController::update((int)$id); },
        '#^/project/(\d+)$#' => function ($id) { ProjectController::update((int)$id); },
        '#^/deliverable/(\d+)$#' => function ($id) { DeliverableController::update((int)$id); },
    ],
    'DELETE' => [
        '#^/user/(\d+)$#' => function ($id) { UserController::delete((int)$id); },
        '#^/project/(\d+)$#' => function ($id) { ProjectController::delete((int)$id); },
        '#^/work/user/(\d+)/project/(\d+)$#' => function ($user_id, $project_id) { WorkController::delete((int)$user_id, (int)$project_id); },
        '#^/deliverable/(\d+)$#' => function ($id) { DeliverableController::delete((int)$id); },
    ]
];