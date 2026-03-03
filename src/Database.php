<?php

declare(strict_types=1);

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            require_once __DIR__ . '/../config.php';

            /** @var string $dsn */
            /** @var string $username */
            /** @var string $password */
            /** @var array<int, mixed> $options */

            try {
                self::$instance = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                error_log('Erreur de connexion BDD : ' . $e->getMessage());
                throw new RuntimeException('Impossible de se connecter à la base de données.');
            }
        }

        return self::$instance;
    }

    /**
     * @return array<int, array{id: int, text: string}>
     */
    public static function getData(): array
    {
        $pdo = self::getConnection();

        /** @var array<int, array{id: int, text: string}> $rows */
        $rows = $pdo->query('SELECT id, text FROM db_table')->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
    }
}
