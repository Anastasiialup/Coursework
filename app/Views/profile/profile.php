<?php
session_start();

require_once('C:\wamp64\www\MyCoursework\app\Controllers\AuthController.php');
require_once('C:\wamp64\www\MyCoursework\app\Models\User.php');
require_once('C:\wamp64\www\MyCoursework\config\database.php');

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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_photo'])) {
    // Перевірка, чи був файл завантажений успішно
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        // Отримання інформації про завантажений файл
        $fileName = $_FILES['profile_image']['name'];
        $fileTmpName = $_FILES['profile_image']['tmp_name'];

        // Переміщення завантаженого файлу в директорію, де знаходиться profile.php
        $uploadDir = __DIR__ . '/../../../public/uploads/';
        move_uploaded_file($fileTmpName, $uploadDir . $fileName);

        // Оновлення поля profile_image в базі даних
        $userModel->updateProfileImage($userProfile['id'], $fileName);

        // Перенаправлення на сторінку профілю після оновлення
        header("Location: profile.php");
        exit;
    } else {
        // Обробка помилки, якщо файл не був завантажений
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
    <style>
        /* CSS стилі для вигляду */
        body {
            font-family: "Comic Sans MS";
            margin: 0;
            padding: 0;
            background-image: url('https://static.wixstatic.com/media/aafcc4_88e3f58195dc4a2d8c0a9f9e26b18984~mv2.png/v1/fill/w_1264,h_713,al_c,q_90,usm_0.66_1.00_0.01,enc_auto/Picsart_24-06-08_16-51-27-432.png');
        }
        header {
            background-color: #705d5d;
            color: #f1e9e9;
            padding: 10px;
            text-align: center;
            width: 90%;
            margin: 0 auto;
        }
        nav {
            background-color: #eae3e3;
            padding: 10px;
            width: 90%;
            text-align: center;
            margin: 0 auto;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
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
            width: 90%;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: row; /* Розташування фото та тексту в рядок */
        }
        .profile-picture {
            margin-top: 100px; /* Відступ зверху */
            width: 500px;
            height: 500px;
            overflow: hidden;
            border-radius: 50%; /* Заокруглені кути, щоб створити круглий кадр */
            margin-right: 50px; /* Відступ тексту від фото */
            flex-shrink: 0; /* Щоб картинка не зменшувалася */
        }
        .profile-picture img {
            width: 100%; /* Забезпечуємо, що зображення буде заповнювати весь круглий кадр */
            height: auto;
        }
        .profile-info {
            flex: 1;
        }
        .profile-section, .logout-section {
            background-color: rgba(234, 227, 227, 0.5);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
        }
        .file-input-container {
            margin-bottom: 10px;
        }
        .file-input-container label {
            display: block;
            margin-bottom: 5px;
        }
        .file-input-container input[type="file"] {
            padding: 10px;
            background-color: #eae3e3;
            border: 1px solid #333;
            border-radius: 5px;
            cursor: pointer;
        }
        form {
            margin-bottom: 20px;
        }
        label, input[type="text"], input[type="submit"], p {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"] {
            width: 20%; /* Зменшення ширини поля введення */
            padding: 10px;
            border: 1px solid #333;
            border-radius: 5px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #705d5d;
            color: #f1e9e9;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #856767;
        }
        h2 {
            margin-top: 0;
        }
        footer {
            background-color: #705d5d;
            color: #fff;
            padding: 10px;
            text-align: center;
            width: 90%;
            margin: 20px auto 0;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
        .logout-section .forms-container {
            display: flex;
            gap: 30px; /* Збільшує відстань між кнопками */
            align-items: flex-start;
        }
    </style>
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
