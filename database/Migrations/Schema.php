<?php

namespace Database\Migrations;

final class Schema
{
    public function __construct(
        private \PDO $pdo
    )
    {
    }

    public function createTable(string $table, array $columns): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS $table (";

        foreach ($columns as $column) {
            $sql .= "$column, ";
        }

        $sql = rtrim($sql, ', ');
        $sql .= ')';

        $this->execute($sql);
    }

    public function dropTable(string $table): void
    {
        $sql = "DROP TABLE IF EXISTS $table";

        $this->execute($sql);
    }

    private function execute(string $sql): void
    {
        $this->pdo->exec($sql);
    }
}
