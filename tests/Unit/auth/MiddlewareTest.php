<?php

use App\Helpers\Auth;
use App\Middlewares\JwtMiddleware;

test('the jwt token middleware should return 401 if token is not found', function () {
    $queryParams = ['Authorization' => null];
    new JwtMiddleware($queryParams);
})->throws(\Exception::class, 'Token not found');

test('the jwt token middleware should return 401 if token is invalid', function () {
    $queryParams = ['Authorization' => 'Bearer invalid'];
    new JwtMiddleware($queryParams);
})->throws(\Exception::class, 'Unauthorized');

test('the jwt token middleware should return void', function () {
    Auth::init();
    $token = Auth::generateToken(['uuid' => '123', 'first_name' => 'John', 'last_name' => 'Doe']);

    $queryParams = ['Authorization' => 'Bearer ' . $token];
    $middleware = new JwtMiddleware($queryParams);

    expect($middleware)->not()->toThrow(\Exception::class);
});

test('the jwt token middleware should return 400 if token is malformed', function () {
    $queryParams = ['Authorization' => 'invalid_token'];

    new JwtMiddleware($queryParams);
})->throws(\Exception::class, 'Malformed Authorization header');
