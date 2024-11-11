<?php
session_start();
$total_price = 0;

if (!empty($_SESSION['cart'])){
    $cart = $_SESSION['cart'];
    foreach ($cart as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Корзина</h1>
    <div id="cart-items">
        <?php if (!empty($_SESSION['cart'])): ?>
            <table>
                <tr>
                    <th>Товар</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Всего</th>
                </tr>
                <?php foreach ($cart as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['title']); ?></td>
                        <td><?php echo htmlspecialchars($item['price']); ?> евро</td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo $item['price'] * $item['quantity']; ?> евро</td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Корзина пуста.</p>
        <?php endif; ?>
    </div>
    <p>Общая стоимость: <?php echo $total_price; ?> евро</p>
    <button onclick="window.location.href='checkout.php'">Оформить заказ</button>
    <button id="clear-cart-btn" class="btn btn-danger">Очистить корзину</button>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#clear-cart-btn').click(function() {
        $.post('clear_cart.php', function(response) {
            var data = JSON.parse(response);

            if (data.status === 'success') {
                alert('Корзина успешно очищена.');
                $total_price = 0;
                $('#cart-items').html('<p>Корзина пуста.</p>');
                $('#cart-count').text('0');
            } else {
                alert('Ошибка при очистке корзины.');
            }
        });
    });
</script>
</body>
</html>