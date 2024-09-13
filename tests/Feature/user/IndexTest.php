<?php

use App\Dto\UserCreateDto;
use App\Repositories\UserRepository;

beforeEach(function () {
    $users = [];
    $this->userDto = new UserCreateDto('John', 'Doe');
    $this->userDtoTwo = new UserCreateDto('Jane', 'Doe');
    $this->userDtoThree = new UserCreateDto('John', 'Smith');

    $users[] = $this->userDto;
    $users[] = $this->userDtoTwo;
    $users[] = $this->userDtoThree;

    $this->repository = new UserRepository($this->pdo);

    foreach ($users as $user) {
        $this->repository->store($user);
    }
});

it('should find all users', function () {
    $users = $this->repository->index();

    expect($users)->toHaveCount(3);
});
