<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset="UTF-8">
        <meta name ="viewport" content="width=device-width, initial-scale=1.0">
        <title>Splash Screen</title>
        <style>

        @font-face {
            font-family: 'Poppins';
            src: url(assets/Poppins/Poppins-Bold.ttf);
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: 'Poppins', sans-serif;
        }

        .splash-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            background-color: #3f0070;
        }

        .splash-content {
            text-align: center;
            color: #fff;
        }

        .splash-content h1 {
            font-size: 48px;
        }

        .splash-content p {
            font-size: 18px;
        }
     </style>

        <!-- Redirect to the next page after 3 seconds -->
        <script>
        setTimeout(function(){
            window.location.href = 'login.php';
        }, 3000);
    </script> 
</head>

<!-- Contains logo and title  -->
<body>
    <div class="splash-container">
        <div class="splash-content">
            <img src="assets/Southside.png" alt="Logo" width="200">
            <h1>Post Proper Southside</h1>
        </div>
    </div>
    </head>
</body>
</html>