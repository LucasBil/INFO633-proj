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
        '#^/user$#' => function () { UserController::getInfo(); },
        '#^/projects$#' => function () { ProjectController::getAll(); },
        '#^/project/(\d+)$#' => function ($id) { ProjectController::getById((int)$id); },
        '#^/works$#' => function () { WorkController::getAll(); },
        '#^/work/user/(\d+)$#' => function ($id) { WorkController::getByUserId((int)$id); },
        '#^/work/project/(\d+)$#' => function ($id) { WorkController::getByProjectId((int)$id); },
        '#^/work/user/(\d+)/project/(\d+)$#' => function ($user_id, $project_id) { WorkController::getByIds((int)$user_id, (int)$project_id); },
        '#^/deliverables$#' => function () { DeliverableController::getAll(); },
        '#^/deliverable/(\d+)$#' => function ($id) { DeliverableController::getById((int)$id); },
        '#^/deliverable/project/(\d+)$#' => function ($project_id) { DeliverableController::getByProjectId((int)$project_id); },
        '#^/assets#' => function () { AssetController::getAll(); },
        '#^/asset/(\d+)$#' => function ($id) { AssetController::getById((int)$id); },
        '#^/composes#' => function () { ComposesController::getAll(); },
        '#^/compose/project/(\d+)$#' => function ($project_id) { ComposesController::getByProjectId((int)$project_id); },
        '#^/compose/asset/(\d+)$#' => function ($asset_id) { ComposesController::getByAssetId((int)$asset_id); },
        '#^/compose/project/(\d+)/asset/(\d+)$#' => function ($project_id, $asset_id) { ComposesController::getByIds((int)$project_id, (int)$asset_id); },
        '#^/documents#' => function () { DocumentController::getAll(); },
        '#^/document/(\d+)$#' => function ($id) { DocumentController::getById((int)$id); },
        '#^/document/deliverable/(\d+)$#' => function ($deliverable_id) { DocumentController::getByDeliverableId((int)$deliverable_id); },
        '#^/document/asset/(\d+)$#' => function ($asset_id) { DocumentController::getByAssetId((int)$asset_id); },
        '#^/document/download/(\d+)$#' => function ($id) { DocumentController::download((int)$id); },
        '#^/document/download/deliverable/(\d+)$#' => function ($deliverable_id) { DocumentController::downloadByDeliverableId((int)$deliverable_id); },
        '#^/document/download/asset/(\d+)$#' => function ($asset_id) { DocumentController::downloadByAssetId((int)$asset_id); },
    ],
    'POST' => [
        '#^/user$#' => function() { UserController::create(); },
        '#^/login$#' => function() { AuthController::login(); },
        '#^/project$#' => function() { ProjectController::create(); },
        '#^/work$#' => function() { WorkController::create(); },
        '#^/deliverable$#' => function() { DeliverableController::create(); },
        '#^/asset#' => function() { AssetController::create(); },
        '#^/compose#' => function() { ComposesController::create(); },
        '#^/document#' => function() { DocumentController::create(); },
    ],
    'PUT' => [
        '#^/user/(\d+)$#' => function ($id) { UserController::update((int)$id); },
        '#^/project/(\d+)$#' => function ($id) { ProjectController::update((int)$id); },
        '#^/deliverable/(\d+)$#' => function ($id) { DeliverableController::update((int)$id); },
        '#^/asset/(\d+)$#' => function ($id) { AssetController::update((int)$id); },
        '#^/compose/project/(\d+)/asset/(\d+)$#' => function ($project_id, $asset_id) { ComposesController::update((int)$project_id, (int)$asset_id); },
    ],
    'DELETE' => [
        '#^/user/(\d+)$#' => function ($id) { UserController::delete((int)$id); },
        '#^/project/(\d+)$#' => function ($id) { ProjectController::delete((int)$id); },
        '#^/work/user/(\d+)/project/(\d+)$#' => function ($user_id, $project_id) { WorkController::delete((int)$user_id, (int)$project_id); },
        '#^/deliverable/(\d+)$#' => function ($id) { DeliverableController::delete((int)$id); },
        '#^/asset/(\d+)$#' => function ($id) { AssetController::delete((int)$id); },
        '#^/compose/project/(\d+)/asset/(\d+)$#' => function ($project_id, $asset_id) { ComposesController::delete((int)$project_id, (int)$asset_id); },
        '#^/document/(\d+)$#' => function ($id) { DocumentController::delete((int)$id); },
    ]
];