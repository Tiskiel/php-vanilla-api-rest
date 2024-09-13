<?php

require __DIR__ . '/vendor/autoload.php';

use Database\Database;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

(new Database())->connection();
