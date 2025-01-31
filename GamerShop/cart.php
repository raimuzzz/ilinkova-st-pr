<?php 
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Подключение к базе данных
$pdo = new PDO('mysql:host=localhost;dbname=gamershop', 'root', '');

// Получаем все товары в корзине для текущего пользователя
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT p.name, p.price, p.image, c.quantity 
                       FROM cart_items c
                       JOIN products p ON c.product_id = p.id
                       WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Вычисляем итоговую сумму
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Корзина</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
  <div class="container py-5">
    <h2 class="text-info text-center">Ваша корзина</h2>

    <?php if (isset($cart_items) && count($cart_items) > 0): ?>
      <div class="list-group">
        <?php foreach ($cart_items as $item): ?>
          <div class="list-group-item bg-black text-light border-info">
            <div class="d-flex">
              <img src="<?= $item["image"] ?>" alt="<?= $item["name"] ?>" class="img-fluid" style="width: 100px; height: 100px; object-fit: cover; margin-right: 20px;">
              <div>
                <h5 class="text-info"><?= $item["name"] ?></h5>
                <p>Цена: <?= number_format($item["price"], 2, ',', ' ') ?> ₽</p>
                <p>Количество: <?= $item["quantity"] ?></p>
                <p>Цена: <?= number_format($item["price"] * $item["quantity"], 2, ',', ' ') ?> ₽</p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <!-- Итоговая сумма -->
      <div class="text-center mt-4">
        <h4 class="text-info">Итоговая сумма: <?= number_format($total_price, 2, ',', ' ') ?> ₽</h4>
        <!-- Кнопка оформления заказа -->
        <form action="checkout.php" method="POST">
            <button type="submit" class="btn btn-success" <?= empty($cart_items) ? 'disabled' : '' ?>>Оформить заказ</button>
        </form>
      </div>
    <?php else: ?>
      <p class="text-center text-info">Ваша корзина пуста</p>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

