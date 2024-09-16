<?php

use App\Http\Controllers\UserController;
use Routes\Router;

header('Content-Type: application/json');

$router = new Router();

$router->addRoute('GET', '/users', function () {
    $controller = new UserController();
    return $controller->index();
});

$router->addRoute('POST', '/users', function ($params) {
    $controller = new UserController();
    return $controller->store($params['first_name'], $params['last_name']);
});

$response = $router->matchRoute();
echo $response;
