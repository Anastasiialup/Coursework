<?php
require_once __DIR__ . '/../../Models/Category.php';
require_once __DIR__ . '/../../Models/FinancialRecord.php';
require_once __DIR__ . '/../../Controllers/CategoryController.php';
require_once __DIR__ . '/../../Controllers/RecordController.php';
require_once __DIR__ . '/../../../config/database.php';

use app\Models\FinancialRecord;
use app\Models\Category;

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../profile/profile.php");
    exit;
}

$records = FinancialRecord::getAll($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['month'])) {
    $month = $_POST['month'];
    $year = $_POST['year'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];
    $attachment = $_POST['attachment'];
    $currency = $_POST['currency'];
    $amount = $_POST['amount'];
    $type = $_POST['type'];
    $user_id = $_SESSION['user_id'];

    FinancialRecord::add($conn, $user_id, $month, $year, $category_id, $description, $attachment, $currency, $amount, $type);
    header("Location: records.php");
    exit;
}

if (isset($_GET['delete_record'])) {
    $id = $_GET['delete_record'];
    FinancialRecord::delete($conn, $id);
    header("Location: records.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Records</title>
    <link rel="stylesheet" href="../../../public/css/records.css">
    <script src="../../../public/js/cur.js" defer></script>
    <script src="../../../public/js/records.js" defer></script>
</head>
<body>
<?php include('../partials/header.php'); ?>
<main>
    <label for="category-filter">Фільтр категорій:</label>
    <select id="category-filter">
        <option value="">All</option>
        <?php
        $categories = Category::getAll($conn, $_SESSION['user_id']);
        foreach ($categories as $category) {
            echo "<option value='".$category['id']."'>".$category['name']."</option>";
        }
        ?>
    </select>
    <button id="apply-filters">Застосувати фільтр</button>

    <table id="records-table">
        <thead>
        <tr>
            <th onclick="sortTable(0)">Місяць<span class="filter-icon" onclick="toggleFilter(0)">🔍</span></th>
            <th onclick="sortTable(1)">Рік<span class="filter-icon" onclick="toggleFilter(1)">🔍</span></th>
            <th>Категорія</th>
            <th onclick="sortTable(3)">Опис<span class="filter-icon" onclick="toggleFilter(3)">🔍</span></th>
            <th onclick="sortTable(4)">Кількість<span class="filter-icon" onclick="toggleFilter(4)">🔍</span></th>
            <th>Колір категорії</th> <!-- Додали новий заголовок стовпця -->
            <th>Дії</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($records as $record): ?>
            <tr data-category-id="<?php echo $record['category_id']; ?>" class="data-row"> <!-- Додаємо клас "data-row" -->
                <td><?php echo $record['month']; ?></td>
                <td><?php echo $record['year']; ?></td>
                <td><?php $category = Category::getById($conn, $record['category_id']); echo $category['name']; ?></td>
                <td><?php echo $record['description']; ?></td>
                <td><?php echo $record['currency'] . ' ' . $record['amount']; ?></td>
                <td> <!-- Додаємо стовпчик для кольору категорії -->
                    <div class="category-color-box" style="background-color: <?php echo $category['color']; ?>"></div>
                </td>
                <td><a href="?delete_record=<?php echo $record['id']; ?>">Видалити</a></td>
            </tr>
        <?php endforeach; ?>


        </tbody>
    </table>
    <div class="container">
        <h2>Конвертатор валют</h2>
        <input type="number" id="amount" placeholder="Введіть кількість">
        <select id="fromCurrency">
            <!-- Список валют буде заповнено динамічно -->
        </select>
        <select id="toCurrency">
            <!-- Список валют буде заповнено динамічно -->
        </select>
        <button id="convert">Конвертувати</button>
        <p id="result"></p>
    </div>

    <h2>Додати запис</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="month">Місяць:</label><br>
        <input type="text" id="month" name="month" required><br>
        <label for="year">Рік:</label><br>
        <input type="number" id="year" name="year" required><br>
        <label for="category_id">Категорія:</label><br>
        <select id="category_id" name="category_id" required>
            <?php
            foreach ($categories as $category) {
                echo "<option value='".$category['id']."'>".$category['name']."</option>";
            }
            ?>
        </select><br>
        <label for="description">Опис:</label><br>
        <input type="text" id="description" name="description" required><br>
        <label for="attachment">Вкладення:</label><br>
        <input type="text" id="attachment" name="attachment" required><br>
        <label for="currency">Валюта:</label><br>
        <input type="text" id="currency" name="currency" required><br>
        <label for="amount">Кількість:</label><br>
        <input type="number" id="amount" name="amount" step="0.01" required><br>
        <label for="type">Тип:</label><br>
        <select id="type" name="type" required>
            <option value="income">Income</option>
            <option value="expense">Expense</option>
        </select><br>
        <button type="submit">Додати запис</button>
    </form>

</main>
<?php include('../partials/footer.php'); ?>
</body>
</html>
