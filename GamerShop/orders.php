<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=gamershop', 'root', '');

// Получаем ID пользователя
$user_id = $_SESSION['user_id'];

// Получаем заказы пользователя
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ?");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Мои заказы</title>
  <!-- Подключение Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Подключение пользовательских стилей -->
  <link rel="stylesheet" href="orders-style.css"> <!-- Укажите путь к вашему стилю -->
</head>
<body class="bg-dark text-light">

  <!-- Навигация -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-black shadow">
    <div class="container">
      <a class="navbar-brand text-info fw-bold" href="index.php">GamerShop</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <?php
            if (isset($_SESSION["user_id"])) {
                // Если пользователь вошел, показываем кнопку "Выйти"
                echo '<li class="nav-item"><a class="btn btn-outline-info" href="logout.php">Выйти</a></li>';
                echo '<li class="nav-item"><a class="btn btn-outline-info" href="cart.php">Корзина</a></li>';
                echo '<li class="nav-item"><a class="btn btn-outline-info" href="orders.php">Мои заказы</a></li>';
            } else {
                // Если пользователь не вошел, показываем кнопку "Войти"
                echo '<li class="nav-item"><button class="btn btn-outline-info" onclick="location.href=\'register.html\'">Войти</button></li>';
            }
          ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Заголовок -->
  <header class="text-center py-5">
    <h1 class="display-4 text-info">Мои заказы</h1>
  </header>

  <!-- Секция заказов -->
  <div class="container py-5">
    <?php if (count($orders) > 0): ?>
        <?php foreach ($orders as $order): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Заказ #<?= $order['id'] ?> - <?= date('d F Y', strtotime($order['order_date'])) ?></h5>
                    <p class="card-text">Статус: <span class="text-info"><?= $order['status'] ?></span></p>

                    <ul>
                        <?php
                        // Получаем товары в заказе
                        $stmt_items = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
                        $stmt_items->execute([$order['id']]);
                        $order_items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($order_items as $item): ?>
                            <li><?= $item['product_name'] ?> - <?= number_format($item['price'], 2, ',', ' ') ?> ₽ x <?= $item['quantity'] ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <p class="text-info">Итого: <?= number_format($order['total_amount'], 2, ',', ' ') ?> ₽</p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-center text-info">У вас нет заказов.</p>
    <?php endif; ?>
  </div>

  <!-- Подключение Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
