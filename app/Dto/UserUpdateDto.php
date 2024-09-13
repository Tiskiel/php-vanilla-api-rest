<?php

namespace App\Dto;

final class UserUpdateDto
{
    public function __construct(
        private string $uuid,
        private string $first_name,
        private string $last_name,
    ) {
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
