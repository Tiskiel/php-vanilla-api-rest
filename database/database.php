<?php

namespace Database;

use PDO;
use App\Helpers\Json;
use PDOException;

final class Database
{
    private static ?Database $instance = null;
    private ?PDO $pdo = null;
    private array $config;
    private bool $isTest = false;

    private function __construct()
    {
        $this->config = require __DIR__ . '/../config/database.php';
        $this->connection();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    public function setIsTest(bool $isTest): void
    {
        $this->isTest = $isTest;
    }

    private function connection(): void
    {
        $dbName = $this->isTest ? $this->config['db_test_database'] : $this->config['db_database'];

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

    public function disconnect(): void
    {
        $this->pdo = null;
        echo 'Disconnected from the database successfully';
    }
}
