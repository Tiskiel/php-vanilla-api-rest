<?php

use App\Dto\UserCreateDto;
use App\Http\Controllers\UserController;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Routes\Router;

beforeEach(function () {
    $this->userDto = new UserCreateDto('John', 'Doe');
    $this->repository = new UserRepository($this->pdo);
    $this->repository->store($this->userDto);
});

it('should retrieve a user', function () {
    $user = $this->repository->show($this->userDto->getUuid());

    expect($user['first_name'])->toBe('John');
    expect($user['last_name'])->toBe('Doe');
});

it('should not retrieve a user that does not exist', function () {
    expect($this->repository->show('non-existent-uuid'))->toBeFalse();
});

it('should retrieve a user via the service', function () {
    $service = new UserService($this->pdo);

    $user = $service->show($this->userDto->getUuid());

    expect($user['first_name'])->toBe('John');
    expect($user['last_name'])->toBe('Doe');
});

it('should not retrieve a user that does not exist via the service', function () {
    $service = new UserService($this->pdo);

    expect($service->show('non-existent-uuid'))->toBeArray();
});

it('should receive an array with errors', function () {
    $service = new UserService($this->pdo);
    $errors = $service->show('non-existent-uuid');

    expect($errors['errors'])->toBeArray();
    expect($errors['errors']['uuid'])->toBe('User not found');
});


it('should retrieve a user via the controller' , function () {
    $controller = new UserController($this->pdo);

    $response = $controller->show($this->userDto->getUuid());

    expect($response)->toBeString();
    expect(json_decode($response, true)['first_name'])->toBe('John');
    expect(json_decode($response, true)['last_name'])->toBe('Doe');
});

it('should not retrieve a user that does not exist via the controller', function () {
    $controller = new UserController($this->pdo);

    $response = $controller->show('non-existent-uuid');

    expect($response)->toBeString();
    expect(json_decode($response, true)['errors'])->toBeArray();
    expect(json_decode($response, true)['errors']['uuid'])->toBe('User not found');
});

it('should receive a 404 status code', function () {
    $controller = new UserController($this->pdo);

    $response = $controller->show('non-existent-uuid');

    expect($response)->toBeString();
    expect(http_response_code())->toBe(404);
});

it('should receive a 200 status code', function () {
    $controller = new UserController($this->pdo);

    $response = $controller->show($this->userDto->getUuid());

    expect($response)->toBeString();
    expect(http_response_code())->toBe(200);
});

it('should retrieve a user via the router' , function () {
    $router = new Router();

    $router->addRoute('GET', '/users/{uuid}', function ($uuid) {
        $controller = new UserController($this->pdo);
        return $controller->show($uuid);
    });

    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/users/' . $this->userDto->getUuid();

    $response = $router->matchRoute();

    expect($response)->toBeString();
    expect(json_decode($response, true)['first_name'])->toBe('John');
    expect(json_decode($response, true)['last_name'])->toBe('Doe');
});

it('should not retrieve a user that does not exist via the router', function () {
    $router = new Router();

    $router->addRoute('GET', '/users/{uuid}', function ($uuid) {
        $controller = new UserController($this->pdo);
        return $controller->show($uuid);
    });

    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/users/non-existent-uuid';

    $response = $router->matchRoute();

    expect($response)->toBeString();
    expect(json_decode($response, true)['errors'])->toBeArray();
    expect(json_decode($response, true)['errors']['uuid'])->toBe('User not found');
});

it('should receive a 404 status code via the router', function () {
    $router = new Router();

    $router->addRoute('GET', '/users/{uuid}', function ($uuid) {
        $controller = new UserController($this->pdo);
        return $controller->show($uuid);
    });

    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/users/non-existent-uuid';

    $response = $router->matchRoute();

    expect($response)->toBeString();
    expect(http_response_code())->toBe(404);
});

it('should receive a 200 status code via the router', function () {
    $router = new Router();

    $router->addRoute('GET', '/users/{uuid}', function ($uuid) {
        $controller = new UserController($this->pdo);
        return $controller->show($uuid);
    });

    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/users/' . $this->userDto->getUuid();

    $response = $router->matchRoute();

    expect($response)->toBeString();
    expect(http_response_code())->toBe(200);
});

it('should receive a array with errors via the router', function () {
    $router = new Router();

    $router->addRoute('GET', '/users/{uuid}', function ($uuid) {
        $controller = new UserController($this->pdo);
        return $controller->show($uuid);
    });

    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/users/non-existent-uuid';

    $response = $router->matchRoute();

    expect($response)->toBeString();
    expect(json_decode($response, true)['errors'])->toBeArray();
    expect(json_decode($response, true)['errors']['uuid'])->toBe('User not found');
});
