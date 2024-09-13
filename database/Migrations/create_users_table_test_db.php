<?php

use Database\Migrations\Schema;

require_once __DIR__ . '/../../vendor/autoload.php';

$columns = [
    'uuid CHAR(36) PRIMARY KEY',
    'first_name VARCHAR(100) NOT NULL',
    'last_name VARCHAR(100) NOT NULL',
    'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
    'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
];

$databaseTest = database(true);
$databaseTest->connection();
$schemaTest = new Schema($databaseTest->getPdo());
$schemaTest->createTable('users', $columns);
$databaseTest->disconnect();
