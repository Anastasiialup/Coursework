<?php

namespace app\Controllers;

use app\Models\User;
use PDO;
use PDOException;

class AuthController
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    // Метод для перевірки аутентифікації користувача
    public function isAuthenticated(): bool
    {
        // Перевірка, чи є у сесії прапорець, що позначає аутентифікованого користувача
        return isset($_SESSION["authenticated"]) && $_SESSION["authenticated"] === true;
    }

    public function login($username = null, $password = null): void
    {
        // Перевірка, чи був відправлений POST-запит
        if (!isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"] != "POST") {
            // Якщо метод запиту не є POST, видаємо помилку
            echo "Метод запиту повинен бути POST.";
            include_once __DIR__ . '/../views/auth/login.php';
            return;
        }

        // Перевірка, чи вказані ім'я користувача та пароль
        if ($username === null || $password === null) {
            // Передайте помилку, якщо ім'я користувача або пароль не вказані
            echo "Ім'я користувача або пароль не вказані.";
            include_once __DIR__ . '/../views/auth/login.php';
            return;
        }
        try {
            // Підготовка та виконання SQL-запиту для перевірки наявності користувача
            $stmt = $this->user->getUserByUsernameAndPassword($username, $password);

            // Перевірка, чи отримано користувача
            if ($stmt->rowCount() !== 1) {
                // Якщо користувача не знайдено, виводимо повідомлення про помилку
                echo "Неправильне ім'я користувача або пароль.";
                include_once __DIR__ . '/../views/auth/login.php';
                return;
            }
            // Отримання даних про користувача
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Якщо користувач знайдений, аутентифікуємо його
            $_SESSION["authenticated"] = true;
            $_SESSION["username"] = $user['username'];
            $_SESSION["user_id"] = $user['id'];

            // Перенаправлення на головну сторінку
            header("location: ../dashboard/index.php");
            exit;
        } catch (PDOException $e) {
            // Вивід повідомлення про помилку в разі виникнення винятку
            echo "Помилка: " . $e->getMessage();
        }
    }

    public function register(): void
    {
        if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Показуємо форму реєстрації
            include_once __DIR__ . '/../views/auth/register.php';
            return;
        }
        // Отримуємо дані з форми реєстрації
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Перевіряємо коректність введених даних
        if (empty($username) || empty($email) || empty($password)) {
            // Виводимо повідомлення про необхідність заповнення всіх полів
            echo "Будь ласка, заповніть всі поля";
            return;
        }
        // Перевіряємо, чи не існує вже користувач з таким іменем або email
        if ($this->user->exists($username, $email)) {
            // Користувач з таким ім'ям або email вже існує, виводимо повідомлення
            echo "Користувач з таким ім'ям або email вже існує";
            return;
        }
        // Створюємо нового користувача
        if ($this->user->register($username, $email, $password)) {
            // Реєстрація успішна, перенаправляємо на сторінку входу
            header('Location: ../../views/auth/login.php');
            exit;
        } else {
            // Помилка реєстрації, виводимо повідомлення
            echo "Виникла помилка при реєстрації";
        }
    }
}