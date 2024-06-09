<?php
require_once __DIR__ . '/../../Models/FinancialRecord.php';
require_once __DIR__ . '/../../../config/database.php';

use app\Models\FinancialRecord;

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../profile/profile.php");
    exit;
}

$months = FinancialRecord::getDistinctMonths($conn);

$chart_data_json = null;
$yearChartDataJson = null;

if (isset($_GET['month']) && isset($_GET['year'])) {
    $selected_month = $_GET['month'];
    $selected_year = $_GET['year'];

    $total_expenses = FinancialRecord::getTotalByTypeAndMonth($conn, 'expense', $selected_month, $selected_year);
    $total_income = FinancialRecord::getTotalByTypeAndMonth($conn, 'income', $selected_month, $selected_year);

    $chart_data = [
        'labels' => ['Income', 'Expenses'],
        'datasets' => [
            [
                'label' => 'Overview',
                'data' => [$total_income, $total_expenses],
                'backgroundColor' => ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                'borderColor' => ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                'borderWidth' => 1
            ]
        ]
    ];
    $chart_data_json = json_encode($chart_data);
}

if (isset($_GET['year2'])) {
    $selected_year2 = $_GET['year2'];
    $yearChartData = FinancialRecord::getYearlyStatistics($conn, $selected_year2);
    $yearChartLabels = [];
    $yearChartIncome = [];
    $yearChartExpenses = [];
    foreach ($yearChartData as $data) {
        $yearChartLabels[] = $data['Month']; // Отримання місяців
        $yearChartIncome[] = $data['income'];
        $yearChartExpenses[] = $data['expenses'];
    }
    $yearChart = [
        'labels' => $yearChartLabels,
        'datasets' => [
            [
                'label' => 'Income',
                'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                'borderColor' => 'rgba(75, 192, 192, 1)',
                'borderWidth' => 1,
                'data' => $yearChartIncome
            ],
            [
                'label' => 'Expenses',
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                'borderColor' => 'rgba(255, 99, 132, 1)',
                'borderWidth' => 1,
                'data' => $yearChartExpenses
            ]
        ]
    ];
    $yearChartDataJson = json_encode($yearChart);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overview</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .chart-container {
            width: 50%;
            margin: auto;
        }
        .chart {
            width: 100%;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }
        select, input[type="text"], button {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #333;
            border-radius: 5px;
            font-size: 16px;
            width: 200px;
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
    <form action="overview.php" method="get">
        <select name="month" id="month">
            <option value="January">January</option>
            <option value="February">February</option>
            <option value="March">March</option>
            <option value="April">April</option>
            <option value="May">May</option>
            <option value="June">June</option>
            <option value="July">July</option>
            <option value="August">August</option>
            <option value="September">September</option>
            <option value="October">October</option>
            <option value="November">November</option>
            <option value="December">December</option>
        </select>
        <input type="text" name="year" id="year" placeholder="Enter Year" required>
        <button type="submit">Show</button>
    </form>
    <form action="overview.php" method="get">
        <input type="text" name="year2" id="year2" placeholder="Enter Year for Statistics" required>
        <button type="submit">Show Statistics</button>
    </form>
    <?php if (isset($chart_data_json)): ?>
        <h2>Selected Month: <?php echo $selected_month . ' ' . $selected_year; ?></h2>
        <div class="chart-container">
            <canvas id="myChart"></canvas>
        </div>
        <script>
            var ctx = document.getElementById('myChart').getContext('2d');
            var chartData = <?php echo $chart_data_json; ?>;
            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: chartData.labels,
                    datasets: chartData.datasets
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    <?php endif; ?>
    <?php if (isset($yearChartDataJson)): ?>
        <h2>Statistics for Selected Year: <?php echo $selected_year2; ?></h2>
        <div class="chart-container">
            <canvas id="yearChart"></canvas>
        </div>
        <script>
            var yearChartData = <?php echo $yearChartDataJson; ?>;
            var yearChartCtx = document.getElementById('yearChart').getContext('2d');
            var yearChart = new Chart(yearChartCtx, {
                type: 'bar',
                data: {
                    labels: yearChartData.labels,
                    datasets: yearChartData.datasets
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    <?php endif; ?>

</main>
<?php include('../partials/footer.php'); ?>
</body>
</html>
