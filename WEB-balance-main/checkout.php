<?php
session_start();

// Подсчитываем общую стоимость
$total_price = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }
} else {
    header('Location: index.php'); // Если корзина пуста, перенаправляем на главную страницу
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформление заказа</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Оформление заказа</h1>

    <!-- Список товаров в корзине -->
    <div id="cart-summary">
        <h2>Ваш заказ:</h2>
        <table class="table">
            <tr>
                <th>Товар</th>
                <th>Цена</th>
                <th>Количество</th>
                <th>Всего</th>
            </tr>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['description']); ?></td>
                    <td><?php echo htmlspecialchars($item['price']); ?> евро</td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo $item['price'] * $item['quantity']; ?> евро</td>
                </tr>
            <?php endforeach; ?>
        </table>
        <p><strong>Общая стоимость:</strong> <?php echo $total_price; ?> евро</p>
    </div>

    <!-- Форма ввода данных для оформления заказа -->
    <h2>Введите свои данные</h2>
    <form id="checkout-form" method="post" action="payment.php">
        <div class="mb-3">
            <label for="name" class="form-label">Имя</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Адрес</label>
            <textarea class="form-control" id="address" name="address" required></textarea>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Телефон</label>
            <input type="tel" class="form-control" id="phone" name="phone" required>
        </div>
        <button type="submit" class="btn btn-primary">Подтвердить заказ</button>
    </form>
</div>
</body>
</html>