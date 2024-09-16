<?php

namespace App\Middlewares;

abstract class Middleware
{
    public function __construct()
    {
        $this->init();
    }

    abstract public function init(): void;
}
