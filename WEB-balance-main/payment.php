<?php
session_start();
require_once("conf.php"); // Подключение к базе данных
global $connection;

if (!isset($_SESSION['user_id'])) {
    echo "Пользователь не авторизован.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Получение текущего баланса из базы данных
$query = $connection->prepare("SELECT balance FROM agapov WHERE id = ?");
$query->bind_param('i', $user_id);
$query->execute();
$query->bind_result($current_balance);
$query->fetch();
$query->close();

// Расчет общей стоимости корзины
$total_price = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }
} else {
    echo "Корзина пуста.";
    exit;
}

// Проверка наличия достаточного баланса и обновление в базе данных
if ($current_balance >= $total_price) {
    $new_balance = $current_balance - $total_price;

    // Обновление баланса пользователя в базе данных
    $update_balance_query = $connection->prepare("UPDATE agapov SET balance = ? WHERE id = ?");
    $update_balance_query->bind_param('di', $new_balance, $user_id);
    $update_balance_query->execute();
    $update_balance_query->close();

    // Запись каждой позиции корзины в таблицу purchases
    $insert_purchase_query = $connection->prepare("INSERT INTO purchases (user_id, product_name, quantity, price, purchase_date) VALUES (?, ?, ?, ?, NOW())");

    foreach ($_SESSION['cart'] as $item) {
        $product_name = $item['description'];
        $quantity = $item['quantity'];
        $price = $item['price'];
        $insert_purchase_query->bind_param('isid', $user_id, $product_name, $quantity, $price);
        $insert_purchase_query->execute();
    }
    $insert_purchase_query->close();

    // Очищение корзины
    unset($_SESSION['cart']);

    $message = "Заказ успешно оплачен. Текущий баланс: " . number_format($new_balance, 2) . " евро.";
    $_SESSION['balance'] = $new_balance; // Обновление баланса в сессии
} else {
    $message = "Недостаточно средств для оплаты. Текущий баланс: " . number_format($current_balance, 2) . " евро.";
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Оплата заказа</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('images/background.png') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: #fff;
        }

        .message-container {
            width: 100%;
            max-width: 800px;
            margin: 50px auto;
            background-color: rgba(0, 0, 0, 0.6);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
        }

        .message-container h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .message-container .message {
            font-size: 1.5rem;
            margin-bottom: 30px;
        }

        .message-container a {
            font-size: 1.2rem;
            color: #fff;
            text-decoration: none;
            background-color: #000;
            padding: 10px 20px;
            border-radius: 10px;
            margin: 5px;
        }

        .message-container a:hover {
            background-color: #333;
        }

        .success {
            color: #28a745;
        }

        .error {
            color: #dc3545;
        }
    </style>
</head>
<body>
<div class="message-container">
    <h1>Статус оплаты</h1>
    <p class="message <?php echo $current_balance >= $total_price ? 'success' : 'error'; ?>">
        <?php echo $message; ?>
    </p>
    <div>
        <a href="balance.php">Пополнить баланс</a>
        <a href="index.php">Вернуться на главную страницу</a>
    </div>
</div>
</body>
</html>
