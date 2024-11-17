<?php
session_start();

$product_id = $_POST['product_id'];
$title = $_POST['title'];
$description = $_POST['description'];
$price = $_POST['price'];
$quantity = 1;

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Проверка, есть ли уже товар в корзине
if (isset($_SESSION['cart'][$product_id])) {
    // Если товар уже есть, увеличиваем его количество
    $_SESSION['cart'][$product_id]['quantity'] += 1;
} else {
    // Если товара нет, добавляем его
    $_SESSION['cart'][$product_id] = [
        'product_id' => $product_id,
        'title' => $title,
        'description' => $description,
        'price' => $price,
        'quantity' => $quantity
    ];
}

echo json_encode(['status' => 'success', 'message' => 'Товар добавлен в корзину', 'cart' => $_SESSION['cart']]);
exit;
