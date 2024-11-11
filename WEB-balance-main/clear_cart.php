<?php
session_start();

// Очищаем корзину
unset($_SESSION['cart']);

// Возвращаем успешный ответ
echo json_encode(['status' => 'success', 'message' => 'Корзина очищена']);
