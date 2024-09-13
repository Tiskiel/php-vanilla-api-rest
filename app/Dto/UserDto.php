<?php

namespace App\Dto\User;

final class UserDto
{
    public function __construct(
        public string $uuid,
        public string $first_name,
        public string $last_name,
    ) {
    }
}
