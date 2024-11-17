<?php
session_start();
$total_price = 0;

if (!empty($_SESSION['cart'])) {
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
    <style>
        body {
            background: url('images/background.png') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: #fff;
        }

        .profile-container {
            width: 100%;
            max-width: 1200px;
            margin: 50px auto;
            background-color: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 20px;
            text-align: center;
        }

        .profile-header {
            font-size: 2rem;
            margin-bottom: 30px;
        }

        .balance-display {
            font-size: 4rem;
            font-weight: bold;
            margin: 40px 0;
        }

        .balance-display span {
            display: block;
            font-size: 1.5rem;
            color: #ccc;
        }

        .btn-custom1 {
            background-color: #fff;
            color: #000;
        }

        .btn-custom2 {
            background-color: #777;
            color: #fff;
        }

        .btn-custom1:hover, .btn-custom2:hover {
            background-color: black;
            color: white;
            cursor: pointer;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 25px;
            margin: 20px auto auto auto;
            width: 1200px;
            height: 100px;
        }

        .header h1 {
            font-size: 3rem;
        }

        .header .buttons button {
            border-radius: 20px;
            border: none;
            padding: 10px 20px;
            font-size: 1.2rem;
            margin: 5px;
        }

        .profile-container .buttons button {
            border-radius: 20px;
            border: none;
            padding: 10px 20px;
            font-size: 1.2rem;
            margin: 5px;
        }

        table {
            width: 100%;
            color: #fff;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #444;
        }

        td {
            background-color: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>
<div class="profile-container">
    <h1 class="profile-header">Корзина</h1>

    <div id="cart-items">
        <?php if (!empty($_SESSION['cart'])): ?>
            <table id="cart-table">
                <tr>
                    <th>Товар</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Всего</th>
                    <th>Действие</th>
                </tr>
                <?php foreach ($cart as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['description']); ?></td>
                        <td><?php echo htmlspecialchars($item['price']); ?> евро</td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo $item['price'] * $item['quantity']; ?> евро</td>
                        <td>
                            <button class="btn btn-warning decrement-item-btn" data-product-id="<?php echo $item['product_id']; ?>">Удалить</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Корзина пуста.</p>
        <?php endif; ?>
    </div>

    <p class="balance-display">Общая стоимость: <span><?php echo $total_price; ?> евро</span></p>

    <div class="buttons">
        <button onclick="window.location.href='checkout.php'" class="btn btn-custom1">Оформить заказ</button>
        <button id="clear-cart-btn" class="btn btn-danger">Очистить корзину</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#clear-cart-btn').click(function() {
        $.post('clear_cart.php', function(response) {
            var data = JSON.parse(response);

            if (data.status === 'success') {
                alert('Корзина успешно очищена.');
                $('#cart-items').html('<p>Корзина пуста.</p>');
                $('p:contains("Общая стоимость")').text('Общая стоимость: 0 евро');
            } else {
                alert('Ошибка при очистке корзины.');
            }
        });
    });

    $(document).on('click', '.decrement-item-btn', function() {
        var productId = $(this).data('product-id');
        console.log('Deleting product with ID:', productId); // Для отладки

        $.post('remove_from_cart.php', { product_id: productId }, function(response) {
            var data = JSON.parse(response);

            if (data.status === 'success') {
                alert('Один товар успешно удален.');

                if (data.cart_count === 0) {
                    $('#cart-items').html('<p>Корзина пуста.</p>');
                    $('p:contains("Общая стоимость")').text('Общая стоимость: 0 евро');
                } else {
                    $('#cart-items').html(data.cart_html);
                    $('p:contains("Общая стоимость")').text('Общая стоимость: ' + data.total_price + ' евро');
                }
            } else {
                alert('Ошибка при удалении товара из корзины.');
            }
        });
    });

</script>
</body>
</html>
