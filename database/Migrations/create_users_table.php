<?php

use Database\Database;
use Database\Migrations\Schema;

require_once __DIR__ . '/../../vendor/autoload.php';

$columns = [
    'uuid CHAR(36) PRIMARY KEY',
    'first_name VARCHAR(100) NOT NULL',
    'last_name VARCHAR(100) NOT NULL',
    'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
    'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
];

$database = new Database();
$database->connection();

$schema = new Schema($database->getPdo());
$schema->createTable('users', $columns);
$database->disconnect();

$databaseTest = new Database();
$databaseTest->connection(true);

$schemaTest = new Schema($databaseTest->getPdo());
$schemaTest->createTable('users', $columns);
$databaseTest->disconnect();
