<?php
session_start();

$timeout = 5 * 60;
$message = '';

if (isset($_POST['simulate_inactivity'])) {
    $_SESSION['last_activity'] = time() - ($timeout + 10);
}

if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > $timeout) {
    $_SESSION = [];
    session_destroy();
    session_start();
    $message = 'Сесію автоматично завершено через неактивність більше 5 хвилин.';
}

$_SESSION['last_activity'] = time();
$_SESSION['activity_counter'] = ($_SESSION['activity_counter'] ?? 0) + 1;
$lastActivity = date('H:i:s', $_SESSION['last_activity']);
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Пункт 5 - Session activity</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<nav><a href="../index.php">← На головну</a></nav>
<div class="card">
    <h1>5. Час активності сесії</h1>
    <?php if ($message): ?><p class="error"><?= $message ?></p><?php endif; ?>
    <p>При кожному завантаженні сторінки перевіряється <code>$_SESSION['last_activity']</code>.</p>
    <p>Тайм-аут: <strong>5 хвилин</strong>.</p>
    <p>Остання активність: <strong><?= $lastActivity ?></strong></p>
    <p>Кількість активних завантажень у поточній сесії: <strong><?= (int)$_SESSION['activity_counter'] ?></strong></p>
    <form method="post">
        <button type="submit">Оновити активність</button>
        <button class="danger" type="submit" name="simulate_inactivity" value="1">Симулювати неактивність понад 5 хвилин</button>
    </form>
</div>
</body>
</html>
