<?php
session_start();

if (!isset($_POST['product_id']) || !isset($_SESSION['cart'])) {
    echo json_encode(['status' => 'error', 'message' => 'Некорректный запрос']);
    exit;
}

$product_id = $_POST['product_id'];
$cart = &$_SESSION['cart'];

// Уменьшаем количество товара на 1, если он есть в корзине
foreach ($cart as $key => &$item) {
    if ($item['product_id'] == $product_id) {
        if ($item['quantity'] > 1) {
            $item['quantity'] -= 1;  // Уменьшаем количество на 1
        } else {
            unset($cart[$key]);  // Удаляем товар, если количество стало 0
        }
        break;
    }
}

// Пересчитываем общую стоимость и количество товаров
$total_price = 0;
$cart_count = 0;
foreach ($cart as $item) {
    $total_price += $item['price'] * $item['quantity'];
    $cart_count += $item['quantity'];
}

// Генерация HTML корзины
$cart_html = '';
if (!empty($cart)) {
    $cart_html .= '<table><tr><th>Товар</th><th>Цена</th><th>Количество</th><th>Всего</th><th>Действие</th></tr>';
    foreach ($cart as $item) {
        $cart_html .= '<tr>
            <td>' . htmlspecialchars($item['title']) . '</td>
            <td>' . htmlspecialchars($item['price']) . ' евро</td>
            <td>' . $item['quantity'] . '</td>
            <td>' . ($item['price'] * $item['quantity']) . ' евро</td>
            <td><button class="btn btn-warning decrement-item-btn" data-product-id="' . $item['product_id'] . '">Удалить</button></td>
        </tr>';
    }
    $cart_html .= '</table>';
} else {
    $cart_html = '<p>Корзина пуста.</p>';
}

echo json_encode([
    'status' => 'success',
    'cart_html' => $cart_html,
    'total_price' => $total_price,
    'cart_count' => $cart_count
]);
exit;