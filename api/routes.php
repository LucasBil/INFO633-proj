<?php
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ProjectController.php';

$routes = [
    'GET' => [
        '#^/users$#' => function () { UserController::getAll(); },
        '#^/user/(\d+)$#' => function ($id) { UserController::getById((int)$id); },
        '#^/projects$#' => function () { ProjectController::getAll(); },
        '#^/project/(\d+)$#' => function ($id) { ProjectController::getById((int)$id); },
    ],
    'POST' => [
        '#^/user$#' => function() { UserController::create(); },
        '#^/login$#' => function() { AuthController::login(); },
        '#^/project$#' => function() { ProjectController::create(); },
    ],
    'PUT' => [
        '#^/user/(\d+)$#' => function ($id) { UserController::update((int)$id); }
    ],
    'DELETE' => [
        '#^/user/(\d+)$#' => function ($id) { UserController::delete((int)$id); }
    ]
];