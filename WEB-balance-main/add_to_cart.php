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
$found = false;
foreach ($_SESSION['cart'] as &$item) {
    if ($item['product_id'] === $product_id) {
        $item['quantity'] += 1;  // Увеличиваем количество
        $found = true;
        break;
    }
}

if (!$found) {
    $_SESSION['cart'][] = [
        'product_id' => $product_id,
        'title' => $title,
        'description' => $description,
        'price' => $price,
        'quantity' => $quantity
    ];
}

echo json_encode(['status' => 'success', 'message' => 'Товар добавлен в корзину', 'cart' => $_SESSION['cart']]);
exit;
