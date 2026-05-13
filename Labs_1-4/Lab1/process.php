<?php
// process.php — обробка даних, отриманих з HTML-форми.

// Функція очищає введені користувачем дані від зайвих пробілів і HTML-символів.
function cleanInput(string $value): string
{
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

$errors = [];
$firstName = '';
$lastName = '';

// Перевіряємо, чи форма була відправлена методом POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = cleanInput($_POST['first_name'] ?? '');
    $lastName = cleanInput($_POST['last_name'] ?? '');

    // Перевірка на пусті значення.
    if ($firstName === '') {
        $errors[] = "Поле 'Ім'я' не може бути порожнім.";
    }

    if ($lastName === '') {
        $errors[] = "Поле 'Прізвище' не може бути порожнім.";
    }

    // Перевірка типу даних: після отримання з форми значення мають бути рядками.
    if (!is_string($firstName) || !is_string($lastName)) {
        $errors[] = "Некоректний тип даних.";
    }
} else {
    $errors[] = "Дані форми не були надіслані методом POST.";
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Результат обробки форми</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 40px auto; padding: 0 15px; }
        .success { color: #047857; font-weight: bold; }
        .error { color: #b91c1c; }
    </style>
</head>
<body>
<h1>Результат обробки форми</h1>

<?php if (empty($errors)): ?>
    <p class="success">Вітаємо, <?= $firstName ?> <?= $lastName ?>!</p>
    <p>Дані були отримані та перевірені коректно.</p>
<?php else: ?>
    <h2 class="error">Виявлено помилки:</h2>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li class="error"><?= $error ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<p><a href="index.html">Повернутися до форми</a></p>
<p><a href="lab1.php">Перейти до пунктів 1-6</a></p>
</body>
</html>
