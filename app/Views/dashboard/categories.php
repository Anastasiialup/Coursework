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
    <div class="search-container">
        <label for="search-filter">Пошук категорій:</label>
        <input type="text" id="searchInput" onkeyup="search()" placeholder="Пошук за назвою...">
        <button onclick="search()">Пошук</button>
    </div>
    <table id="categories-table">
        <thead>
        <tr>
            <th onclick="sortTable(0)">Назва категорії<span class="filter-icon" onclick="toggleFilter(0)">🔍</span></th>
            <th onclick="sortTable(1)">Тип категорії<span class="filter-icon" onclick="toggleFilter(1)">🔍</span></th>
            <th>Колір</th>
            <th>Дії</th>
        </tr>
        </thead>
        <tbody id="category-list">
        <?php foreach ($categories as $category): ?>
            <tr>
                <td><?php echo $category['name']; ?></td>
                <td><?php echo $category['type']; ?></td>
                <td><span class="category-color-box" style="background-color: <?php echo $category['color']; ?>"></span></td>
                <td><a href="?delete_category=<?php echo $category['id']; ?>">Видалити</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <form class="add-category-form" method="post">
        <label for="category-name">Назва нової категорї:</label>
        <input type="text" id="category-name" name="category_name" required>
        <label for="category-type">Тип нової категорії:</label>
        <select id="category-type" name="category_type" required>
            <option value="income">Income</option>
            <option value="expense">Expense</option>
        </select>
        <label for="category-color">Колір нової категорії:</label>
        <input type="color" id="category-color" name="category_color" required>
        <button type="submit">Додати нову категорію</button>
    </form>
</main>

<?php include('../partials/footer.php'); ?>

<script src="../../../public/js/categories.js" defer></script>
<script>
    function search() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("categories-table");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0]; // Отримуємо перший <td> у кожному рядку
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
</body>
</html>
