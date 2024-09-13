<?php

use App\Helpers\Json;
use Database\Database;

function database(bool $isTest = false): Database
{
    $database = Database::getInstance();
    $database->setIsTest($isTest);
    $database->connection();
    return $database;
}

function refreshTable(string $table, bool $isTest = false, ?\PDO $pdo = null): void
{
    $sql = "TRUNCATE TABLE $table";

    try {
        $statement = $pdo->prepare($sql);
        $statement->execute();
    } catch (PDOException $e) {
        Json::response(['error' => $e->getMessage()], 500);
    }
}

function testPDO(): \PDO
{
    $config = require __DIR__ . '/../config/database.php';

    $pdo = new PDO(
        "{$config['db_connection']}:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_test_database']}",
        $config['db_username'],
        $config['db_password']
    );

    return $pdo;
}
