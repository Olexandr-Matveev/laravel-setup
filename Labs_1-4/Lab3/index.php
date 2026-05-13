<?php
require_once __DIR__ . '/classes/BankAccount.php';
require_once __DIR__ . '/classes/SavingsAccount.php';

$operationResults = [];

function addResult(array &$results, string $title, callable $operation): void
{
    try {
        $message = $operation();
        $results[] = [
            'status' => 'success',
            'title' => $title,
            'message' => $message,
        ];
    } catch (Exception $exception) {
        $results[] = [
            'status' => 'error',
            'title' => $title,
            'message' => $exception->getMessage(),
        ];
    }
}

try {
    $mainAccount = new BankAccount(1000, 'USD');
    $savingsAccount = new SavingsAccount(2500, 'EUR');
} catch (Exception $exception) {
    die('Помилка створення рахунків: ' . htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8'));
}

addResult($operationResults, 'Поповнення базового рахунку на 500 USD', function () use ($mainAccount) {
    $mainAccount->deposit(500);
    return 'Операція виконана. Поточний баланс: ' . $mainAccount->getFormattedBalance();
});

addResult($operationResults, 'Зняття 300 USD з базового рахунку', function () use ($mainAccount) {
    $mainAccount->withdraw(300);
    return 'Операція виконана. Поточний баланс: ' . $mainAccount->getFormattedBalance();
});

addResult($operationResults, 'Спроба зняти 9999 USD', function () use ($mainAccount) {
    $mainAccount->withdraw(9999);
    return 'Цей текст не повинен з’явитися, бо має бути виняток.';
});

addResult($operationResults, 'Спроба поповнити рахунок на -50 USD', function () use ($mainAccount) {
    $mainAccount->deposit(-50);
    return 'Цей текст не повинен з’явитися, бо має бути виняток.';
});

addResult($operationResults, 'Застосування відсоткової ставки до накопичувального рахунку', function () use ($savingsAccount) {
    $savingsAccount->applyInterest();
    return 'Ставка: ' . SavingsAccount::getInterestRatePercent() . '. Поточний баланс: ' . $savingsAccount->getFormattedBalance();
});

addResult($operationResults, 'Зняття 1000 EUR з накопичувального рахунку', function () use ($savingsAccount) {
    $savingsAccount->withdraw(1000);
    return 'Операція виконана. Поточний баланс: ' . $savingsAccount->getFormattedBalance();
});
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторна робота №3 — ООП PHP</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <div class="card">
        <span class="badge">Лабораторна робота №3</span>
        <h1>Об'єктно-орієнтоване програмування в PHP</h1>
        <p>
            Реалізовано систему класів для роботи з банківськими рахунками:
            інтерфейс <code>AccountInterface</code>, базовий клас <code>BankAccount</code>,
            дочірній клас <code>SavingsAccount</code>, константа <code>MIN_BALANCE</code>,
            статична властивість <code>interestRate</code> та обробка винятків через <code>try/catch</code>.
        </p>
    </div>

    <div class="grid">
        <div class="card">
            <h2>BankAccount</h2>
            <ul>
                <li>Валюта: <code><?= htmlspecialchars($mainAccount->getCurrency(), ENT_QUOTES, 'UTF-8') ?></code></li>
                <li>Мінімальний баланс: <code><?= BankAccount::MIN_BALANCE ?></code></li>
                <li>Поточний баланс: <strong><?= htmlspecialchars($mainAccount->getFormattedBalance(), ENT_QUOTES, 'UTF-8') ?></strong></li>
            </ul>
        </div>

        <div class="card">
            <h2>SavingsAccount</h2>
            <ul>
                <li>Валюта: <code><?= htmlspecialchars($savingsAccount->getCurrency(), ENT_QUOTES, 'UTF-8') ?></code></li>
                <li>Статична ставка: <code><?= SavingsAccount::getInterestRatePercent() ?></code></li>
                <li>Поточний баланс: <strong><?= htmlspecialchars($savingsAccount->getFormattedBalance(), ENT_QUOTES, 'UTF-8') ?></strong></li>
            </ul>
        </div>
    </div>

    <div class="card">
        <h2>Клієнтський код: результати тестування операцій</h2>
        <table>
            <thead>
            <tr>
                <th>Операція</th>
                <th>Статус</th>
                <th>Результат</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($operationResults as $result): ?>
                <tr>
                    <td><?= htmlspecialchars($result['title'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="<?= $result['status'] === 'success' ? 'success' : 'error' ?>">
                        <?= $result['status'] === 'success' ? 'Успішно' : 'Виняток оброблено' ?>
                    </td>
                    <td><?= htmlspecialchars($result['message'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h2>Що показати викладачу</h2>
        <ul>
            <li><code>AccountInterface</code> задає методи <code>deposit()</code>, <code>withdraw()</code>, <code>getBalance()</code>.</li>
            <li><code>BankAccount</code> реалізує інтерфейс і містить константу <code>MIN_BALANCE</code>.</li>
            <li><code>SavingsAccount</code> успадковує <code>BankAccount</code> і додає статичну ставку та метод <code>applyInterest()</code>.</li>
            <li>Некоректні операції не ламають сторінку, а обробляються через <code>Exception</code>.</li>
            <li>На сторінці є клієнтський код — приклад роботи з класами та методами.</li>
        </ul>
    </div>
</div>
</body>
</html>
