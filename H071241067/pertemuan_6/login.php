<?php 
session_start();

if(isset($_SESSION['username'])){
    header('Location: halaman_utama.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #6B5FC7 0%, #8B7FD8 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #anu {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 380px;
        }

        h2 {
            text-align: center;
            color: #6B5FC7;
            margin-bottom: 30px;
            font-size: 28px;
        }

        /* Error Message - DI ATAS FORM */
        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
            border: 1px solid #ef5350;
        }

        #aku, #kamu {
            position: static;
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: #555;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
        }

        input:focus {
            border-color: #6B5FC7;
            box-shadow: 0 0 0 3px rgba(107, 95, 199, 0.1);
        }

        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #6B5FC7 0%, #8B7FD8 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            margin-top: 10px;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(107, 95, 199, 0.4);
        }

        button:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <form action="login_logic.php" method="post" id="anu">
        <h2>Login</h2>

        <!-- Error Message - MUNCUL DI ATAS USERNAME -->
        <?php if(isset($_GET['error'])){ ?>
        <div class="error-message">
            Username atau password salah!
        </div>
        <?php } ?>

        <div id="aku">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" placeholder="Masukkan username" required>
        </div>
        <div id="kamu">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="Masukkan password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</body>
</html>