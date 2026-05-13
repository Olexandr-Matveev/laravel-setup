<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: form.php?redirected=1');
    exit;
}

$serverData = [
    'IP-адреса клієнта' => $_SERVER['REMOTE_ADDR'] ?? 'Немає даних',
    'Назва та версія браузера' => $_SERVER['HTTP_USER_AGENT'] ?? 'Немає даних',
    'Назва скрипта, що виконується' => $_SERVER['PHP_SELF'] ?? 'Немає даних',
    'Метод запиту' => $_SERVER['REQUEST_METHOD'] ?? 'Немає даних',
    'Шлях до файлу на сервері' => $_SERVER['SCRIPT_FILENAME'] ?? 'Немає даних',
    'Коренева директорія документа' => $_SERVER['DOCUMENT_ROOT'] ?? 'Немає даних',
];
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Пункт 3 - SERVER</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<nav><a href="../index.php">← На головну</a> <a href="form.php">Форма POST</a></nav>
<div class="card">
    <h1>3. Дані з $_SERVER</h1>
    <table>
        <tr><th>Параметр</th><th>Значення</th></tr>
        <?php foreach ($serverData as $key => $value): ?>
            <tr>
                <td><?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
