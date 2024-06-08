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
            background-image: url('https://static.wixstatic.com/media/aafcc4_88e3f58195dc4a2d8c0a9f9e26b18984~mv2.png/v1/fill/w_1264,h_713,al_c,q_90,usm_0.66_1.00_0.01,enc_auto/Picsart_24-06-08_16-51-27-432.png');
        }
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
            width: 90%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            flex: 1;
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
        .gallery {
            display: grid;
            width: 440px; /* збільшена ширина */
            margin-top: 20px; /* відстань зверху від текстового контейнера */
        }
        .gallery > img {
            grid-area: 1/1;
            width: 100%;
            aspect-ratio: 1;
            object-fit: cover;
            border: 10px solid #f2f2f2;
            box-shadow: 0 0 4px #0007;
            animation: slide 6s infinite;
        }
        .gallery > img:last-child {
            animation-name: slide-last;
        }
        .gallery > img:nth-child(2) { animation-delay: -2s; }
        .gallery > img:nth-child(3) { animation-delay: -4s; }
        @keyframes slide {
            0%     {transform: translateX(0%)  ;z-index: 2;}
            16.66% {transform: translateX(120%);z-index: 2;}
            16.67% {transform: translateX(120%);z-index: 1;}
            33.34% {transform: translateX(0%)  ;z-index: 1;}
            66.33% {transform: translateX(0%)  ;z-index: 1;}
            66.34% {transform: translateX(0%)  ;z-index: 2;}
            100%   {transform: translateX(0%)  ;z-index: 2;}
        }
        @keyframes slide-last {
            0%     {transform: translateX(0%)  ;z-index: 2;}
            16.66% {transform: translateX(120%);z-index: 2;}
            16.67% {transform: translateX(120%);z-index: 1;}
            33.34% {transform: translateX(0%)  ;z-index: 1;}
            83.33% {transform: translateX(0%)  ;z-index: 1;}
            83.34% {transform: translateX(0%)  ;z-index: 2;}
            100%   {transform: translateX(0%)  ;z-index: 2;}
        }
        .text-container {
            background-color: rgba(255, 255, 255, 0.5); /* напівпрозорий фон */
            padding: 20px;
            border-radius: 10px;
            text-align: left; /* вирівнювання тексту зліва */
            margin: 20px 0;
            margin-left: 90px; /* відстань зліва */
        }
    </style>
</head>
<body>
<?php include('../partials/header.php'); ?>
<main>
    <div class="text-container">
        <h2>Welcome to Your Financial Planner!</h2>
        <p>Here you can manage your finances effectively.</p>
        <p>You can plan your finances, view detailed information about your income and expenses, analyze your financial data through graphs and charts, manage categories, and customize your profile.</p>
        <p>Click on the tabs above to explore different sections of the dashboard.</p>
    </div>
    <div class="gallery">
        <img src="https://kartinki.pics/uploads/posts/2021-03/thumbs/1616072299_47-p-finansi-krasivie-foto-51.jpg" alt="a lovely kiss in the night">
        <img src="https://kartinki.pics/uploads/posts/2021-03/1616072235_5-p-finansi-krasivie-foto-7.jpg" alt="a women inside a car">
        <img src="https://balthazar.club/uploads/posts/2023-01/1674323043_balthazar-club-p-finansi-estetika-oboi-8.jpg" alt="a baby">
    </div>
</main>
<?php include('../partials/footer.php'); ?>
</body>
</html>
