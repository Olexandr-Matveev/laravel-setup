<?php
$redirected = isset($_GET['redirected']);
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Пункт 3 - SERVER form</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<nav><a href="../index.php">← На головну</a></nav>
<div class="card">
    <h1>3. Робота з $_SERVER</h1>
    <?php if ($redirected): ?>
        <p class="success">Виконано автоматичне перенаправлення, бо сторінку інформації відкрили не POST-запитом.</p>
    <?php endif; ?>
    <p>Натисніть кнопку, щоб відправити POST-запит і побачити дані з масиву <code>$_SERVER</code>.</p>
    <form method="post" action="index.php">
        <input type="hidden" name="check" value="1">
        <button type="submit">Показати інформацію про сервер і запит</button>
    </form>
    <p>Для перевірки редиректу відкрийте напряму: <a href="index.php">index.php GET-запитом</a>.</p>
</div>
</body>
</html>
