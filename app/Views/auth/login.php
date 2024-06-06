<?php
session_start();

require_once('C:\wamp64\www\MyCoursework\app\Controllers\AuthController.php');
require_once('C:\wamp64\www\MyCoursework\app\Models\User.php');
require_once('C:\wamp64\www\MyCoursework\config\database.php');

use App\Controllers\AuthController;
use App\Models\User;

$userModel = new User($conn);
$authController = new AuthController($userModel);

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    // Обробка відправленої форми тільки у випадку, якщо REQUEST_METHOD встановлено і дорівнює "POST"
    $authController->login($_POST['username'], $_POST['password']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: "Comic Sans MS";
            background-image: url('https://balthazar.club/uploads/posts/2023-01/1674323013_balthazar-club-p-finansi-estetika-oboi-18.jpg');
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.85); /* Прозорий білий колір з прозорістю 80% */
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #555;
        }

        p {
            text-align: center;
            margin-top: 10px;
        }

        a {
            color: #333;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Вхід на сайт</h2>
    <form action="" method="post">
        <label for="username">Логін:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>
        <input type="submit" value="Увійти">
    </form>
    <p>Ще не зареєстровані?<br> <a href="register.php">Зареєструватися тут</a>.</p> <!-- Слова "Зареєструватися тут" розміщено на новому рядку -->
</div>
</body>
</html>
