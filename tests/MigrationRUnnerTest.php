<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../migrations/Migration.php';
require_once __DIR__ . '/../migrations/MigrationRunner.php';

class MigrationRunnerTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    private function createMockMigration(string $version, bool $shouldFail = false): Migration
    {
        return new class ($version, $shouldFail) implements Migration {
            public function __construct(
                private string $version,
                private bool $shouldFail
            ) {}

            public function getVersion(): string
            {
                return $this->version;
            }

            public function getDescription(): string
            {
                return 'Migration de test ' . $this->version;
            }

            public function up(PDO $pdo): void
            {
                if ($this->shouldFail) {
                    throw new PDOException('Échec simulé');
                }

                $pdo->exec(
                    'CREATE TABLE IF NOT EXISTS test_' . $this->version . ' (id INTEGER PRIMARY KEY)'
                );
            }
        };
    }

    public function testMigrationsTableIsCreatedOnInstantiation(): void
    {
        new MigrationRunner($this->pdo);

        $tables = $this->pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='migrations'")->fetchAll();
        $this->assertCount(1, $tables);
    }

    public function testRunReturnsMigrationsStatus(): void
    {
        $runner  = new MigrationRunner($this->pdo);
        $results = $runner->run();

        $this->assertIsArray($results);
    }

    public function testStatusReturnsCorrectStructure(): void
    {
        $runner = new MigrationRunner($this->pdo);
        $status = $runner->status();

        $this->assertIsArray($status);

        foreach ($status as $entry) {
            $this->assertArrayHasKey('version', $entry);
            $this->assertArrayHasKey('description', $entry);
            $this->assertArrayHasKey('applied', $entry);
            $this->assertArrayHasKey('executed_at', $entry);
        }
    }
}