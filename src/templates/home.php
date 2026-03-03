<?php
/**
 * @var array<int, array{id: int, text: string}> $rows
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>R6.06 Maintenance applicative</title>
    <link rel="stylesheet" href="/src/style.css">
</head>
<body>
    <header>
        <h1>R6.06 Maintenance applicative</h1>
        <h2 class="highlight">Evaluation</h2>
    </header>

    <main>
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Text</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) $row['id'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) $row['text'], ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>