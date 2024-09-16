<?php

namespace App\Services;

use App\Dto\UserCreateDto;
use App\Dto\UserUpdateDto;
use App\Repositories\UserRepository;

final class UserService
{
    private UserRepository $_repository;
    private ValidatorService $_validatorService;

    /**
     * @var array<string, array<string, string>>
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

    /**
     * @return array<string, string>
     */
    public function index(?string $firstName = null, ?string $lastName = null): array
    {
        /**
         * @var array<string, string>
         */
        $names = $firstName && $lastName ? [
            'first_name' => $firstName,
            'last_name' => $lastName
        ] : [];

        /**
         * @var bool|array<string, string>
         */
        $prepareNames = false;

        if(!empty($names)) {
            $prepareNames = $this->_validatorService->prepareNames($names);
        } elseif ($firstName && !$lastName) {
            $prepareNames = $this->_validatorService->prepareNames($firstName);
        } elseif (!$firstName && $lastName) {
            $prepareNames = $this->_validatorService->prepareNames($lastName);
        }

        if(is_array($prepareNames) && array_key_exists('error', $prepareNames)) {
            $this->errors['errors'] = array_merge($this->errors, $prepareNames);

            return $this->errors;
        }

        return $this->_repository->index($firstName, $lastName);
    }

    /**
     * @return array<string, string>|bool
     */
    public function show(string $uuid): array|bool
    {
        if(!$this->_validatorService->exist('users', 'uuid', $uuid)) {
            $this->errors['errors']['uuid'] = 'User not found';

            return $this->errors;
        }

        return $this->_repository->show($uuid);
    }

    /**
     * @return array<string, array<string, string>>|bool
     */
    public function store(string $firstName, string $lastName): array|bool
    {
        if(!empty($this->_validatorService->validateNames($firstName, $lastName))) {
            $this->errors['errors'] = array_merge($this->errors, $this->_validatorService->validateNames($firstName, $lastName));
        }

        $dto = new UserCreateDto($firstName, $lastName);

        if(is_array($this->_validatorService->unique('users', 'uuid', $dto->getUuid()))) {
            $this->errors['errors'] = array_merge($this->errors, $this->_validatorService->unique('users', 'uuid', $dto->getUuid()));
        }

        if(!empty($this->errors)) {
            return $this->errors;
        }

        return $this->_repository->store($dto);
    }

    /**
     * @return array<string, string>|bool
     */
    public function update(string $uuid, string $firstName, string $lastName): array|bool
    {
        try {
            if(!$this->_validatorService->exist('users', 'uuid', $uuid)) {
                $this->errors['errors']['uuid'] = 'User not found';
            }

            if(!empty($this->_validatorService->validateNames($firstName, $lastName))) {
                $this->errors['errors'] = array_merge($this->errors, $this->_validatorService->validateNames($firstName, $lastName));
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
     * @return array<string, string>|bool
     */
    public function delete(string $uuid): array|bool
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
