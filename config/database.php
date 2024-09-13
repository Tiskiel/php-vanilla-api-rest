<?php

use Dotenv\Dotenv;

if (!isset($_ENV['DB_CONNECTION'])) {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}

return [
    'db_connection' => $_ENV['DB_CONNECTION'] ?? 'mysql',
    'db_host' => $_ENV['DB_HOST'] ?? 'localhost',
    'db_port' => $_ENV['DB_PORT'] ?? '3306',
    'db_database' => $_ENV['DB_DATABASE'] ?? 'test',
    'db_username' => $_ENV['DB_USERNAME'] ?? 'root',
    'db_password' => $_ENV['DB_PASSWORD'] ?? '',
    'db_test_database' => $_ENV['DB_TEST_DATABASE'] ?? 'test',
];
