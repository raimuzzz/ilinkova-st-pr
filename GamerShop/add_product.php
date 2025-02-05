<?php 
session_start(); 
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") { 
    header("Location: ../index.php"); 
    exit(); 
} 

require "db.php"; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $category = $_POST["category"];
    
    // Обработка изображения
    if (!empty($_FILES["image"]["name"])) {
        $targetDir = "../img";
        $imageName = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $imageName;
        $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];
        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                $image = $imageName;
            } else {
                die("Ошибка загрузки изображения.");
            }
        } else {
            die("Недопустимый формат изображения.");
        }
    } else {
        $image = null;
    }

    // Добавление товара в базу
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, category, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $description, $price, $category, $image]);

    header("Location: index.php");
    exit();
}
?>