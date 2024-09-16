<?php

use App\Helpers\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Middlewares\JwtMiddleware;
use Dotenv\Dotenv;
use Routes\Router;

header('Content-Type: application/json');
$dotenv = Dotenv::createImmutable(__DIR__. '/../../');
$dotenv->load();

Auth::init();

$router = new Router();

$router->addRoute('GET', '/', function () {
    echo "Welcome to the api";
});

$router->addRoute('POST', '/login', function ($params) {
    $controller = new AuthController();
    return $controller->generateToken($params);
});

$router->addRoute('GET', '/users', function ($params) {
    $controller = new UserController();
    return $controller->index($params['first_name'] ?? null, $params['last_name'] ?? null);
}, [JwtMiddleware::class]);

$router->addRoute('GET', '/users/{uuid}', function ($uuid) {
    $controller = new UserController();
    return $controller->show($uuid);
}, [JwtMiddleware::class]);

$router->addRoute('POST', '/users', function ($params) {
    $controller = new UserController();
    return $controller->store($params['first_name'], $params['last_name']);
}, [JwtMiddleware::class]);

$router->addRoute('PUT', '/users/{uuid}', function ($uuid, $params) {
    $controller = new UserController();
    return $controller->update($uuid, $params['first_name'], $params['last_name']);
}, [JwtMiddleware::class]);

$router->addRoute('DELETE', '/users/{uuid}', function ($uuid) {
    $controller = new UserController();
    return $controller->delete($uuid);
}, [JwtMiddleware::class]);

$response = $router->matchRoute();
echo $response;
