<?php

use App\Dto\UserCreateDto;
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
    expect($errors['first_name'])->toBe('First name is required');
});

it('should not store a user without a last name and first name using the service', function () {
    $service = new UserService($this->pdo);
    $errors = $service->store('', '');

    expect($errors)->toBeArray();
    expect(count($errors))->toBe(2);
    expect($errors['first_name'])->toBe('First name is required');
    expect($errors['last_name'])->toBe('Last name is required');
});

it('should not store a user without a last name using the service', function () {
    $service = new UserService($this->pdo);
    $errors = $service->store('John', '');

    expect($errors)->toBeArray();
    expect($errors['last_name'])->toBe('Last name is required');
});

it('should store a user using the service', function () {
    $service = new UserService($this->pdo);

    expect($service->store('Sacha', 'Ketchum'))->toBeTrue();
});
