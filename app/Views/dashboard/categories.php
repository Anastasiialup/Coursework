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
    <link rel="stylesheet" href="../../../public/css/categories.css">
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
