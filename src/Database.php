<?php

declare(strict_types=1);

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            require_once __DIR__ . '/../config.php';

            try {
                self::$instance = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                error_log('Erreur de connexion BDD : ' . $e->getMessage());
                throw new RuntimeException('Impossible de se connecter à la base de données.');
            }
        }

        return self::$instance;
    }

    public static function getOrCreateData(): array
    {
        $pdo = self::getConnection();

        try {
            $rows = $pdo->query('SELECT id, text FROM db_table')->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $pdo->exec(
                'CREATE TABLE IF NOT EXISTS db_table (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    text VARCHAR(100) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
            );

            $stmt = $pdo->prepare('INSERT INTO db_table (text) VALUES (:text)');
            $defaultData = ['Le Boss Mamad', 'Je suis beau', 'Heheh', '123456789'];

            foreach ($defaultData as $text) {
                $stmt->execute([':text' => $text]);
            }

            $rows = $pdo->query('SELECT id, text FROM db_table')->fetchAll(PDO::FETCH_ASSOC);
        }

        return $rows;
    }
}
