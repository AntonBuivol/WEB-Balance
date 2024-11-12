<?php
session_start();

// Очищаем корзину
unset($_SESSION['cart']);
$total_price = 0;

// Возвращаем успешный ответ
echo json_encode([
    'status' => 'success',
    'message' => 'Корзина очищена',
    'total_price' => $total_price
]);
exit;