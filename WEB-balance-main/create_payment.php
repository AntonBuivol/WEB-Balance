<?php
session_start();
require_once("conf.php");
global $connection;

if (!isset($_SESSION['user_id'])) {
    // Проверка, что пользователь вошел в систему
    echo json_encode(['status' => 'error', 'message' => 'Пользователь не авторизован']);
    exit;
}

$user_id = $_SESSION['user_id'];
$amount = floatval($_POST['amount']);

if ($amount <= 0) {
    // Проверка, что сумма пополнения больше 0
    echo json_encode(['status' => 'error', 'message' => 'Введите корректную сумму']);
    exit;
}

// Обновление баланса пользователя
$query = $connection->prepare("UPDATE agapov SET balance = balance + ? WHERE id = ?");
$query->bind_param('di', $amount, $user_id);

if ($query->execute()) {
    $_SESSION['balance'] += $amount; // Обновляем баланс в сессии
    echo json_encode(['status' => 'success', 'message' => 'Баланс успешно пополнен']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Ошибка пополнения баланса']);
}

$query->close();
exit;
