<?php

use App\Dto\UserCreateDto;
use App\Repositories\UserRepository;
use App\Services\UserService;

beforeEach(function () {
    $this->userDto = new UserCreateDto('John', 'Doe');
    $this->repository = new UserRepository($this->pdo);
    $this->user = $this->repository->store($this->userDto);
});

it('should delete a user', function () {
    $this->repository->delete($this->userDto->getUuid());

    $statement = $this->pdo->prepare('SELECT uuid, first_name, last_name FROM users WHERE uuid = :uuid');
    $statement->execute([':uuid' => $this->userDto->getUuid()]);
    $user = $statement->fetch(\PDO::FETCH_OBJ);

    expect($user)->toBeFalse();
});

it('should not delete a user that does not exist', function () {
    expect($this->repository->delete('non-existent-uuid'))->toBeFalse();
});

it('should delete a user via the service', function () {
    $service = new UserService($this->pdo);

    $service->delete($this->userDto->getUuid());

    $statement = $this->pdo->prepare('SELECT uuid, first_name, last_name FROM users WHERE uuid = :uuid');
    $statement->execute([':uuid' => $this->userDto->getUuid()]);
    $user = $statement->fetch(\PDO::FETCH_OBJ);

    expect($user)->toBeFalse();
});

it('should not delete a user that does not exist via the service', function () {
    $service = new UserService($this->pdo);

    expect($service->delete('non-existent-uuid'))->toBeArray();
});

it('is protected from injection', function () {
    $service = new UserService($this->pdo);

    expect($service->delete('DROP TABLE users'))->toBeArray();
});

it('is return an array with errors', function () {
    $service = new UserService($this->pdo);
    $errors = $service->delete('non-existent-uuid');

    expect($errors)->toBeArray();
    expect($errors['uuid'])->toBe('User not found');
});
