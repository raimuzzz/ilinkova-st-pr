<?php
session_start();

// Подключение к базе данных
$pdo = new PDO('mysql:host=localhost;dbname=gamershop', 'root', '');  // замените на свои данные

// Запрос товаров из базы данных
$query = $pdo->query("SELECT * FROM products");
$products = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Каталог товаров - GamerShop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-dark text-light">
  <nav class="navbar navbar-expand-lg navbar-dark bg-black shadow">
    <div class="container">
      <a class="navbar-brand text-info fw-bold" href="#">GamerShop</a>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <?php
          if (isset($_SESSION["user_id"])) {
              echo '<li class="nav-item"><a class="btn btn-outline-info" href="orders.php">Мои заказы</a></li>';
              echo '<li class="nav-item"><a class="btn btn-outline-info" href="cart.php">Корзина</a></li>';
              echo '<li class="nav-item"><a class="btn btn-outline-info" href="logout.php">Выйти</a></li>';
          } else {
              echo '<li class="nav-item"><a class="btn btn-outline-info" href="login.php">Войти</a></li>';
          }
          ?>
        </ul>
      </div>
    </div>
  </nav>

  <header class="text-center py-5">
    <h1 class="display-4 text-info">Каталог товаров</h1>
    <p class="lead">Выберите свои любимые игры и аксессуары!</p>
  </header>

  <?php
  // Разделение товаров по категориям
  $categories = ['PlayStation', 'Xbox', 'Аксессуары'];
  foreach ($categories as $category) {
      echo '<section id="' . strtolower($category) . '-catalog" class="py-5">';
      echo '<h2 class="text-info text-center mb-4">Каталог ' . $category . '</h2>';
      echo '<div class="container"><div class="row g-4">';

      foreach ($products as $product) {
          if ($product['category'] == $category) {
              echo '<div class="col-sm-6 col-md-4 col-lg-3 d-flex">
                      <div class="card bg-black text-light border-info shadow w-100">
                        <img src="' . $product['image'] . '" class="card-img-top" alt="' . $product['name'] . '">
                        <div class="card-body d-flex flex-column">
                          <h5 class="card-title text-info">' . $product['name'] . '</h5>
                          <p class="card-text">' . $product['description'] . '</p>
                          <p class="price mt-auto">' . number_format($product['price'], 2, ',', ' ') . ' ₽</p>
                          <form action="add_to_cart.php" method="POST">
                            <input type="hidden" name="product_id" value="' . $product['id'] . '">
                            <button class="btn btn-info w-100" type="submit">В корзину</button>
                          </form>
                        </div>
                      </div>
                    </div>';
          }
      }
      echo '</div></div></section>';
  }
  ?>

  <footer class="text-center py-4 mt-5 border-top border-info">
    <p class="mb-0">© 2025 GameShop. Все права защищены.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>