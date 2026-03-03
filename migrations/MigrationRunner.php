<?php

declare(strict_types=1);

class MigrationRunner
{
    private PDO $pdo;
    private string $driver;

    public function __construct(PDO $pdo)
    {
        $this->pdo    = $pdo;
        $this->driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        $this->ensureMigrationsTable();
    }

    private function ensureMigrationsTable(): void
    {
        if ($this->driver === 'sqlite') {
            $this->pdo->exec(
                'CREATE TABLE IF NOT EXISTS migrations (
                    id          INTEGER PRIMARY KEY AUTOINCREMENT,
                    version     VARCHAR(20)  NOT NULL UNIQUE,
                    description VARCHAR(255) NOT NULL,
                    executed_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
                )'
            );
        } else {
            $this->pdo->exec(
                'CREATE TABLE IF NOT EXISTS migrations (
                    id          INT PRIMARY KEY AUTO_INCREMENT,
                    version     VARCHAR(20)  NOT NULL UNIQUE,
                    description VARCHAR(255) NOT NULL,
                    executed_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
            );
        }
    }


    private function getAppliedVersions(): array
    {
        $versions = $this->pdo
            ->query('SELECT version FROM migrations ORDER BY version ASC')
            ->fetchAll(PDO::FETCH_COLUMN);

        return $versions;
    }

    private function loadMigrations(): array
    {
        $migrations = [];
        $files      = glob(__DIR__ . '/versions/V*.php') ?: [];

        foreach ($files as $file) {
            require_once $file;

            $className = pathinfo($file, PATHINFO_FILENAME);

            if (class_exists($className)) {
                /** @var Migration $migration */
                $migration    = new $className();
                $migrations[] = $migration;
            }
        }

        usort($migrations, fn (Migration $a, Migration $b) => strcmp($a->getVersion(), $b->getVersion()));

        return $migrations;
    }

    public function run(): array
    {
        $applied = $this->getAppliedVersions();
        $results = [];

        foreach ($this->loadMigrations() as $migration) {
            $version = $migration->getVersion();

            if (in_array($version, $applied, true)) {
                $results[] = [
                    'version'     => $version,
                    'description' => $migration->getDescription(),
                    'status'      => 'skipped',
                ];
                continue;
            }

            try {
                $this->pdo->beginTransaction();
                $migration->up($this->pdo);

                $stmt = $this->pdo->prepare(
                    'INSERT INTO migrations (version, description) VALUES (:version, :description)'
                );
                $stmt->execute([
                    ':version'     => $version,
                    ':description' => $migration->getDescription(),
                ]);

                $this->pdo->commit();

                $results[] = [
                    'version'     => $version,
                    'description' => $migration->getDescription(),
                    'status'      => 'applied',
                ];
            } catch (PDOException $e) {
                $this->pdo->rollBack();
                error_log('Migration ' . $version . ' échouée : ' . $e->getMessage());

                $results[] = [
                    'version'     => $version,
                    'description' => $migration->getDescription(),
                    'status'      => 'failed: ' . $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    public function status(): array
    {
        $applied = [];
        $rows    = $this->pdo
            ->query('SELECT version, executed_at FROM migrations')
            ->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $applied[$row['version']] = $row['executed_at'];
        }

        $status = [];
        foreach ($this->loadMigrations() as $migration) {
            $version  = $migration->getVersion();
            $status[] = [
                'version'     => $version,
                'description' => $migration->getDescription(),
                'applied'     => isset($applied[$version]),
                'executed_at' => $applied[$version] ?? null,
            ];
        }

        return $status;
    }
}
