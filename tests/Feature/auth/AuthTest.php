<?php

use App\Http\Controllers\AuthController;
use App\Middlewares\JwtMiddleware;
use Routes\Router;

it('should generate a token', function () {
    $router = new Router();

    $router->addRoute('POST', '/login', function ($params) {
        $controller = new AuthController();
        return $controller->generateToken($params);
    });

    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_SERVER['REQUEST_URI'] = '/login';
    $_POST = ['uuid' => '123', 'first_name' => 'John', 'last_name' => 'Doe'];

    $response = $router->matchRoute();

    expect($response)->toBeString();
});

it('return an error when route are not found', function () {
    $router = new Router();

    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/users';


    $response = $router->matchRoute();

    expect($response)->toBeString();
    expect(json_decode($response)->message)->toBe('Route not found');
});

it('return an error when middleware fails', function () {
    $router = new Router();

    $router->addRoute('GET', '/users', function ($params) {
        return 'success';
    }, [JwtMiddleware::class]);

    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/users';
    $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer invalid_token';
    $response = $router->matchRoute();

    expect($response)->toBeString();
    expect(json_decode($response)->message)->toBe('Unauthorized');
});
