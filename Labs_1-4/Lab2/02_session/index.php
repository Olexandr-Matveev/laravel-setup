<?php
session_start();

$validLogin = 'student';
$validPassword = 'S1357R';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($login === $validLogin && $password === $validPassword) {
        $_SESSION['user'] = $login;
        $_SESSION['last_activity'] = time();
        header('Location: index.php');
        exit;
    }
    $error = 'Невірний логін або пароль.';
}
$isLoggedIn = isset($_SESSION['user']);
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Пункт 2 - SESSION</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<nav><a href="../index.php">← На головну</a></nav>
<div class="card">
    <h1>2. Робота з $_SESSION</h1>
    <?php if ($isLoggedIn): ?>
        <p class="success">Вітаємо, <?= htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8') ?>! Активна сесія знайдена.</p>
        <p>Дані користувача збережено в <code>$_SESSION['user']</code>.</p>
        <a class="button danger" href="logout.php">Вихід</a>
    <?php else: ?>
        <p>Введіть логін і пароль для створення сесії.</p>
        <p><strong>Тестові дані:</strong> login: <code>student</code>, password: <code>S1357R</code></p>
        <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
        <form method="post">
            <label for="login">Логін:</label><br>
            <input type="text" id="login" name="login" required><br>
            <label for="password">Пароль:</label><br>
            <input type="password" id="password" name="password" required><br>
            <button type="submit">Увійти</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
