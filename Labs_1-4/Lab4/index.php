<?php
session_start();
?>
<!doctype html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторна робота №4</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="container">
    <section class="card">
        <h1>Лабораторна робота №4</h1>
        <p><strong>Тема:</strong> створення форми авторизації та реєстрації користувачів на PHP з використанням MySQL.</p>
        <p>Реалізовано базу <code>users_db</code>, таблицю <code>users</code>, реєстрацію, авторизацію, prepared statements, MD5-хешування за вимогою лабораторної, сесію користувача та захищену сторінку <code>welcome.php</code>.</p>
    </section>

    <section class="grid">
        <a class="tile" href="init_db.php">
            <h2>1. Ініціалізувати БД</h2>
            <p>Створити <code>users_db</code> і таблицю <code>users</code> через <code>dump.sql</code>.</p>
        </a>
        <a class="tile" href="register.php">
            <h2>2. Реєстрація</h2>
            <p>Додати користувача в MySQL із хешованим паролем.</p>
        </a>
        <a class="tile" href="login.php">
            <h2>3. Авторизація</h2>
            <p>Перевірити логін і пароль через prepared statement.</p>
        </a>
        <a class="tile" href="welcome.php">
            <h2>4. Захищена сторінка</h2>
            <p>Доступ тільки для користувача з активною сесією.</p>
        </a>
    </section>

    <section class="card">
        <h2>Порядок перевірки</h2>
        <ol>
            <li>Запустіть MySQL Server.</li>
            <li>Запустіть PHP-сервер: <code>C:\php\php.exe -S localhost:8000</code>.</li>
            <li>Відкрийте <code>init_db.php</code>, потім <code>register.php</code>, <code>login.php</code> і <code>welcome.php</code>.</li>
        </ol>
    </section>
</main>
</body>
</html>
