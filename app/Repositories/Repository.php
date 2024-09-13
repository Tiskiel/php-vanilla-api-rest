<?php

namespace App\Repositories;

use Database\Database;

abstract class Repository
{
    protected \PDO $pdo;

    public function __construct(
    )
    {
        $this->pdo = database()->getPdo();
    }
}
