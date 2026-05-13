<?php
require_once __DIR__ . '/config/helpers.php';
requireAuth();
?>
<!doctype html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Захищена сторінка</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="container narrow">
    <section class="card">
        <h1>Welcome.php</h1>
        <div class="alert success">Авторизацію виконано успішно.</div>
        <p>Вітаємо, <strong><?= e($_SESSION['username']) ?></strong>!</p>
        <p>Email користувача: <code><?= e($_SESSION['email']) ?></code></p>
        <p>ID користувача в сесії: <code><?= e((string)$_SESSION['user_id']) ?></code></p>
        <p>Ця сторінка захищена: якщо сесії немає, користувач автоматично перенаправляється на <code>login.php</code>.</p>
        <p><a class="button" href="logout.php">Вийти</a></p>
    </section>
</main>
</body>
</html>
