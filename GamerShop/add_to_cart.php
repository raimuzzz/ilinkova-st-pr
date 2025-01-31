<?php
session_start();

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];

    // Подключение к базе данных
    $pdo = new PDO('mysql:host=localhost;dbname=gamershop', 'root', '');  // Убедитесь, что база данных правильная

    // Получаем данные о товаре по ID
    $stmt = $pdo->prepare("SELECT name, price FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Если товар найден в базе данных, добавляем его в корзину
        $product_name = $product['name'];
        $product_price = $product['price'];

        // Проверка, есть ли уже этот товар в корзине пользователя
        $stmt = $pdo->prepare("SELECT * FROM cart_items WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cart_item) {
            // Если товар уже есть в корзине, увеличиваем его количество
            $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$user_id, $product_id]);
        } else {
            // Если товара нет в корзине, добавляем его с количеством 1
            $stmt = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $product_id, 1]);
        }

        // Перенаправляем на страницу корзины или каталог после добавления
        header('Location: cart.php');  // Перенаправляем на страницу корзины
        exit();
    } else {
        // Если товара нет в базе данных
        echo "Товар не найден!";
    }
}
?>
