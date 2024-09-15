<?php

use App\Http\Controllers\UserController;
use Routes\Router;

header('Content-Type: application/json');

$router = new Router();

$router->addRoute('GET', '/users', function () {
    $controller = new UserController();
    return $controller->index();
});

$response = $router->matchRoute();
echo $response;
