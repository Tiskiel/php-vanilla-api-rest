<?php

namespace Database\Seeders;

final class Seeder
{
    /**
     * Seeder constructor.
     *
     * @param \PDO $pdo
     * @param string $table
     * @param array<array<string, string>> $data
     */
    public function __construct(
        private \PDO $pdo,
        private string $table,
        private array $data
    ){}

    public function seed(): void
    {
        foreach ($this->data as $data) {
            $columns = implode(', ', array_keys($data));
            $values = implode(', ', array_fill(0, count($data), '?'));

            $statement = $this->pdo->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$values})");

            $statement->execute(array_values($data));
        }
    }
}
