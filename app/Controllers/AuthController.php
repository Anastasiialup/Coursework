<?php
namespace App\Controllers;

use App\Models\User;
use PDO;

class AuthController {
    private $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    // Метод для перевірки аутентифікації користувача
    public function isAuthenticated() {
        // Перевірка, чи є у сесії прапорець, що позначає аутентифікованого користувача
        return isset($_SESSION["authenticated"]) && $_SESSION["authenticated"] === true;
    }

    public function login($username = null, $password = null) {
        // Перевірка, чи був відправлений POST-запит
        if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
            // Перевірка, чи вказані ім'я користувача та пароль
            if ($username !== null && $password !== null) {
                try {
                    // Підготовка та виконання SQL-запиту для перевірки наявності користувача
                    $stmt = $this->user->getUserByUsernameAndPassword($username, $password);

                    // Перевірка, чи отримано користувача
                    if ($stmt->rowCount() == 1) {
                        // Отримання даних про користувача
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);

                        // Якщо користувач знайдений, аутентифікуємо його
                        $_SESSION["authenticated"] = true;
                        $_SESSION["username"] = $user['username'];
                        $_SESSION["user_id"] = $user['id'];

                        // Перенаправлення на головну сторінку
                        header("location: ../dashboard/index.php");
                        exit;
                    } else {
                        // Якщо користувача не знайдено, виводимо повідомлення про помилку
                        echo "Неправильне ім'я користувача або пароль.";
                    }
                } catch(PDOException $e) {
                    // Вивід повідомлення про помилку в разі виникнення винятку
                    echo "Помилка: " . $e->getMessage();
                }
            } else {
                // Передайте помилку, якщо ім'я користувача або пароль не вказані
                echo "Ім'я користувача або пароль не вказані.";
            }
        } else {
            // Якщо метод запиту не є POST, видаємо помилку
            echo "Метод запиту повинен бути POST.";
        }

        // Показуємо форму входу
        include_once __DIR__ . '/../views/auth/login.php';
    }

    public function register() {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            // Отримуємо дані з форми реєстрації
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Перевіряємо коректність введених даних
            if (!empty($username) && !empty($email) && !empty($password)) {
                // Перевіряємо, чи не існує вже користувач з таким іменем або email
                if (!$this->user->exists($username, $email)) {
                    // Створюємо нового користувача
                    if ($this->user->register($username, $email, $password)) {
                        // Реєстрація успішна, перенаправляємо на сторінку входу
                        header('Location: ../../views/auth/login.php');
                        exit;
                    } else {
                        // Помилка реєстрації, виводимо повідомлення
                        echo "Виникла помилка при реєстрації";
                    }
                } else {
                    // Користувач з таким ім'ям або email вже існує, виводимо повідомлення
                    echo "Користувач з таким ім'ям або email вже існує";
                }
            } else {
                // Виводимо повідомлення про необхідність заповнення всіх полів
                echo "Будь ласка, заповніть всі поля";
            }
        }

        // Показуємо форму реєстрації
        include_once __DIR__ . '/../views/auth/register.php';
    }
}
?>
