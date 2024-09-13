<?php

use Database\Migrations\Schema;

it('can create a table', function() {
    $columns = [
        'id INT PRIMARY KEY',
        'name VARCHAR(100) NOT NULL',
    ];

    $schema = new Schema($this->pdo);
    $schema->createTable('test', $columns);

    $statement = $this->pdo->query('SELECT * FROM users');
    $this->assertCount(0, $statement->fetchAll());
});

it('can drop a table', function() {
    $columns = [
        'id INT PRIMARY KEY',
        'name VARCHAR(100) NOT NULL',
    ];

    $schema = new Schema($this->pdo);
    $schema->createTable('test', $columns);
    $schema->dropTable('test');

    $query = $this->pdo->query("SELECT EXISTS (
        SELECT 1 FROM pg_tables WHERE schemaname = 'public' AND tablename = 'test'
    )");
    $exists = $query->fetchColumn();

    $this->assertFalse((bool) $exists, 'Table should be dropped');
});
