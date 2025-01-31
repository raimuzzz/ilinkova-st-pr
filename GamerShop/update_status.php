<?php
session_start();

// Проверяем, что пользователь авторизован и является администратором
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Подключение к базе данных
$pdo = new PDO('mysql:host=localhost;dbname=gamershop', 'root', '');

// Получаем данные из формы
$order_id = $_POST['order_id'];
$status = $_POST['status'];

// Обновляем статус заказа в базе данных
$stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
$stmt->execute([$status, $order_id]);

// Перенаправляем обратно на страницу заказов
header('Location: orders.php');
exit();
?>
