<?php

namespace App\Repositories;

final class ValidatorRepository extends Repository
{
    public function exist(string $table, string $column, string $value): bool
    {
        $sql = "SELECT COUNT(*) FROM $table WHERE $column = :$column";

        $statement = $this->pdo->prepare($sql);

        $statement->execute([":$column" => $value]);

        return $statement->fetchColumn() > 0;
    }

    public function unique(string $table, string $column, string $value): bool
    {
        $sql = "SELECT COUNT(*) FROM $table WHERE $column = :$column";

        $statement = $this->pdo->prepare($sql);

        $statement->execute([":$column" => $value]);

        return $statement->fetchColumn() === 0;
    }
}
