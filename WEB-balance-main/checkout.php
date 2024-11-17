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
    <style>
        body {
            background: url('images/background.png') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Roboto', sans-serif;
            color: #fff;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Анимация плавного перехода для кнопок */
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(50px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Контейнер для контента */
        .container {
            max-width: 1200px;
            margin: 50px auto;
            background: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            animation: fadeIn 1s ease-out;
        }

        /* Заголовки */
        h1, h2 {
            font-size: 2.5rem;
            color: #fff;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .table {
            width: 100%;
            margin-bottom: 30px;
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        th, td {
            padding: 15px;
            text-align: center;
        }

        th {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
            text-transform: uppercase;
            font-weight: bold;
        }

        td {
            background-color: rgba(255, 255, 255, 0.1);
            border-top: 1px solid rgba(255, 255, 255, 0.3);
        }

        .total-price {
            font-size: 2.5rem;
            font-weight: bold;
            color: #ddd;  /* Сделал цвет более нейтральным */
            margin-top: 30px;
        }

        .form-label {
            font-size: 1.3rem;
            font-weight: bold;
            color: #fff;
            text-align: left;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 1rem; /* Сделал чуть меньше */
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: border 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 10px rgba(40, 167, 69, 0.5);
        }

        /* Кнопки */
        .btn-custom1 {
            background-color: #444; /* Сделал кнопку темнее */
            color: #fff;
            border-radius: 30px;
            padding: 15px 30px;
            font-size: 1.4rem;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin-top: 20px;
        }

        .btn-custom1:hover {
            background-color: #333; /* Темнее при наведении */
            transform: scale(1.05);
        }

        .checkout-summary {
            margin-top: 40px;
            text-align: center;
        }

        .checkout-summary button {
            background-color: #28a745;
            color: #fff;
            font-size: 1.5rem;
            padding: 12px 40px;
            border-radius: 50px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .checkout-summary button:hover {
            background-color: #218838;
            transform: scale(1.1);
        }

        /* Плавные тени */
        .table, .btn-custom1, .form-control {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
        }
    </style>
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
        <p class="total-price"><strong>Общая стоимость:</strong> <?php echo $total_price; ?> евро</p>
    </div>

    <!-- Форма ввода данных для оформления заказа -->
    <div class="checkout-summary">
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
            <button type="submit" class="btn-custom1">Подтвердить заказ</button>
        </form>
    </div>
</div>
</body>
</html>
