<?php

use App\Dto\UserCreateDto;
use App\Dto\UserUpdateDto;
use App\Repositories\UserRepository;

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
