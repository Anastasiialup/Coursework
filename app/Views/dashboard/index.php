<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Index</title>
    <style>
        /* CSS стилі для вигляду */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
        nav {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
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
        }
        footer {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
            width: 98.7%;
            position: relative;
            bottom: 0;
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
    <h2>Welcome to Your Financial Planner!</h2>
    <p>Here you can manage your finances effectively.</p>
    <p>You can plan your finances, view detailed information about your income and expenses, analyze your financial data through graphs and charts, manage categories, and customize your profile.</p>
    <p>Click on the tabs above to explore different sections of the dashboard.</p>
    <img src="https://eiu.nuft.edu.ua/assets/img/finance/1.jpg" alt="Моє зображення">
</main>
<?php include('../partials/footer.php'); ?>
</body>
</html>
