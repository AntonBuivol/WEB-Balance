# WEB-balance

**Описание проекта**

Этот проект представляет собой сайт онлайн-магазина. Этот сайт также имеет  собственный платёжный модуль для оплаты внутрисайтовой валютой. Основной фокус проекта - обеспечение функциональности онлайн-магазина, создание и интеграция платежного модуля.

**Использующиеся технологии**
Язык программирование - PHP, JavaScript, HTML, CSS
База данных - MySQL, XAMPP

**Установка проекта**
Для того чтобы проект заработал, изначально нужно создать базу данных и поменять conf файл.

**Рассмотрим базу данных:**
Для работы нам понадобится 4 таблицы.

  1. Создаём базу данных в XAMPP.
  2. Называем базу данных, как угодно.
  3. Первая таблица "agapov"
     Структура таблицы:
     ![image](https://github.com/user-attachments/assets/34294519-ca1e-480d-be7b-0f81667f1783)

````
    CREATE TABLE `your_database_name`.`agapov` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `balance` DOUBLE DEFAULT 0,
  PRIMARY KEY (`id`)
);
````

  4. Вторая таблица
     Таблица "purchases"
     Структура таблицы:
     ![image](https://github.com/user-attachments/assets/a50408d9-9d2c-4dd7-9275-9ae822f525a4)
````
CREATE TABLE purchases (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES agapov(id),
    product_name VARCHAR(255),
    quantity INT(11) NOT NULL,
    price DECIMAL(10, 2),
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

````
  5. Третья таблица
    Таблица "transactions"
    Структура таблицы:
    ![image](https://github.com/user-attachments/assets/c8971f68-ba06-4ee5-8629-c46d3146469c)
````
CREATE TABLE transactions (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    sender_id INT(11) NOT NULL,
    FOREIGN KEY (sender_id) REFERENCES agapov(id),
    recipient_id INT(11) NOT NULL,
    FOREIGN KEY (recipient_id) REFERENCES agapov(id),
    amount DECIMAL(10,2) NOT NULL,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
````
  6. Четвёртая таблица
    Таблица "cards"
    Структура таблицы:
    ![image](https://github.com/user-attachments/assets/c7205f59-eeac-47d9-8221-8f47d6dd8b54)

````
  CREATE TABLE cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    artist VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255) NOT NULL
);

INSERT INTO cards (title, description, artist, price, image_url) VALUES
('Стена и прочие друзья', 'Убей меня, Эйс', 'Убей меня, Эйс', 25, 'images/card1.jpg'),
('Глаза. Рты.', 'Убей меня, Эйс', 'Убей меня, Эйс', 30, 'images/card2.jpg'),
('Хуже, чем вчера...', 'Убей меня, Эйс', 'Убей меня, Эйс', 120, 'images/card3.jpg'),
('Adrenaline', 'Deftones', 'Deftones', 50, 'images/Deftones-Adrenaline.jpg'),
('Around the fur', 'Deftones', 'Deftones', 120, 'images/Deftones-Around-the-fur.jpg'),
('Covers', 'Deftones', 'Deftones', 70, 'images/Deftones-Covers.jpg');

````

## Основные функции
**Пополнение баланса пользователя.** Находится в файле create_payment.php.
````
// Получаем сумму пополнения из формы
$amount = floatval($_POST['amount']);

// Обновляем баланс пользователя
$query = $connection->prepare("UPDATE agapov SET balance = balance + ? WHERE id = ?");
$query->bind_param('di', $amount, $user_id);
if ($query->execute()) {
    $_SESSION['balance'] += $amount; // Обновляем баланс в сессии
    echo json_encode(['status' => 'success', 'message' => 'Баланс успешно пополнен']);
}
````
**Объяснение:**
* Получаем сумму пополнения.
* Обновляем баланс пользователя в базе данных и в сессии.
* Возвращаем ответ в формате JSON с успешным сообщением.

**Добавление товара в корзину пользователя.** Находится в файле add_to_cart.php.
````
// Получаем данные о товаре из POST-запроса
$product_id = $_POST['product_id'];
$title = $_POST['title'];
$description = $_POST['description'];
$price = $_POST['price'];
$quantity = 1; // По умолчанию добавляем один товар
// Проверка, существует ли уже корзина в сессии

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Если корзины нет, создаем пустой массив
}
// Проверка, есть ли товар уже в корзине

if (isset($_SESSION['cart'][$product_id])) {
    // Если товар уже есть в корзине, увеличиваем его количество на 1
    $_SESSION['cart'][$product_id]['quantity'] += 1;
} else {
    // Если товара нет в корзине, добавляем его в корзину
    $_SESSION['cart'][$product_id] = [
        'product_id' => $product_id,
        'title' => $title,
        'description' => $description,
        'price' => $price,
        'quantity' => $quantity
    ];
}
// Возвращаем JSON-ответ с успешным статусом и текущим состоянием корзины
echo json_encode(['status' => 'success', 'message' => 'Товар добавлен в корзину', 'cart' => $_SESSION['cart']]);
exit;
````
**Объяснение:**
* Получаем информацию о товаре через $_POST (ID товара, название, описание и цену).
* Если корзина не существует в сессии (!isset($_SESSION['cart'])), то создаем пустой массив для хранения товаров.
* Если товар уже есть в корзине (по product_id), увеличиваем его количество.
* Если товара нет в корзине, добавляем новый товар с заданной информацией.
* В конце отправляем JSON-ответ, содержащий статус успешного добавления товара и текущее состояние корзины.

**Удаление товара из корзины пользователя.** Находится в файле remove_from_cart.php.
````
<?php
session_start();

// Проверяем, что product_id передан и корзина существует
if (!isset($_POST['product_id']) || !isset($_SESSION['cart'])) {
    // Если чего-то не хватает, возвращаем ошибку
    echo json_encode(['status' => 'error', 'message' => 'Некорректный запрос']);
    exit;
}
// Получаем ID товара из POST-запроса
$product_id = $_POST['product_id'];
$cart = &$_SESSION['cart']; // Ссылаемся на корзину в сессии
// Проверяем, существует ли товар в корзине

if (isset($cart[$product_id])) {
    // Если количество товара больше 1, уменьшаем его на 1
    if ($cart[$product_id]['quantity'] > 1) {
        $cart[$product_id]['quantity'] -= 1;
    } else {
        // Если товар один, удаляем его из корзины
        unset($cart[$product_id]);
    }
}
// Переменные для вычисления общей стоимости и количества товаров в корзине
$total_price = 0;
$cart_count = 0;
// Перебираем корзину и вычисляем сумму
foreach ($cart as $item) {
    $total_price += $item['price'] * $item['quantity']; // Общая стоимость
    $cart_count += $item['quantity']; // Общее количество товаров
}
// Генерируем HTML-контент для обновления корзины
$cart_html = '';
if (!empty($cart)) {
    // Если корзина не пуста, выводим таблицу с товарами
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
    // Если корзина пуста, выводим соответствующее сообщение
    $cart_html = '<p>Корзина пуста.</p>';
}
// Возвращаем обновленную информацию в формате JSON
echo json_encode([
    'status' => 'success',  // Статус успешного выполнения
    'cart_html' => $cart_html, // Обновленный HTML-код корзины
    'total_price' => $total_price, // Общая стоимость товаров в корзине
    'cart_count' => $cart_count // Общее количество товаров
]);
exit;
?>
````
**Объяснение:**
* Проверяем, что product_id передан и корзина существует в сессии.
* Если товар есть в корзине, его количество уменьшаем, либо он удаляем, если количество равно 1.
* Обновляем общую стоимость и количество товаров в корзине.
* Генерируем новый HTML для отображения корзины.
* Отправляем информация о статусе, обновленный HTML корзины, общую стоимость и количество товаров

**Очищение корзины пользователя, удаляя все товары из сессии.** Находится в файле clear_cart.php.
````
// Удаляем корзину из сессии
unset($_SESSION['cart']);
$total_price = 0; // Общая стоимость после очистки корзины (она будет 0)

echo json_encode([
    'status' => 'success',
    'message' => 'Корзина очищена',
    'total_price' => $total_price
]);
exit;
````
**Объяснение:**
* Функция unset($_SESSION['cart']) удаляет данные корзины из сессии.
* Мы также обнуляем общую стоимость корзины, устанавливая $total_price = 0.
* В ответе JSON передаем информацию о том, что корзина была очищена, и текущую стоимость (0, так как корзина пуста).

**Обрабатывание оплаты заказа.** Находится в файле payment.php.
````
// Получаем текущий баланс пользователя из базы данных
$query = $connection->prepare("SELECT balance FROM agapov WHERE id = ?");
$query->bind_param('i', $user_id);
$query->execute();
$query->bind_result($current_balance);
$query->fetch();
$query->close();

// Рассчитываем общую стоимость корзины
$total_price = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_price += $item['price'] * $item['quantity']; // Суммируем стоимость всех товаров
    }
} else {
    echo "Корзина пуста."; // Если корзина пуста, выводим ошибку
    exit;
}
// Проверка наличия достаточного баланса
if ($current_balance >= $total_price) {
    $new_balance = $current_balance - $total_price; // Новый баланс после оплаты

    // Обновляем баланс пользователя в базе данных
    $update_balance_query = $connection->prepare("UPDATE agapov SET balance = ? WHERE id = ?");
    $update_balance_query->bind_param('di', $new_balance, $user_id);
    $update_balance_query->execute();
    $update_balance_query->close();

    // Записываем покупки в таблицу purchases
    $insert_purchase_query = $connection->prepare("INSERT INTO purchases (user_id, product_name, quantity, price, purchase_date) VALUES (?, ?, ?, ?, NOW())");
    foreach ($_SESSION['cart'] as $item) {
        $product_name = $item['description'];
        $quantity = $item['quantity'];
        $price = $item['price'];
        $insert_purchase_query->bind_param('isid', $user_id, $product_name, $quantity, $price);
        $insert_purchase_query->execute();
    }
    $insert_purchase_query->close();

    // Очищаем корзину
    unset($_SESSION['cart']);

    $message = "Заказ успешно оплачен. Текущий баланс: " . number_format($new_balance, 2) . " евро.";
    $_SESSION['balance'] = $new_balance; // Обновляем баланс в сессии
} else {
    $message = "Недостаточно средств для оплаты. Текущий баланс: " . number_format($current_balance, 2) . " евро.";

````
**Объяснение:**
* Получаем текущий баланс пользователя из базы данных.
* Рассчитываем общую стоимость корзины, перебирая все товары в сессии и умножая цену на количество каждого товара.
* Если баланс достаточен, списываем деньги с баланса пользователя и записываем информацию о покупке в таблицу purchases.
* Очищаем корзину после успешной оплаты.
* Если средств недостаточно, выводим сообщение об ошибке.

**Подтверждение заказа.** Находится в файле process_checkout.php.
````
// Получаем данные из формы
$name = htmlspecialchars($_POST['name']);
$address = htmlspecialchars($_POST['address']);
$phone = htmlspecialchars($_POST['phone']);

// Проверяем, что корзина не пуста
if (!empty($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
    $total_price = 0;
    foreach ($cart as $item) {
        $total_price += $item['price'] * $item['quantity']; // Рассчитываем общую стоимость
    }
    // Составляем строку с деталями заказа
    $orderDetails = "Заказ от: $name\nАдрес: $address\nТелефон: $phone\n";
    $orderDetails .= "Состав заказа:\n";
    foreach ($cart as $item) {
        $orderDetails .= "{$item['title']} x {$item['quantity']} = " . ($item['price'] * $item['quantity']) . " евро\n";
    }
    $orderDetails .= "Общая стоимость: $total_price евро\n";

    // Очистка корзины после оформления заказа
    unset($_SESSION['cart']);

    // Отображаем подтверждение
    echo "<h2>Спасибо за заказ, $name!</h2>";
    echo "<p>Ваш заказ успешно оформлен. Мы свяжемся с вами по телефону $phone для уточнения деталей.</p>";
    echo "<p>Общая стоимость заказа: $total_price евро.</p>";
} else {
    // Если корзина пуста, выводим ошибку
    echo "<p>Корзина пуста. Оформить заказ невозможно.</p>";
}
````
**Объяснение:**
* Получаем данные пользователя из формы и обрабатываем их.
* Проверяем, есть ли товары в корзине.
* Рассчитываем общую стоимость корзины.
* Если корзина не пуста, отображаем заказ и очищаем корзину.
* Если корзина пуста, выводим ошибку.

**Перевод средств между пользователями.** Находится в файле transfer.php.
````
// Получаем данные о пользователях и сумме перевода
$sender_id = $_SESSION['user_id'];
$recipient_username = htmlspecialchars(trim($_POST['recipient']));
$amount = floatval($_POST['amount']);

// Получаем ID получателя по имени пользователя
$query = "SELECT id FROM agapov WHERE username = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param('s', $recipient_username);
$stmt->execute();
$stmt->bind_result($recipient_id);
$stmt->fetch();
$stmt->close();

// Обновляем баланс отправителя
$query = "UPDATE agapov SET balance = balance - ? WHERE id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param('di', $amount, $sender_id);
$stmt->execute();
$stmt->close();

// Обновляем баланс получателя
$query = "UPDATE agapov SET balance = balance + ? WHERE id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param('di', $amount, $recipient_id);
$stmt->execute();
$stmt->close();
````
**Объяснение:**
* Получаем данные отправителя, получателя и сумму перевода.
* Получаем ID получателя по его имени пользователя.
* Выполняем обновление баланса отправителя и получателя в базе данных.
