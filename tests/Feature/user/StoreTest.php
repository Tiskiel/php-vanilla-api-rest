<?php

use App\Dto\UserCreateDto;
use App\Repositories\UserRepository;

it('should store a user', function () {
    $userCreateDto = new UserCreateDto('John', 'Doe');

    $userRepository = new UserRepository($this->pdo);

    expect($userRepository->store($userCreateDto))->toBeTrue();
});
