<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Dto\UserCreateDto;
use Database\Seeders\Seeder;

$users = [
    (new UserCreateDto('Ash', 'Ketchum'))->toArray(),
    (new UserCreateDto('Misty', 'Williams'))->toArray(),
    (new UserCreateDto('Brock', 'Johnson'))->toArray(),
    (new UserCreateDto('Jessie', 'Smith'))->toArray(),
    (new UserCreateDto('James', 'Brown'))->toArray(),
    (new UserCreateDto('Meowth', 'Davis'))->toArray(),
];

$database = database();
$database->connection();

(new Seeder($database->getPdo(), 'users', $users))->seed();
