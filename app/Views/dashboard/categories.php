<?php
require_once __DIR__ . '/../../Models/Category.php';
require_once __DIR__ . '/../../Controllers/CategoryController.php';
require_once __DIR__ . '/../../../config/database.php';

use app\Models\Category;
use app\Controllers\CategoryController;

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
            background-image: url('https://static.wixstatic.com/media/aafcc4_88e3f58195dc4a2d8c0a9f9e26b18984~mv2.png/v1/fill/w_1264,h_713,al_c,q_90,usm_0.66_1.00_0.01,enc_auto/Picsart_24-06-08_16-51-27-432.png');        }
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
        }
        .add-category-form {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        .add-category-form label,
        .add-category-form input,
        .add-category-form select,
        .add-category-form button {
            margin-bottom: 10px;
            font-size: 16px;
        }
        .add-category-form input,
        .add-category-form select {
            padding: 10px;
            border: 1px solid #333;
            border-radius: 5px;
            width: calc(20% - 22px);
        }
        .add-category-form button {
            padding: 10px 20px;
            background-color: #705d5d;
            color: #f1e9e9;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .add-category-form button:hover {
            background-color: #856767;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;

        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            background-color: rgba(234, 227, 227, 0.5);        }
        th {
            background-color: #705d5d;
            color: #fff;
        }
        .category-color-box {
            width: 20px;
            height: 20px;
            display: inline-block;
            margin-right: 5px;
            border: 1px solid #333;
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
    </style>
</head>
<body>
<?php include('../partials/header.php'); ?>

<main>

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

</main>

<?php include('../partials/footer.php'); ?>
</body>
</html>
