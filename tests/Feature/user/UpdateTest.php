<?php

use App\Dto\UserCreateDto;
use App\Dto\UserUpdateDto;
use App\Repositories\UserRepository;
use App\Services\UserService;

beforeEach(function () {
    $this->userDto = new UserCreateDto('John', 'Doe');
    $this->repository = new UserRepository($this->pdo);
    $this->user = $this->repository->store($this->userDto);
});

it('should update a user', function () {
    $userUpdateDto = new UserUpdateDto($this->userDto->getUuid(), 'Bat', 'man');

    $this->repository->update($userUpdateDto);

    $statement = $this->pdo->prepare('SELECT uuid, first_name, last_name FROM users WHERE uuid = :uuid');
    $statement->execute([':uuid' => $this->userDto->getUuid()]);
    $user = $statement->fetch(\PDO::FETCH_OBJ);

    expect($user->uuid)->toBe($this->userDto->getUuid());
    expect($user->first_name)->toBe('Bat');
    expect($user->last_name)->toBe('man');
});

it('should not update a user that does not exist', function () {
    $userUpdateDto = new UserUpdateDto('non-existent-uuid', 'Bat', 'man');

    expect($this->repository->update($userUpdateDto))->toBeFalse();

    $statement = $this->pdo->prepare('SELECT uuid, first_name, last_name FROM users WHERE uuid = :uuid');
    $statement->execute([':uuid' => $userUpdateDto->getUuid()]);
    $user = $statement->fetch(\PDO::FETCH_OBJ);

    expect($user)->toBeFalse();
});

it('should update a user via the service', function () {
    $service = new UserService($this->pdo);

    $service->update($this->userDto->getUuid(), 'Bat', 'man');

    $statement = $this->pdo->prepare('SELECT uuid, first_name, last_name FROM users WHERE uuid = :uuid');
    $statement->execute([':uuid' => $this->userDto->getUuid()]);
    $user = $statement->fetch(\PDO::FETCH_OBJ);

    expect($user->uuid)->toBe($this->userDto->getUuid());
    expect($user->first_name)->toBe('Bat');
    expect($user->last_name)->toBe('man');
});

it('should not update a user that does not exist via the service', function () {
    $service = new UserService($this->pdo);

    expect($service->update('non-existent-uuid', 'Bat', 'man'))->toBeArray();

    $statement = $this->pdo->prepare('SELECT uuid, first_name, last_name FROM users WHERE uuid = :uuid');
    $statement->execute([':uuid' => 'non-existent-uuid']);
    $user = $statement->fetch(\PDO::FETCH_OBJ);

    expect($user)->toBeFalse();
});

it('return an error when the first name is empty', function () {
    $service = new UserService($this->pdo);
    $errors = $service->update($this->userDto->getUuid(),'', 'man');

    expect($errors)->toBeArray();
    expect($errors)->toHaveKey('first_name');
    expect($errors['first_name'])->toBe('First name is required');
});

it('return an error when the last name is empty', function () {
    $service = new UserService($this->pdo);
    $errors = $service->update($this->userDto->getUuid(),'Bat', '');

    expect($errors)->toBeArray();
    expect($errors)->toHaveKey('last_name');
    expect($errors['last_name'])->toBe('Last name is required');
});

it('return an error when the first name and last name are empty', function () {
    $service = new UserService($this->pdo);
    $errors = $service->update($this->userDto->getUuid(), '', '');

    expect($errors)->toBeArray();
    expect($errors)->toHaveKey('first_name');
    expect($errors['first_name'])->toBe('First name is required');
    expect($errors)->toHaveKey('last_name');
    expect($errors['last_name'])->toBe('Last name is required');
});
