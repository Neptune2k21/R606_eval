<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        // Base de données SQLite en mémoire pour les tests (pas besoin de MySQL)
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $this->pdo->exec(
            'CREATE TABLE db_table (
                id   INTEGER PRIMARY KEY AUTOINCREMENT,
                text VARCHAR(100) NOT NULL
            )'
        );
    }

    public function testGetDataReturnsEmptyArrayWhenNoRows(): void
    {
        $rows = $this->pdo->query('SELECT id, text FROM db_table')->fetchAll();
        $this->assertIsArray($rows);
        $this->assertEmpty($rows);
    }

    public function testGetDataReturnsInsertedRows(): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO db_table (text) VALUES (:text)');
        $stmt->execute([':text' => 'Hello']);
        $stmt->execute([':text' => 'World']);

        $rows = $this->pdo->query('SELECT id, text FROM db_table')->fetchAll();

        $this->assertCount(2, $rows);
        $this->assertSame('Hello', $rows[0]['text']);
        $this->assertSame('World', $rows[1]['text']);
    }

    public function testRowHasExpectedKeys(): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO db_table (text) VALUES (:text)');
        $stmt->execute([':text' => 'Test']);

        $rows = $this->pdo->query('SELECT id, text FROM db_table')->fetchAll();

        $this->assertArrayHasKey('id', $rows[0]);
        $this->assertArrayHasKey('text', $rows[0]);
    }
}