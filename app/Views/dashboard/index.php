<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your financial planner</title>
    <style>
        /* CSS стилі для вигляду */
        body {
            font-family: "Comic Sans MS";
            margin: 0;
            padding: 0;
            background-color: #cbbdbd;
        }
        header {
            background-color: #674f4f;
            color: #f1e9e9;
            padding: 10px;
            text-align: center;
            width: 90%;
            margin: 0 auto;
        }
        nav {
            background-color: #f4f4f4;
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
        }
        footer {
            background-color: #674f4f;
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
    <h2>Welcome to Your Financial Planner!</h2>
    <p>Here you can manage your finances effectively.</p>
    <p>You can plan your finances, view detailed information about your income and expenses, analyze your financial data through graphs and charts, manage categories, and customize your profile.</p>
    <p>Click on the tabs above to explore different sections of the dashboard.</p>
    <img src="https://eiu.nuft.edu.ua/assets/img/finance/1.jpg" alt="Моє зображення">
</main>
<?php include('../partials/footer.php'); ?>
</body>
</html>
