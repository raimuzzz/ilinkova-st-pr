<?php
include "db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверка на наличие данных в POST
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);

        // Подготовка и выполнение SQL запроса для проверки пользователя
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Проверка пароля
        if ($user && password_verify($password, $user["password"])) {
            // Устанавливаем сессионные данные
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["role"] = $user["role"];

            // Логирование роли пользователя для отладки
            error_log("User role: " . $_SESSION["role"]);

            // Ответ с редиректом в зависимости от роли
            if ($user["role"] === "admin") {
                echo json_encode(["status" => "success", "redirect" => "admin.php"]);
            } else {
                echo json_encode(["status" => "success", "redirect" => "index.php"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Неверный email или пароль"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Некорректные данные"]);
    }
}
?>

