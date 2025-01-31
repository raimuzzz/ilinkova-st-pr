<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Подключение к базе данных
$pdo = new PDO('mysql:host=localhost;dbname=gamershop', 'root', '');

// Получаем ID пользователя
$user_id = $_SESSION['user_id'];

// Получаем товары из корзины
$stmt = $pdo->prepare("SELECT p.id AS product_id, p.name, p.price, c.quantity 
                       FROM cart_items c
                       JOIN products p ON c.product_id = p.id
                       WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Если корзина пуста, перенаправляем назад
if (empty($cart_items)) {
    header('Location: cart.php');
    exit();
}

// Сумма заказа
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// Создаем новый заказ
$stmt = $pdo->prepare("INSERT INTO orders (user_id, order_date, status, total_amount) VALUES (?, NOW(), 'В ожидании', ?)");
$stmt->execute([$user_id, $total_price]);

// Получаем ID нового заказа
$order_id = $pdo->lastInsertId();

// Добавляем товары в заказ
foreach ($cart_items as $item) {
    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$order_id, $item['product_id'], $item['name'], $item['price'], $item['quantity']]);
}

// Очищаем корзину
$stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
$stmt->execute([$user_id]);

// Перенаправляем на страницу "Мои заказы"
header('Location: orders.php');
exit();
?>
