<?php
require_once __DIR__ . '/../../Models/Category.php';
require_once __DIR__ . '/../../Models/FinancialRecord.php';
require_once __DIR__ . '/../../../config/database.php';

use app\Models\FinancialRecord;
use app\Models\Category;

session_start();
if (!isset($_SESSION['user_id'])) {
    // Якщо user_id не збережено у сесії, можливо, користувач не увійшов у систему, перенаправте його на сторінку входу
    header("Location: ../profile/profile.php");
    exit;
}

// Отримання фінансових записів з бази даних за допомогою класу FinancialRecord
$records = FinancialRecord::getAll($conn);

// Перевірка, чи існує ключ REQUEST_METHOD та чи він є POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['month'])) {
    $month = $_POST['month'];
    $year = $_POST['year'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];
    $attachment = $_POST['attachment'];
    $currency = $_POST['currency'];
    $amount = $_POST['amount'];
    $type = $_POST['type'];
    $user_id = $_SESSION['user_id']; // Припускається, що ми маємо user_id у сесії

    // Додавання нового фінансового запису за допомогою класу FinancialRecord
    FinancialRecord::add($conn, $user_id, $month, $year, $category_id, $description, $attachment, $currency, $amount, $type);

    // Перенаправлення після додавання запису (за бажанням)
    header("Location: records.php");
    exit;
}

// Видалення запису, якщо ID запису передано через URL параметр
if (isset($_GET['delete_record'])) {
    $id = $_GET['delete_record'];

    // Видалення фінансового запису за допомогою класу FinancialRecord
    FinancialRecord::delete($conn, $id);

    // Перенаправлення після видалення запису (за бажанням)
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
        }
        label {
            margin-right: 10px;
        }
        select, input, button {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #333;
            border-radius: 5px;
            font-size: 16px;
        }
        select, input[type="text"], input[type="number"] {
            width: calc(20% - 22px);
        }
        button {
            background-color: #705d5d;
            color: #f1e9e9;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
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
    <label for="category-filter">Filter by Category:</label>
    <select id="category-filter">
        <option value="">All</option>
        <?php
        // Отримання категорій з бази даних
        $categories = Category::getAll($conn, $_SESSION['user_id']);

        // Виведення категорій у випадаючому списку
        foreach ($categories as $category) {
            echo "<option value='".$category['id']."'>".$category['name']."</option>";
        }
        ?>
    </select>
    <button id="apply-filters">Apply Filters</button>

    <!-- Таблиця для відображення фінансових записів -->
    <table id="records-table">
        <thead>
        <tr>
            <th>Month</th>
            <th>Year</th>
            <th>Category</th>
            <th>Description</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($records as $record): ?>
            <tr data-category-id="<?php echo $record['category_id']; ?>">
                <td><?php echo $record['month']; ?></td>
                <td><?php echo $record['year']; ?></td>
                <td><?php $category = Category::getById($conn, $record['category_id']); echo $category['name']; ?></td>
                <td><?php echo $record['description']; ?></td>
                <td><?php echo $record['currency'] . ' ' . $record['amount']; ?></td>
                <td><a href="?delete_record=<?php echo $record['id']; ?>">Delete</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="month">Month:</label><br>
        <input type="text" id="month" name="month" required><br>
        <label for="year">Year:</label><br>
        <input type="number" id="year" name="year" required><br>
        <label for="category_id">Category:</label><br>
        <select id="category_id" name="category_id" required>
            <?php
            // Виведення категорій у випадаючому списку
            foreach ($categories as $category) {
                echo "<option value='".$category['id']."'>".$category['name']."</option>";
            }
            ?>
        </select><br>
        <label for="description">Description:</label><br>
        <input type="text" id="description" name="description" required><br>
        <label for="attachment">Attachment:</label><br>
        <input type="text" id="attachment" name="attachment" required><br>
        <label for="currency">Currency:</label><br>
        <input type="text" id="currency" name="currency" required><br>
        <label for="amount">Amount:</label><br>
        <input type="number" id="amount" name="amount" step="0.01" required><br>
        <label for="type">Type:</label><br>
        <select id="type" name="type" required>
            <option value="income">Income</option>
            <option value="expense">Expense</option>
        </select><br>
        <button type="submit">Add Record</button>
    </form>
</main>
<script>
    document.getElementById('apply-filters').addEventListener('click', function() {
        var categoryFilter = document.getElementById('category-filter').value;
        var tableRows = document.querySelectorAll('#records-table tbody tr');

        tableRows.forEach(function(row) {
            var categoryId = row.getAttribute('data-category-id');

            if (categoryFilter === '' || categoryId === categoryFilter) {
                row.style.display = 'table-row';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
<?php include('../partials/footer.php'); ?>
</body>
</html>
