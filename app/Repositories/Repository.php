<?php

namespace App\Repositories;

abstract class Repository
{
    public function __construct(
        protected ?\PDO $pdo = null
    )
    {
        $this->pdo =  $pdo ?? database()->getPdo();
    }
}
