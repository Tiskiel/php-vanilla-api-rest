<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Repositories\ValidatorRepository;
use InvalidArgumentException;

final class ValidatorService
{
    private ValidatorRepository $_repository;
    private UserRepository $_userRepository;

    /**
     * @var string[]
     */
    private array $allowedTables = ['users', 'roles', 'permissions'];
    /**
     * @var array<string, string[]>
     */
    private array $allowedColumns = [
        'users' => ['uuid', 'first_name', 'last_name', 'updated_at'],
    ];


    /**
     * @param \PDO|null $pdo
     */
    public function __construct(
        private ?\PDO $pdo = null
    )
    {
        $this->_repository = $this->pdo ? new ValidatorRepository($this->pdo) : new ValidatorRepository();
        $this->_userRepository = $this->pdo ? new UserRepository($this->pdo) : new UserRepository();
    }

    public function exist(string $table, string $column, string $value): bool
    {
        if (!in_array($table, $this->allowedTables)) {
            throw new InvalidArgumentException('Table not allowed');
        }

        if (!in_array($column, $this->allowedColumns[$table])) {
            throw new InvalidArgumentException('Column not allowed');
        }

        return $this->_repository->exist($table, $column, $value);
    }

    /**
     * @return array<string, string>
     */
    public function validateNames(string $firstName, string $lastName): array
    {
        /**
         * @var array<string, string>
         */
        $errors = [];

        if (!$firstName) {
            $errors['first_name'] = 'First name is required';
        }

        if (empty($lastName)) {
            $errors['last_name'] = 'Last name is required';
        }

        return $errors;
    }
}
