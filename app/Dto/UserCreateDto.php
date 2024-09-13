<?php

namespace App\Dto\User;

use Ramsey\Uuid\Uuid;

final class UserCreateDto
{
    private string $uuid;

    public function __construct(
        private string $first_name,
        private string $last_name,
    ) {
        $this->uuid = Uuid::uuid4();
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }
}
