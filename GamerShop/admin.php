
<?php 
session_start(); 
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") { 
    header("Location: ../index.html"); 
    exit(); 
} 
require "db.php"; 

// Получаем список товаров из базы
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h1>Добро пожаловать, администратор!</h1>
    <a href="logout.php" class="btn btn-danger">Выйти</a>

    <h2 class="mt-4">Добавить товар</h2>
    <form action="add_product.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Название товара</label>
            <input type="text" class="form-control" name="name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Описание</label>
            <textarea class="form-control" name="description"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Цена (₽)</label>
            <input type="number" step="0.01" class="form-control" name="price" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Категория</label>
            <input type="text" class="form-control" name="category" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Изображение</label>
            <input type="file" class="form-control" name="image" required>
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>

    <h2 class="mt-4">Список товаров</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Цена</th>
                <th>Категория</th>
                <th>Изображение</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['id']) ?></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= number_format($product['price'], 2) ?> ₽</td>
                    <td><?= htmlspecialchars($product['category']) ?></td>
                    <td>
                        <?php if ($product['image']): ?>
                            <img src="../img/<?= htmlspecialchars($product['image']) ?>" width="50">
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>