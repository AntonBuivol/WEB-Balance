<?php
session_start();

if (!isset($_POST['product_id']) || !isset($_SESSION['cart'])) {
    echo json_encode(['status' => 'error', 'message' => 'Некорректный запрос']);
    exit;
}

$product_id = $_POST['product_id'];
$cart = &$_SESSION['cart'];

if (isset($cart[$product_id])) {
    if ($cart[$product_id]['quantity'] > 1) {
        $cart[$product_id]['quantity'] -= 1;
    } else {
        unset($cart[$product_id]);
    }
}

$total_price = 0;
$cart_count = 0;
foreach ($cart as $item) {
    $total_price += $item['price'] * $item['quantity'];
    $cart_count += $item['quantity'];
}

$cart_html = '';
if (!empty($cart)) {
    $cart_html .= '<table><tr><th>Товар</th><th>Цена</th><th>Количество</th><th>Всего</th><th>Действие</th></tr>';
    foreach ($cart as $item) {
        $cart_html .= '<tr>
            <td>' . htmlspecialchars($item['description']) . '</td>
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
