<?php

declare(strict_types=1);

$host     = $_ENV['MYSQL_HOST'] ?? 'db';
$dbname   = $_ENV['MYSQL_DATABASE'] ?? 'ma_bdd';
$username = $_ENV['MYSQL_USER'] ?? 'user';
$password = $_ENV['MYSQL_PASSWORD'] ?? 'password';
$charset  = 'utf8mb4';

$dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
