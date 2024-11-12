<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $name = htmlspecialchars($_POST['name']);
    $address = htmlspecialchars($_POST['address']);
    $phone = htmlspecialchars($_POST['phone']);

    // Проверка, есть ли товары в корзине
    if (!empty($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        $total_price = 0;
        foreach ($cart as $item) {
            $total_price += $item['price'] * $item['quantity'];
        }

        // Например, сохранение информации о заказе (может быть в базу данных или отправка на email)
        // Здесь для примера просто создаем строку с деталями
        $orderDetails = "Заказ от: $name\nАдрес: $address\nТелефон: $phone\n";
        $orderDetails .= "Состав заказа:\n";
        foreach ($cart as $item) {
            $orderDetails .= "{$item['title']} x {$item['quantity']} = " . ($item['price'] * $item['quantity']) . " евро\n";
        }
        $orderDetails .= "Общая стоимость: $total_price евро\n";

        // Очистка корзины
        unset($_SESSION['cart']);

        // Показ сообщения о успешном оформлении
        echo "<h2>Спасибо за заказ, $name!</h2>";
        echo "<p>Ваш заказ успешно оформлен. Мы свяжемся с вами по телефону $phone для уточнения деталей.</p>";
        echo "<p>Общая стоимость заказа: $total_price евро.</p>";
        echo "<a href='index.php'>Вернуться на главную страницу</a>";
    } else {
        echo "<p>Корзина пуста. Оформить заказ невозможно.</p>";
        echo "<a href='index.php'>Вернуться на главную страницу</a>";
    }
} else {
    header('Location: checkout.php');
    exit;
}
?>
