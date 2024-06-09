<?php
namespace app\Controllers;

use app\Models\FinancialRecord;
use app\Models\Category;

class DashboardController {
    public function index() {
        // Логіка для головної сторінки
        include_once __DIR__ . '/../views/dashboard/index.php';
    }

    public function overview() {
        // Логіка для сторінки огляду
        session_start();
        if (!isset($_SESSION['user_id'])) {
            // Якщо user_id не збережено у сесії, перенаправте користувача на сторінку входу
            header("Location: ../profile/profile.php");
            exit;
        }

        $user_id = $_SESSION['user_id'];

        // Отримання фінансових записів
        $financialRecords = FinancialRecord::getAll();

        // Отримання категорій
        $categories = Category::getAll($user_id);

        include_once __DIR__ . '/../views/dashboard/overview.php';
    }

    public function annualStatistics() {
        // Логіка для річної статистики
        session_start();
        if (!isset($_SESSION['user_id'])) {
            // Якщо user_id не збережено у сесії, перенаправте користувача на сторінку входу
            header("Location: ../profile/profile.php");
            exit;
        }

        $user_id = $_SESSION['user_id'];

        // Отримання річної статистики
        $yearlyStatistics = FinancialRecord::getYearlyStatistics($user_id);

        include_once __DIR__ . '/../views/dashboard/annual_statistics.php';
    }

    public function records() {
        // Логіка для сторінки записів
        session_start();
        if (!isset($_SESSION['user_id'])) {
            // Якщо user_id не збережено у сесії, перенаправте користувача на сторінку входу
            header("Location: ../profile/profile.php");
            exit;
        }

        // Отримання фінансових записів
        $financialRecords = FinancialRecord::getAll();

        include_once __DIR__ . '/../views/dashboard/records.php';
    }

    public function categories() {
        // Логіка для сторінки категорій
        session_start();
        if (!isset($_SESSION['user_id'])) {
            // Якщо user_id не збережено у сесії, перенаправте користувача на сторінку входу
            header("Location: ../profile/profile.php");
            exit;
        }

        $user_id = $_SESSION['user_id'];

        // Отримання категорій
        $categories = Category::getAll($user_id);

        include_once __DIR__ . '/../views/dashboard/categories.php';
    }

    public function addCategory() {
        // Логіка для додавання нової категорії
        session_start();
        if (!isset($_SESSION['user_id'])) {
            // Якщо user_id не збережено у сесії, перенаправте користувача на сторінку входу
            header("Location: ../profile/profile.php");
            exit;
        }

        $user_id = $_SESSION['user_id'];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $categoryName = $_POST["category_name"];
            $categoryType = $_POST["category_type"];
            $categoryColor = $_POST["category_color"];

            // Додавання нової категорії
            Category::add($categoryName, $categoryType, $categoryColor, $user_id);

            // Перенаправлення після додавання категорії
            header("Location: categories.php");
            exit;
        }
    }

    public function deleteCategory() {
        // Логіка для видалення категорії
        session_start();
        if (!isset($_SESSION['user_id'])) {
            // Якщо user_id не збережено у сесії, перенаправте користувача на сторінку входу
            header("Location: ../profile/profile.php");
            exit;
        }

        $user_id = $_SESSION['user_id'];

        if (isset($_GET["delete_category"])) {
            $categoryId = $_GET["delete_category"];

            // Видалення категорії
            Category::delete($categoryId);

            // Перенаправлення після видалення категорії
            header("Location: categories.php");
            exit;
        }
    }
}
?>
