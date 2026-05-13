<?php
$cookieName = 'lab2_user_name';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_cookie'])) {
        setcookie($cookieName, '', time() - 3600, '/');
        unset($_COOKIE[$cookieName]);
        $message = 'Cookie видалено.';
    } else {
        $name = trim($_POST['user_name'] ?? '');
        if ($name === '') {
            $message = 'Помилка: ім’я не може бути порожнім.';
        } else {
            $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
            setcookie($cookieName, $safeName, time() + 7 * 24 * 60 * 60, '/');
            $_COOKIE[$cookieName] = $safeName;
            $message = 'Ім’я збережено в cookie на 7 днів.';
        }
    }
}
$currentName = $_COOKIE[$cookieName] ?? '';
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Пункт 1 - COOKIE</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<nav><a href="../index.php">← На головну</a></nav>
<div class="card">
    <h1>1. Робота з $_COOKIE</h1>
    <p>Форма зберігає ім’я користувача в cookie на 7 днів.</p>
    <?php if ($message): ?><p class="success"><?= $message ?></p><?php endif; ?>
    <?php if ($currentName): ?>
        <p class="success">Вітаємо, <?= $currentName ?>! Ім’я отримано з cookie.</p>
    <?php else: ?>
        <p>Cookie з іменем користувача ще не встановлено.</p>
    <?php endif; ?>
    <form method="post">
        <label for="user_name">Ім’я користувача:</label><br>
        <input type="text" id="user_name" name="user_name" required>
        <button type="submit">Зберегти ім’я</button>
    </form>
    <form method="post">
        <button class="danger" type="submit" name="delete_cookie" value="1">Видалити cookie</button>
    </form>
</div>
</body>
</html>
