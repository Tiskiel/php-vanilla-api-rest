<?php

namespace Database;

use PDO;
use App\Helpers\Json;
use PDOException;

final class Database
{
    private ?PDO $pdo = null;
    private array $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../config/database.php';
    }

    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    public function connection(bool $isTest = false): void
    {
        $dbName = $isTest ? $this->config['db_test_database'] : $this->config['db_database'];

        try {
            $this->pdo = new PDO(
                "{$this->config['db_connection']}:host={$this->config['db_host']};port={$this->config['db_port']};dbname={$dbName}",
                $this->config['db_username'],
                $this->config['db_password']
            );

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            echo 'Connected to the database successfully';
        } catch (PDOException $e) {
            Json::response(['error' => $e->getMessage()], 500);
        }
    }
}
