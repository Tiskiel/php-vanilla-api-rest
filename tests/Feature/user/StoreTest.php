<?php

use App\Dto\UserCreateDto;
use App\Http\Controllers\UserController;
use App\Repositories\UserRepository;
use App\Services\UserService;

it('should store a user', function () {
    $userCreateDto = new UserCreateDto('John', 'Doe');

    $userRepository = new UserRepository($this->pdo);

    expect($userRepository->store($userCreateDto))->toBeTrue();
});

it('should not store a user without a first name using the service', function () {
    $service = new UserService($this->pdo);
    $errors = $service->store('', 'Doe');

    expect($errors)->toBeArray();
    expect($errors['errors']['first_name'])->toBe('First name is required');
});

it('should not store a user without a last name and first name using the service', function () {
    $service = new UserService($this->pdo);
    $errors = $service->store('', '');

    expect($errors)->toBeArray();
    expect(count($errors['errors']))->toBe(2);
    expect($errors['errors']['first_name'])->toBe('First name is required');
    expect($errors['errors']['last_name'])->toBe('Last name is required');
});

it('should not store a user without a last name using the service', function () {
    $service = new UserService($this->pdo);
    $errors = $service->store('John', '');

    expect($errors)->toBeArray();
    expect($errors['errors']['last_name'])->toBe('Last name is required');
});

it('should store a user using the service', function () {
    $service = new UserService($this->pdo);

    expect($service->store('Sacha', 'Ketchum'))->toBeTrue();
});

it('should not store a user without a first name via the controller', function () {
    $controller = new UserController($this->pdo);

    $response = $controller->store('', 'Doe');

    expect(json_decode($response, true)['errors']['first_name'])->toBe('First name is required');
});

it('should not store a user without a last name and first name via the controller', function () {
    $controller = new UserController($this->pdo);

    $response = $controller->store('', '');

    expect(json_decode($response, true)['errors']['first_name'])->toBe('First name is required');
    expect(json_decode($response, true)['errors']['last_name'])->toBe('Last name is required');
});

it('should not store a user without a last name via the controller', function () {
    $controller = new UserController($this->pdo);

    $response = $controller->store('John', '');

    expect(json_decode($response, true)['errors']['last_name'])->toBe('Last name is required');
});

it('should store a user via the controller', function () {
    $controller = new UserController($this->pdo);

    $response = $controller->store('Sacha', 'Ketchum');

    expect(json_decode($response, true)['message'])->toBe('User created successfully');
});

it('is return 201 status code when store a user via the controller', function () {
    $controller = new UserController($this->pdo);

    $controller->store('Sacha', 'Ketchum');

    expect(http_response_code())->toBe(201);
});

it('is return 404 status code when store a user without a first name via the controller', function () {
    $controller = new UserController($this->pdo);

    $controller->store('', 'Doe');

    expect(http_response_code())->toBe(404);
});
