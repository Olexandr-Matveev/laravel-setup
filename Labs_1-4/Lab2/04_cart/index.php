<?php
session_start();

$timeout = 5 * 60;
if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > $timeout) {
    $_SESSION = [];
    session_destroy();
    session_start();
    $sessionMessage = 'Попередню сесію завершено через неактивність понад 5 хвилин.';
} else {
    $sessionMessage = '';
}
$_SESSION['last_activity'] = time();

$products = [
    'book' => ['name' => 'Книга з PHP', 'price' => 350],
    'mouse' => ['name' => 'Комп’ютерна миша', 'price' => 420],
    'keyboard' => ['name' => 'Клавіатура', 'price' => 780],
    'flash' => ['name' => 'USB Flash 64 GB', 'price' => 260],
];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        $productId = $_POST['product_id'] ?? '';
        if (isset($products[$productId])) {
            $_SESSION['cart'][] = $products[$productId]['name'];
            $message = 'Товар додано до корзини.';
        }
    }
    if (isset($_POST['clear_cart'])) {
        $_SESSION['cart'] = [];
        $message = 'Корзину очищено.';
    }
    if (isset($_POST['save_previous'])) {
        $previous = $_SESSION['cart'] ?? [];
        setcookie('previous_purchases', json_encode($previous, JSON_UNESCAPED_UNICODE), time() + 30 * 24 * 60 * 60, '/');
        $_COOKIE['previous_purchases'] = json_encode($previous, JSON_UNESCAPED_UNICODE);
        $_SESSION['cart'] = [];
        $message = 'Покупки збережено в cookie як попередні покупки.';
    }
}

$previousPurchases = [];
if (!empty($_COOKIE['previous_purchases'])) {
    $decoded = json_decode($_COOKIE['previous_purchases'], true);
    if (is_array($decoded)) {
        $previousPurchases = $decoded;
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Пункт 4 - Cart</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<nav><a href="../index.php">← На головну</a></nav>
<div class="card">
    <h1>4. Корзина покупок: $_SESSION + $_COOKIE</h1>
    <?php if ($sessionMessage): ?><p class="error"><?= $sessionMessage ?></p><?php endif; ?>
    <?php if ($message): ?><p class="success"><?= $message ?></p><?php endif; ?>

    <h2>Каталог товарів</h2>
    <form method="post">
        <select name="product_id">
            <?php foreach ($products as $id => $product): ?>
                <option value="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?> — <?= $product['price'] ?> грн
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="add_product" value="1">Додати в корзину</button>
    </form>

    <h2>Поточна корзина у сесії</h2>
    <?php if (!empty($_SESSION['cart'])): ?>
        <ul>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <li><?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Корзина порожня.</p>
    <?php endif; ?>

    <form method="post">
        <button type="submit" name="save_previous" value="1">Оформити і зберегти в cookie</button>
        <button class="danger" type="submit" name="clear_cart" value="1">Очистити корзину</button>
    </form>

    <h2>Попередні покупки з cookie</h2>
    <?php if ($previousPurchases): ?>
        <ul>
            <?php foreach ($previousPurchases as $item): ?>
                <li><?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Попередніх покупок у cookie ще немає.</p>
    <?php endif; ?>
</div>
</body>
</html>
