<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
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
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 20px;
        }
        .content {
            background-color: rgba(255, 255, 255, 0.5);
            padding: 20px;
            border-radius: 15px;
            width: 60%;
        }
        .image-container {
            width: 30%;
        }
        .image-container img {
            max-width: 92%;
            border-radius: 15px;
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

    <div class="image-container">
        <img src="https://preview.redd.it/silly-cat-v0-ket76730w05a1.jpg?width=1080&crop=smart&auto=webp&s=5a426cf6c948be50fe23fd4d262d6461c4b84b39" alt="Silly Cat">
    </div>
    <div class="content">
        <h2>About Creator</h2>
        <p>Hello! My name is Anastasiia.</p>
        <p>I am a passionate programmer from Zhytomyr Polytechnic University, specializing in both frontend and backend development. I have a strong interest in financial planning and helping others manage their finances effectively.</p>
        <p>With a background in computer science and engineering, I enjoy diving deep into code and solving complex problems. My journey in the world of programming started at university, where I discovered my love for crafting elegant solutions to real-world challenges.</p>
        <p>If you have any questions, need assistance with your projects, or would like to collaborate, feel free to reach out to me using the contact information below. I am always eager to connect with fellow developers and enthusiasts!</p>
        <div class="contact-info">
            <p><strong>Email:</strong> <a href="mailto:nastalupasina1@gmail.com">nastalupasina1@gmail.com</a></p>
            <p><strong>Instagram:</strong> <a href="https://www.instagram.com/damn_dich">@damn_dich</a></p>
            <p><strong>Telegram:</strong> <a href="https://t.me/damn_dich">Anastasiia❤️</a></p>
            <p><strong>Git:</strong> <a href="https://github.com/Anastasiialup">Anastasiia Lupashyna</a></p>
        </div>
    </div>

</main>
<?php include('../partials/footer.php'); ?>
</body>
</html>
