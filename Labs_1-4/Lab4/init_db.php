<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/helpers.php';

$message = '';
$error = '';

try {
    $connection = getServerConnection();
    $sql = file_get_contents(__DIR__ . '/dump.sql');

    if ($sql === false) {
        throw new RuntimeException('Не вдалося прочитати файл dump.sql.');
    }

    if (!$connection->multi_query($sql)) {
        throw new RuntimeException('Не вдалося виконати dump.sql.');
    }

    do {
        if ($result = $connection->store_result()) {
            $result->free();
        }
    } while ($connection->more_results() && $connection->next_result());

    $connection->close();
    $message = 'База даних users_db і таблиця users успішно створені або вже існували.';
} catch (Throwable $exception) {
    $error = $exception->getMessage();
}
?>
<!doctype html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ініціалізація БД</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="container narrow">
    <h1>Ініціалізація бази даних</h1>
    <p><a href="index.php">← На головну</a></p>

    <?php if ($message): ?>
        <div class="alert success"><?= e($message) ?></div>
        <p><a class="button" href="register.php">Перейти до реєстрації</a></p>
    <?php else: ?>
        <div class="alert error">Помилка: <?= e($error) ?></div>
    <?php endif; ?>
</main>
</body>
</html>
