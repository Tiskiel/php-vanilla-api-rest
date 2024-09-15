<?php

namespace App\Dto;

final class UserUpdateDto extends Dto
{
    public function __construct(
        protected string $uuid,
        protected string $first_name,
        protected string $last_name,
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
