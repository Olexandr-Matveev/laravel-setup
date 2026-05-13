<?php
session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/helpers.php';

$errors = [];
$success = '';
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    if ($username === '' || strlen($username) < 3 || strlen($username) > 50) {
        $errors[] = 'Ім’я користувача повинно містити від 3 до 50 символів.';
    }

    if ($username !== '' && !preg_match('/^[A-Za-z0-9_]+$/', $username)) {
        $errors[] = 'Ім’я користувача може містити тільки латинські літери, цифри та символ підкреслення.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
        $errors[] = 'Введіть коректну електронну пошту довжиною до 100 символів.';
    }

    if (strlen($password) < 6) {
        $errors[] = 'Пароль повинен містити щонайменше 6 символів.';
    }

    if ($password !== $passwordConfirm) {
        $errors[] = 'Пароль і підтвердження пароля не збігаються.';
    }

    if (!$errors) {
        try {
            $connection = getDbConnection();

            $checkStatement = $connection->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
            $checkStatement->bind_param('ss', $username, $email);
            $checkStatement->execute();
            $result = $checkStatement->get_result();

            if ($result->num_rows > 0) {
                $errors[] = 'Користувач із таким логіном або email уже існує.';
            } else {
                $passwordHash = createPasswordHash($password);
                $insertStatement = $connection->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
                $insertStatement->bind_param('sss', $username, $email, $passwordHash);
                $insertStatement->execute();

                $success = 'Реєстрацію виконано успішно. Тепер можна увійти.';
                $username = '';
                $email = '';
            }
        } catch (Throwable $exception) {
            $errors[] = 'Помилка реєстрації: ' . $exception->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Реєстрація</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="container narrow">
    <h1>Реєстрація користувача</h1>
    <p><a href="index.php">← На головну</a></p>

    <?php foreach ($errors as $error): ?>
        <div class="alert error"><?= e($error) ?></div>
    <?php endforeach; ?>

    <?php if ($success): ?>
        <div class="alert success"><?= e($success) ?></div>
        <p><a class="button" href="login.php">Перейти до входу</a></p>
    <?php endif; ?>

    <form method="post" action="register.php" class="card form-card" autocomplete="off">
        <label for="username">Ім’я користувача</label>
        <input type="text" id="username" name="username" value="<?= e($username) ?>" required minlength="3" maxlength="50" pattern="[A-Za-z0-9_]+">

        <label for="email">Електронна пошта</label>
        <input type="email" id="email" name="email" value="<?= e($email) ?>" required maxlength="100">

        <label for="password">Пароль</label>
        <input type="password" id="password" name="password" required minlength="6">

        <label for="password_confirm">Підтвердження пароля</label>
        <input type="password" id="password_confirm" name="password_confirm" required minlength="6">

        <button type="submit">Зареєструватися</button>
    </form>
</main>
</body>
</html>
