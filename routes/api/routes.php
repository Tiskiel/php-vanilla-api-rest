<?php

use App\Http\Controllers\UserController;
use Routes\Router;

header('Content-Type: application/json');

$router = new Router();

$router->addRoute('GET', '/users', function ($params) {
    $controller = new UserController();
    return $controller->index($params['first_name'] ?? null, $params['last_name'] ?? null);
});

$router->addRoute('GET', '/users/{uuid}', function ($uuid) {
    $controller = new UserController();
    return $controller->show($uuid);
});

$router->addRoute('POST', '/users', function ($params) {
    $controller = new UserController();
    return $controller->store($params['first_name'], $params['last_name']);
});

$router->addRoute('PUT', '/users/{uuid}', function ($uuid, $params) {
    $controller = new UserController();
    return $controller->update($uuid, $params['first_name'], $params['last_name']);
});

$router->addRoute('DELETE', '/users/{uuid}', function ($uuid) {
    $controller = new UserController();
    return $controller->delete($uuid);
});

$response = $router->matchRoute();
echo $response;
