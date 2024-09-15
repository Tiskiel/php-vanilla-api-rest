<?php

namespace App\Dto;

abstract class Dto
{
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
