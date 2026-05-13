<?php
session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/helpers.php';

if (!empty($_SESSION['user_id'])) {
    redirect('welcome.php');
}

$errors = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '') {
        $errors[] = 'Введіть ім’я користувача.';
    }

    if ($password === '') {
        $errors[] = 'Введіть пароль.';
    }

    if (!$errors) {
        try {
            $connection = getDbConnection();
            $statement = $connection->prepare('SELECT id, username, email, password FROM users WHERE username = ? LIMIT 1');
            $statement->bind_param('s', $username);
            $statement->execute();
            $result = $statement->get_result();
            $user = $result->fetch_assoc();

            if (!$user || !verifyPasswordForLab($password, $user['password'])) {
                $errors[] = 'Невірний логін або пароль.';
            } else {
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int)$user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                redirect('welcome.php');
            }
        } catch (Throwable $exception) {
            $errors[] = 'Помилка авторизації: ' . $exception->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизація</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="container narrow">
    <h1>Авторизація</h1>
    <p><a href="index.php">← На головну</a></p>

    <?php foreach ($errors as $error): ?>
        <div class="alert error"><?= e($error) ?></div>
    <?php endforeach; ?>

    <form method="post" action="login.php" class="card form-card" autocomplete="off">
        <label for="username">Ім’я користувача</label>
        <input type="text" id="username" name="username" value="<?= e($username) ?>" required>

        <label for="password">Пароль</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Увійти</button>
    </form>
</main>
</body>
</html>
