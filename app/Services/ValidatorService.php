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

    /**
     * @return array<string, string>|bool
     */
    public function unique(string $table, string $column, string $value): array|bool
    {
        /**
         * @var array<string, string>
         */
        $errors = [];

        if (!in_array($table, $this->allowedTables)) {
            $errors['table'] = 'Table not allowed';
        }

        if (!in_array($column, $this->allowedColumns[$table])) {
            $errors['column'] = 'Column not allowed';
        }

        if (!$this->_repository->unique($table, $column, $value)) {
            $errors[$column] = 'Value already exists';
        }

        if(!empty($errors)) {
            return $errors;
        }

        return $this->_repository->unique($table, $column, $value);
    }

    /**
     * This method prepares the names for the user
     * and prevent SQL injection attacks.
     *
     * @return bool|array<string, string>
     * @param string|array<string, string> $value
     */
    public function prepareNames(string|array $data): bool|array
    {
        /**
         * @var array<string, string>
         */
        $errors = [];

        if(is_array($data)) {
            foreach ($data as $key => $string) {
                trim($string);

                if(! preg_match('/^[a-zA-Z\s-]+$/', $string)) {
                    $errors['error'] = 'Invalid value';
                    break;
                } else {
                    $data[$key] = $string;
                }
            }
        } else {
            trim($data);

            if(! preg_match('/^[a-zA-Z\s-]+$/', $data)) {
                $errors['error'] = 'Invalid value';
            } else {
                $data = $data;
            }
        }

        if(!empty($errors)) {
            return $errors;
        }

        return $data;
    }
}
