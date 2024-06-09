<?php
session_start();

require_once __DIR__ . '/../../Controllers/AuthController.php';
require_once __DIR__ . '/../../Models/User.php';
require_once __DIR__ . '/../../../config/database.php';

use app\Controllers\AuthController;
use app\Models\User;

// Перевірка, чи користувач аутентифікований
$authController = new AuthController(new User($conn));
if (!$authController->isAuthenticated()) {
    // Якщо користувач не аутентифікований, перенаправлення на сторінку входу
    header("Location: ../auth/login.php");
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
    header("Location: profile.php");
    exit;
}

// Логіка для видалення акаунта
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_account'])) {
    $userModel->deleteAccount($userProfile['id']);
    session_destroy();
    header("Location: ../auth/login.php");
    exit;
}

// Вихід з сесії
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    $userModel->logout();
    header("Location: ../auth/login.php");
    exit;
}

// Оновлення фотографії профілю
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_photo'])) {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $fileName = $_FILES['profile_image']['name'];
        $fileTmpName = $_FILES['profile_image']['tmp_name'];
        $uploadDir = __DIR__ . '/../../../public/uploads/';
        move_uploaded_file($fileTmpName, $uploadDir . $fileName);
        $userModel->updateProfileImage($userProfile['id'], $fileName);
        header("Location: profile.php");
        exit;
    } else {
        echo "Помилка: файл не був завантажений.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../../../public/css/profile.css">
</head>
<body>
<?php include('../partials/header.php'); ?>
<main>
    <div class="profile-picture">
        <?php
        if (!empty($userProfile['profile_image'])) {
            echo '<img src="../../../public/uploads/' . $userProfile['profile_image'] . '" alt="Profile Picture">';
        } else {
            echo '<img src="../../../public/uploads/default.jpg" alt="Default Profile Picture">';
        }
        ?>
    </div>
    <div class="profile-info">
        <div class="profile-section">
            <h2>Welcome, <?php echo $username; ?>!</h2>

            <!-- Форма для оновлення профілю -->
            <h2>Update Profile</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="new_username">New Username:</label>
                <input type="text" id="new_username" name="new_username" value="<?php echo $userProfile['username']; ?>">
                <input type="submit" name="update_profile" value="Update Profile">
            </form>

            <!-- Форма для оновлення фотографії профілю -->
            <h2>Оновлення фотографії профілю</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                <div class="file-input-container">
                    <label for="profile_image">Виберіть зображення:</label>
                    <input type="file" id="profile_image" name="profile_image">
                </div>
                <input type="submit" name="update_photo" value="Оновити фото">
            </form>
        </div>
        <div class="logout-section">
            <div class="forms-container">
                <!-- Форма для виходу з сесії -->
                <div class="form-item">
                    <h2>Logout</h2>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <p>Are you sure you want to leave your session?</p>
                        <input type="submit" name="logout" value="Logout">
                        <p></p> <!-- Порожній абзац для вирівнювання -->
                    </form>
                </div>

                <!-- Форма для видалення акаунта -->
                <div class="form-item">
                    <h2>Delete Account</h2>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <p>Are you sure you want to delete your account?</p>
                        <input type="submit" name="delete_account" value="Delete Account">
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include('../partials/footer.php'); ?>
</body>
</html>
