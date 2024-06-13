<?php
session_start();

require_once __DIR__ . '/../../Controllers/AuthController.php';
require_once __DIR__ . '/../../Models/User.php';
require_once __DIR__ . '/../../../config/database.php';

use app\Controllers\AuthController;
use app\Models\User;

$userModel = new User($conn);
$authController = new AuthController($userModel);

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $authController->login($_POST['username'], $_POST['password']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../../../public/css/login.css">
</head>
<body>
<div class="container">
    <h2>Вхід на сайт</h2>
    <form id="login-form" method="post">
        <label for="username">Логін:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>
        <input type="submit" value="Увійти">
    </form>
    <p>Ще не зареєстровані?<br> <a href="register.php">Зареєструватися тут</a>.</p>
    <div id="message"></div>
</div>
<script>
    document.getElementById('login-form').addEventListener('submit', async function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData
        });

        const result = await response.text();
        document.getElementById('message').innerHTML = result;

        if (response.ok) {
            window.location.href = '../dashboard/index.php';
        }
    });
</script>
</body>
</html>
