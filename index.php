<?php

declare(strict_types=1);

require_once __DIR__ . '/src/Database.php';

try {
    $rows = Database::getOrCreateData();
} catch (RuntimeException $e) {
    echo '<p>Erreur : ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
    exit(1);
}

require __DIR__ . '/src/templates/home.php';