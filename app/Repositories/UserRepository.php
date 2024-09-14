<?php

namespace App\Repositories;

use App\Dto\UserCreateDto;
use App\Dto\UserUpdateDto;
use App\Repositories\Repository;

final class UserRepository extends Repository
{
    public function index(?string $first_name = null, ?string $last_name = null): array
    {
        $sql = 'SELECT * FROM users';

        if ($first_name && $last_name) {
            $sql .= ' WHERE first_name ILIKE :first_name AND last_name ILIKE :last_name';
        } elseif ($first_name) {
            $sql .= ' WHERE first_name ILIKE :first_name';
        } elseif ($last_name) {
            $sql .= ' WHERE last_name ILIKE :last_name';
        }

        $statement = $this->pdo->query($sql);

        return $statement->fetchAll();
    }

    public function store(UserCreateDto $data): bool
    {
        $sql = 'INSERT INTO users (uuid, first_name, last_name) VALUES (:uuid, :first_name, :last_name)';

        $statement = $this->pdo->prepare($sql);

        $statement->execute([
            ':uuid' => $data->getUuid(),
            ':first_name' => $data->getFirstName(),
            ':last_name' => $data->getLastName(),
        ]);

        return $statement->rowCount() > 0;
    }

    public function update(UserUpdateDto $data): bool
    {
        $sql = "UPDATE users SET first_name=:first_name, last_name=:last_name WHERE uuid=:uuid";

        $statement = $this->pdo->prepare($sql);

        $statement->execute([
            ':uuid' => $data->getUuid(),
            ':first_name' => $data->getFirstName(),
            ':last_name' => $data->getLastName(),
        ]);

        return $statement->rowCount() > 0;
    }

    public function delete(string $uuid): bool
    {
        $sql = 'DELETE FROM users WHERE uuid = :uuid';

        $statement = $this->pdo->prepare($sql);

        $statement->execute([':uuid' => $uuid]);

        return $statement->rowCount() > 0;
    }
}
