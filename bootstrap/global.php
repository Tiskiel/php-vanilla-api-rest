<?php

use Database\Database;

function database(bool $isTest = false): Database
{
    $database = Database::getInstance();
    $database->setIsTest($isTest);
    return $database;
}
