<?php

use App\Helpers\Json;
use Database\Database;

function database(bool $isTest = false): Database
{
    $database = Database::getInstance();
    $database->setIsTest($isTest);
    return $database;
}

function refreshTable(string $table, bool $isTest = false): void
{
    $pdo = database($isTest)->getPdo();
    $sql = "TRUNCATE TABLE $table";

    try {
        $statement = $pdo->prepare($sql);
        $statement->execute();
    } catch (PDOException $e) {
        Json::response(['error' => $e->getMessage()], 500);
    }
}
