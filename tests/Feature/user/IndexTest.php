<?php

use App\Dto\UserCreateDto;
use App\Repositories\UserRepository;
use App\Services\UserService;

beforeEach(function () {
    $users = [];
    $this->userDto = new UserCreateDto('Sasha', 'Ketchum');
    $this->userDtoTwo = new UserCreateDto('Limule', 'Tempesta');
    $this->userDtoThree = new UserCreateDto('Morticia', 'Addams');
    $this->userDtoFour = new UserCreateDto('Gomez', 'Addams');

    $users[] = $this->userDto;
    $users[] = $this->userDtoTwo;
    $users[] = $this->userDtoThree;
    $users[] = $this->userDtoFour;

    $this->repository = new UserRepository($this->pdo);

    foreach ($users as $user) {
        $this->repository->store($user);
    }
});

it('should find all users', function () {
    $users = $this->repository->index();

    expect($users)->toHaveCount(4);
});

it('should find all users by first name', function () {
    $users = $this->repository->index('Sasha');

    expect($users)->toHaveCount(1);
    expect($users[0]['first_name'])->toBe('Sasha');
});

it('should find all users by last name', function () {
    $users = $this->repository->index(null, 'Addams');

    expect($users)->toHaveCount(2);
    expect($users[0]['last_name'])->toBe('Addams');
    expect($users[1]['last_name'])->toBe('Addams');
});

it('should find all users by first and last name', function () {
    $users = $this->repository->index('Morticia', 'Addams');

    expect($users)->toHaveCount(1);
    expect($users[0]['first_name'])->toBe('Morticia');
    expect($users[0]['last_name'])->toBe('Addams');
});

it('should find a user with a part of first name', function () {
    $users = $this->repository->index('Ash');

    expect($users)->toHaveCount(1);
    expect($users[0]['first_name'])->toBe('Sasha');
});

it('should find a user with a part of last name', function () {
    $users = $this->repository->index(null, 'Adda');

    expect($users)->toHaveCount(2);
    expect($users[0]['last_name'])->toBe('Addams');
    expect($users[1]['last_name'])->toBe('Addams');
});

it('should find a user with a part of first and last name', function () {
    $users = $this->repository->index('Mort', 'Adda');

    expect($users)->toHaveCount(1);
    expect($users[0]['first_name'])->toBe('Morticia');
    expect($users[0]['last_name'])->toBe('Addams');
});

it('throw an error when try to inject SQL', function () {
    $service = new UserService($this->pdo);

    $errors = $service->index('Sasha', 'Ketchum; DROP TABLE users;');

    expect($errors)->toHaveCount(1);
    expect($errors['error'])->toBe('Invalid value');
});

it('throw an error when try to inject SQL via first name', function () {
    $service = new UserService($this->pdo);

    $errors = $service->index('Sasha; DROP TABLE users;', 'Ketchum');

    expect($errors)->toHaveCount(1);
    expect($errors['error'])->toBe('Invalid value');
});

it('throw an error when try to inject SQL via last and first name', function () {
    $service = new UserService($this->pdo);

    $errors = $service->index('Sasha; DROP TABLE users;', 'Ketchum; DROP TABLE users;');

    expect($errors)->toHaveCount(1);
    expect($errors['error'])->toBe('Invalid value');
});

it('should find all users by first name via service', function () {
    $service = new UserService($this->pdo);

    $users = $service->index('Sasha');

    expect($users)->toHaveCount(1);
    expect($users[0]['first_name'])->toBe('Sasha');
});

it('should find all users by last name via service', function () {
    $service = new UserService($this->pdo);

    $users = $service->index(null, 'Addams');

    expect($users)->toHaveCount(2);
    expect($users[0]['first_name'])->toBe('Morticia');
    expect($users[1]['first_name'])->toBe('Gomez');
});

it('should find all users by first and last name via service', function () {
    $service = new UserService($this->pdo);

    $users = $service->index('Morticia', 'Addams');

    expect($users)->toHaveCount(1);
    expect($users[0]['first_name'])->toBe('Morticia');
    expect($users[0]['last_name'])->toBe('Addams');
});

it('should find a user with a part of first name via service', function () {
    $service = new UserService($this->pdo);

    $users = $service->index('Ash');

    expect($users)->toHaveCount(1);
    expect($users[0]['first_name'])->toBe('Sasha');
});

it('should find a user with a part of last name via service', function () {
    $service = new UserService($this->pdo);

    $users = $service->index(null, 'Adda');

    expect($users)->toHaveCount(2);
    expect($users[0]['last_name'])->toBe('Addams');
    expect($users[1]['last_name'])->toBe('Addams');
});

it('should find a user with a part of first and last name via service', function () {
    $service = new UserService($this->pdo);

    $users = $service->index('Mort', 'Adda');

    expect($users)->toHaveCount(1);
    expect($users[0]['first_name'])->toBe('Morticia');
    expect($users[0]['last_name'])->toBe('Addams');
});
