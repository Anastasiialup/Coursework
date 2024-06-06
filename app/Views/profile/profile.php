<?php
session_start();

require_once('C:\wamp64\www\KPZLabs\Lab6\app\Controllers\AuthController.php');
require_once('C:\wamp64\www\KPZLabs\Lab6\app\Models\User.php');
require_once('C:\wamp64\www\KPZLabs\Lab6\config\database.php');

use App\Controllers\AuthController;
use App\Models\User;

// Перевірка, чи користувач аутентифікований
$authController = new AuthController(new User($conn));
if (!$authController->isAuthenticated()) {
    // Якщо користувач не аутентифікований, перенаправлення на сторінку входу
    header("Location:../auth/login.php");
    exit;
}

// Отримання даних профілю користувача
$username = $_SESSION["username"];
$userModel = new User($conn);
$userProfile = $userModel->getUserProfile($username);

// Логіка для оновлення профілю
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $newUsername = $_POST['new_username'];

    $userModel->updateProfile($userProfile['id'], $newUsername, $userProfile['email']);

    // Перенаправлення на сторінку профілю після оновлення
    header("Location: profile.php");
    exit;
}

// Логіка для видалення акаунта
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_account'])) {
    $userModel->deleteAccount($userProfile['id']);

    // Розрив сесії та перенаправлення на сторінку входу після видалення акаунта
    session_destroy();
    header("Location: ../auth/login.php");
    exit;
}

// Вихід з сесії
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    // Виклик методу для виходу з сесії
    $userModel->logout();

    // Перенаправлення на сторінку входу після виходу
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        /* CSS стилі для вигляду */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        nav {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
        }

        nav a {
            padding: 10px 20px;
            text-decoration: none;
            color: #333;
        }

        nav a:hover {
            background-color: #ddd;
        }

        main {
            padding: 20px;
        }
        footer {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
            width: 98.7%;
            position: relative;
            bottom: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1;
        }
    </style>
</head>
<body>
<?php include('../partials/header.php'); ?>
<main>
    <h2>Welcome, <?php echo $username; ?>!</h2>

    <!-- Форма для оновлення профілю -->
    <h2>Update Profile</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="new_username">New Username:</label><br>
        <input type="text" id="new_username" name="new_username" value="<?php echo $userProfile['username']; ?>"><br>
        <input type="submit" name="update_profile" value="Update Profile">
    </form>

    <!-- Форма для видалення акаунта -->
    <h2>Delete Account</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <p>Are you sure you want to delete your account?</p>
        <input type="submit" name="delete_account" value="Delete Account">
    </form>

    <!-- Форма для виходу з сесії -->
    <h2>Logout</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="submit" name="logout" value="Logout">
    </form>
</main>
<?php include('../partials/footer.php'); ?>
</body>
</html>
