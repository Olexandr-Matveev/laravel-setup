<?php
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function requireAuth(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (empty($_SESSION['user_id'])) {
        redirect('login.php');
    }
}

function isMysqliAvailable(): bool
{
    return extension_loaded('mysqli') && class_exists('mysqli') && function_exists('mysqli_report');
}

function renderEnvironmentError(string $title, string $details): never
{
    http_response_code(500);
    echo '<!doctype html><html lang="uk"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>' . e($title) . '</title><link rel="stylesheet" href="assets/style.css"></head><body><main class="container">';
    echo '<section class="card"><h1>' . e($title) . '</h1><div class="alert error">' . e($details) . '</div>';
    echo '<p>Щоб виправити: відкрийте <code>C:\\php\\php.ini</code>, увімкніть рядки <code>extension=mysqli</code> і <code>extension=pdo_mysql</code>, перевірте <code>extension_dir = "C:\\php\\ext"</code>, збережіть файл і перезапустіть PHP-сервер.</p>';
    echo '<p><a href="index.php">На головну</a></p></section></main></body></html>';
    exit;
}

function createPasswordHash(string $password): string
{
    // За формулюванням лабораторної роботи пароль потрібно хешувати через md5().
    // Для реальних систем треба використовувати password_hash($password, PASSWORD_DEFAULT).
    return md5($password);
}

function verifyPasswordForLab(string $password, string $storedHash): bool
{
    // За вимогою лабораторної пароль зберігається через md5().
    // Додатково залишено підтримку password_hash(), якщо такий хеш колись буде в базі.
    if (hash_equals($storedHash, md5($password))) {
        return true;
    }

    $info = password_get_info($storedHash);
    if (!empty($info['algo'])) {
        return password_verify($password, $storedHash);
    }

    return false;
}
