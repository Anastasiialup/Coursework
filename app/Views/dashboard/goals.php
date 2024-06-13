<?php
require_once __DIR__ . '/../../Models/FinancialRecord.php';
require_once __DIR__ . '/../../Models/Goal.php';
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../Controllers/GoalController.php';

use app\Models\FinancialRecord;
use app\Models\Goal;
use app\Controllers\GoalController;

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../profile/profile.php");
    exit;
}

$user_id = $_SESSION['user_id'];
GoalController::handleRequest($conn, $user_id);

$saves_total = FinancialRecord::getTotalByCategory($conn, $user_id, 'saves');
$expenses_total = FinancialRecord::getTotalByType($conn, $user_id, 'expense');
$income_total = FinancialRecord::getTotalByType($conn, $user_id, 'income');
$wallet_balance = FinancialRecord::getWalletBalance($conn, $user_id);
$goals = Goal::getAllGoals($conn, $user_id);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ваш Фінансовий Планувальник - Цілі</title>
    <link rel="stylesheet" href="../../../public/css/goalss.css">
</head>
<body>
<?php include('../partials/header.php'); ?>
<main>
    <div class="container-row">
        <div class="text-container">
            <h2>Гаманець</h2>
            <p>Загальна сума на гаманці: <?php echo $wallet_balance; ?></p>
        </div>
        <div class="text-container">
            <h2>Збереження</h2>
            <p>Загальна сума збережень: <?php echo $saves_total; ?></p>
        </div>
    </div>

    <div class="goals-container">
        <h2>Ваші Цілі</h2>
        <?php foreach ($goals as $goal): ?>
            <?php if ($goal['status'] !== 'досягнуто'): ?>
                <div class="goal">
                    <img src="../../../public/goals/<?php echo $goal['photo']; ?>" alt="Goal Image">
                    <h3><?php echo $goal['name']; ?></h3>
                    <p><?php echo $goal['description']; ?></p>
                    <p>Ціна: <?php echo $goal['price'] . ' ' . $goal['currency']; ?></p>
                    <p>Статус: <?php echo $goal['status']; ?></p>
                    <?php
                    $difference = $wallet_balance - $goal['price'];
                    if ($difference >= 0) {
                        echo "<p>Можете собі дозволити</p>";
                    } else {
                        echo "<p>Не можете собі дозволити</p>";
                    }
                    ?>
                    <form method="post" action="goals.php">
                        <input type="hidden" name="goal_id" value="<?php echo $goal['id']; ?>">
                        <button type="submit" name="delete_goal">Видалити</button>
                        <button type="submit" name="update_status">Позначити як досягнуто</button>
                    </form>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <h2>Досягнуті цілі</h2>
    <div class="achieved-goals">
        <?php foreach ($goals as $goal): ?>
            <?php if ($goal['status'] === 'досягнуто'): ?>
                <div class="goal">
                    <img src="../../../public/goals/<?php echo $goal['photo']; ?>" alt="Goal Image">
                    <h3><?php echo $goal['name']; ?></h3>
                    <p><?php echo $goal['description']; ?></p>
                    <p>Ціна: <?php echo $goal['price'] . ' ' . $goal['currency']; ?></p>
                    <p>Статус: <?php echo $goal['status']; ?></p>
                    <form method="post" action="goals.php">
                        <input type="hidden" name="goal_id" value="<?php echo $goal['id']; ?>">
                        <button type="submit" name="delete_goal">Видалити</button>
                    </form>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <h2>Додати нову ціль</h2>
    <form method="post" action="goals.php" enctype="multipart/form-data">
        <label for="name">Назва цілі:</label>
        <input type="text" id="name" name="name" required>
        <label for="description">Опис:</label>
        <textarea id="description" name="description" required></textarea>
        <label for="price">Ціна:</label>
        <input type="number" id="price" name="price" required>
        <label for="currency">Валюта:</label>
        <input type="text" id="currency" name="currency" required>
        <label for="photo">Фото:</label>
        <input type="file" id="photo" name="photo" required>
        <button type="submit" name="add_goal">Додати ціль</button>
    </form>
</main>
<?php include('../partials/footer.php'); ?>
</body>
</html>
