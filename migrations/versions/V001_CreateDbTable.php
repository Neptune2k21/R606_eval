<?php

declare(strict_types=1);

class V001_CreateDbTable implements Migration
{
    public function getVersion(): string
    {
        return 'V001';
    }

    public function getDescription(): string
    {
        return 'Création de la table db_table avec données initiales';
    }

    public function up(PDO $pdo): void
    {
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS db_table (
                id   INT PRIMARY KEY AUTO_INCREMENT,
                text VARCHAR(100) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );

        $count = (int) $pdo->query('SELECT COUNT(*) FROM db_table')->fetchColumn();

        if ($count === 0) {
            $stmt        = $pdo->prepare('INSERT INTO db_table (text) VALUES (:text)');
            $defaultData = ['Le Boss Mamad', 'Je suis beau', 'Heheh', '123456789'];

            foreach ($defaultData as $text) {
                $stmt->execute([':text' => $text]);
            }
        }
    }
}
