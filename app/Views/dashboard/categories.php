<?php
require_once('C:\wamp64\www\MyCoursework\config\database.php');
require_once('C:\wamp64\www\MyCoursework\app\Models\Category.php');
require_once('C:\wamp64\www\MyCoursework\app\Controllers\CategoryController.php');

use App\Models\Category;
use App\Controllers\CategoryController;

// Перевірка, чи є сесія з user_id
session_start();
if (!isset($_SESSION['user_id'])) {
    // Якщо user_id не збережено у сесії, можливо, користувач не увійшов у систему, перенаправте його на сторінку входу
    header("Location: ../profile/profile.php");
    exit;
}

// Обробка додавання нової категорії
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoryName = $_POST["category_name"];
    $categoryType = $_POST["category_type"];
    $categoryColor = $_POST["category_color"];
    $user_id = $_SESSION['user_id']; // Припускається, що ми маємо user_id у сесії

    // Додавання нової категорії за допомогою класу Category
    Category::add($conn, $categoryName, $categoryType, $categoryColor, $user_id);
}

// Видалення категорії при отриманні ID через параметр "delete_category"
if (isset($_GET["delete_category"])) {
    $categoryId = $_GET["delete_category"];

    // Видалення категорії за допомогою класу Category
    Category::delete($conn, $categoryId);
}

// Отримання категорій з бази даних
$user_id = $_SESSION['user_id']; // Припускається, що ми маємо user_id у сесії
$categories = Category::getAll($conn, $user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Categories</title>
    <style>
        /* CSS стилі для вигляду */
        body {
            font-family: "Comic Sans MS";
            margin: 0;
            padding: 0;
            background-color: #cbbdbd;
        }
        header {
            background-color: #674f4f;
            color: #f1e9e9;
            padding: 10px;
            text-align: center;
            width: 90%;
            margin: 0 auto;
        }
        nav {
            background-color: #f4f4f4;
            padding: 10px;
            width: 90%;
            text-align: center;
            margin: 0 auto;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;

        }
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #674f4f;
            color: #fff;
        }
        .add-category-form {
            margin-bottom: 20px; /* Доданий відступ нижче форми */
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
        .category-color-box {
            width: 20px;
            height: 20px;
            display: inline-block;
            margin-right: 5px;
        }
        footer {
            background-color: #674f4f;
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
    </style>
</head>
<body>
<?php include('../partials/header.php'); ?>
<!-- Форма для додавання нової категорії -->
<form class="add-category-form" method="post">
    <label for="category-name">New Category Name:</label>
    <input type="text" id="category-name" name="category_name" required>
    <label for="category-type">Category Type:</label>
    <select id="category-type" name="category_type" required>
        <option value="income">Income</option>
        <option value="expense">Expense</option>
    </select>
    <label for="category-color">Category Color:</label>
    <input type="color" id="category-color" name="category_color" required>
    <button type="submit">Add Category</button>
</form>

<!-- Таблиця для відображення категорій -->
<table>
    <thead>
    <tr>
        <th>Category Name</th>
        <th>Category Type</th>
        <th>Color</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody id="category-list">
    <?php foreach ($categories as $category): ?>
        <tr>
            <td><?php echo $category['name']; ?></td>
            <td><?php echo $category['type']; ?></td>
            <td><span class="category-color-box" style="background-color: <?php echo $category['color']; ?>"></span></td>
            <td><a href="?delete_category=<?php echo $category['id']; ?>">Delete</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php include('../partials/footer.php'); ?>
</body>
</html>
