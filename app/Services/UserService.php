<?php

namespace App\Services;

use App\Dto\UserCreateDto;
use App\Dto\UserUpdateDto;
use App\Repositories\UserRepository;

final class UserService
{
    private UserRepository $_repository;
    private ValidatorService $_validatorService;

    public function __construct(
        // Used in the tests context
        private ?\PDO $pdo = null
    )
    {
        $this->_repository = $this->pdo ? new UserRepository($this->pdo) : new UserRepository();
        $this->_validatorService = $this->pdo ? new ValidatorService($this->pdo) : new ValidatorService();
    }

    public function index(string $firstName, string $lastName): array
    {
        return $this->_repository->index();
    }

    public function store(string $firstName, string $lastName): bool
    {
        $dto = new UserCreateDto($firstName, $lastName);

        return $this->_repository->store($dto);
    }

    public function update(string $uuid, string $firstName, string $lastName): bool
    {
        $dto = new UserUpdateDto($uuid, $firstName, $lastName);

        return $this->_repository->update($dto);
    }

    public function delete(string $uuid): bool
    {
        try {
            if(!$this->_validatorService->exist('users', 'uuid', $uuid)) {
                return false;
            }

            return $this->_repository->delete($uuid);
        } catch (\Throwable $th) {
            return false;
        }
    }
}
