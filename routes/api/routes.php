<?php

use App\Helpers\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Middlewares\JwtMiddleware;
use Dotenv\Dotenv;
use Routes\Router;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$dotenv = Dotenv::createImmutable(__DIR__. '/../../');
$dotenv->load();

Auth::init();

$router = new Router();

$router->addRoute('GET', '/', function () {
    $filePath = __DIR__ . '/../../resources/views/readme.html';

    if (!file_exists($filePath)) {
        http_response_code(404);
        return json_encode(['error' => 'File not found']);
    }

    header('Content-Type: text/html; charset=utf-8');
    return file_get_contents($filePath);
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
