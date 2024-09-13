<?php

namespace App\Services;

use App\Dto\UserCreateDto;
use App\Dto\UserUpdateDto;
use App\Repositories\UserRepository;

use function PHPUnit\Framework\isEmpty;

final class UserService
{
    private UserRepository $_repository;
    private ValidatorService $_validatorService;
    /**
     * @var array<string, string>
     */
    private array $errors = [];

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

    /**
     * @return array<string, string>
     */
    public function update(string $uuid, string $firstName, string $lastName): array
    {
        try {
            if(!$this->_validatorService->exist('users', 'uuid', $uuid)) {
                $this->errors['uuid'] = 'User not found';
            }

            if(isEmpty($this->_validatorService->validateNames($firstName, $lastName))) {
                $this->errors = array_merge($this->errors, $this->_validatorService->validateNames($firstName, $lastName));
            }

            if(!empty($this->errors)) {
                return $this->errors;
            }

            $dto = new UserUpdateDto($uuid, $firstName, $lastName);

             return $this->_repository->update($dto);
        } catch (\Throwable $th) {
            $this->errors['exception'] = 'An error occurred';

            return $this->errors;
        }
    }

    /**
     * @return array<string, string>
     */
    public function delete(string $uuid): array
    {
        try {
            if(!$this->_validatorService->exist('users', 'uuid', $uuid)) {
                $this->errors['uuid'] = 'User not found';
            }

            if(!empty($this->errors)) {
                return $this->errors;
            }

            return $this->_repository->delete($uuid);
        } catch (\Throwable $th) {
            $this->errors['exception'] = 'An error occurred';

            return $this->errors;
        }
    }
}
