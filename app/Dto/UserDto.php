<?php

namespace App\Dto;

final class UserDto extends Dto
{
    public function __construct(
        public string $uuid,
        public string $first_name,
        public string $last_name,
    ) {
    }
}
