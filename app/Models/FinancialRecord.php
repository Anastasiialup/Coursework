<?php
// FinancialRecord.php

namespace app\Models;

use PDO;

class FinancialRecord {
    private $db;

    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }

    public static function getAll($db) {
        $stmt = $db->query("SELECT * FROM financial_records");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllFinancialRecords() {
        $sql = "SELECT financial_records.*, categories.name AS category_info
                FROM financial_records 
                JOIN categories ON financial_records.category_id = categories.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function add(PDO $pdo, $user_id, $month, $year, $category_id, $description, $attachment, $currency, $amount, $type) {
        try {
            $stmt = $pdo->prepare("INSERT INTO financial_records (user_id, month, year, category_id, description, attachment, currency, amount, type) 
                                VALUES (:user_id, :month, :year, :category_id, :description, :attachment, :currency, :amount, :type)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':month', $month);
            $stmt->bindParam(':year', $year);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':attachment', $attachment);
            $stmt->bindParam(':currency', $currency);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':type', $type);
            $stmt->execute();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function delete(PDO $pdo, $id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM financial_records WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function getDistinctMonths(PDO $conn) {
        try {
            // Підготовка та виконання SQL-запиту для отримання унікальних місяців
            $stmt = $conn->query("SELECT DISTINCT Month FROM financial_records");

            // Повертаємо результат запиту
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch(PDOException $e) {
            // Вивід повідомлення про помилку в разі виникнення винятку
            echo "Помилка: " . $e->getMessage();
            return []; // Повертаємо пустий масив у випадку помилки
        }
    }

    public static function getDistinctMonthsForYear(PDO $conn, $year) {
        try {
            // Підготовка та виконання SQL-запиту для отримання унікальних місяців для заданого року
            $stmt = $conn->prepare("SELECT DISTINCT Month FROM financial_records WHERE Year = ?");
            $stmt->execute([$year]);

            // Повертаємо результат запиту
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch(PDOException $e) {
            // Вивід повідомлення про помилку в разі виникнення винятку
            echo "Помилка: " . $e->getMessage();
            return []; // Повертаємо пустий масив у випадку помилки
        }
    }

    public static function getTotalByTypeAndMonth(PDO $conn, $type, $month, $year) {
        try {
            // Підготовка та виконання SQL-запиту для отримання загальної суми за типом та місяцем
            $stmt = $conn->prepare("SELECT SUM(amount) AS total FROM financial_records WHERE type = ? AND Month = ? AND Year = ?");
            $stmt->execute([$type, $month, $year]);

            // Отримання результату запиту
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Перевірка, чи отримано результат
            if ($result && isset($result['total'])) {
                return $result['total'];
            } else {
                // Якщо результат не отримано, повертаємо 0
                return 0;
            }
        } catch(PDOException $e) {
            // Вивід повідомлення про помилку в разі виникнення винятку
            echo "Помилка: " . $e->getMessage();
            return 0; // Повертаємо 0 у випадку помилки
        }
    }

    public static function getYearlyStatistics(PDO $conn, $year) {
        try {
            // Підготовка та виконання SQL-запиту для отримання статистики за рік
            $stmt = $conn->prepare("SELECT Month, SUM(amount) AS income, SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) AS expenses FROM financial_records WHERE Year = ? GROUP BY Month");
            $stmt->execute([$year]);

            // Отримання результату запиту
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Повернення результату
            return $result;
        } catch(PDOException $e) {
            // Вивід повідомлення про помилку в разі виникнення винятку
            echo "Помилка: " . $e->getMessage();
            return []; // Повертаємо пустий масив у випадку помилки
        }
    }
}

?>