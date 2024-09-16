<?php

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Helpers\Auth;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

Auth::init();
