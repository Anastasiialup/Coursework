<?php
require_once __DIR__ . '/../../Models/FinancialRecord.php';
require_once __DIR__ . '/../../../config/database.php';

use app\Models\FinancialRecord;

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../profile/profile.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$saves_total = FinancialRecord::getTotalByCategory($conn, $user_id, 'saves');
$expenses_total = FinancialRecord::getTotalByType($conn, $user_id, 'expense');
$income_total = FinancialRecord::getTotalByType($conn, $user_id, 'income');
$wallet_balance = FinancialRecord::getWalletBalance($conn, $user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Financial Planner - Saves</title>
    <link rel="stylesheet" href="../../../public/css/savess.css">
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
    <div class="container-row">
        <div class="text-container">
            <h2>Надходження</h2>
            <p>Загальна сума надходжень: <?php echo $income_total; ?></p>
        </div>
        <div class="text-container">
            <h2>Витрати</h2>
            <p>Загальна сума витрат: <?php echo $expenses_total; ?></p>
        </div>
    </div>
    <img src="https://gifdb.com/images/high/empty-wallet-no-money-unhappy-guy-lqj07kj2c4sxegf3.gif" alt="GIF">

</main>
<?php include('../partials/footer.php'); ?>
</body>
</html>
