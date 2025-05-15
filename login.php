<?php
session_start();

// Cek apakah pengguna sudah login
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $valid_username = "admin";
    $valid_password = "12345678";

    if ($username == $valid_username && $password == $valid_password) {
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Keuangan Masjid</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            height: 100%;
        }

        .background {
            background: url('masjid-background.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
        }

        .login-container {
            position: relative;
            z-index: 1;
            background: transparan;
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.4);
            max-width: 400px;
            width: 500%;
            text-align: center;
        }

        .login-container h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .login-container p {
            color: #666;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
            position: relative;
        }

        .form-group label {
            display: block;
            color: #333;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 90%;
            padding: 10px 35px 10px 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group .fa {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }

        .login-btn {
            width: 100%;
            padding: 10px;
            background-color: #00796b;
            color: white;
            border: none;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .login-btn:hover {
            background-color: #004d40;
        }

        .forgot-password {
            margin-top: 15px;
        }

        .forgot-password a {
            color: #00796b;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            margin-top: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="background">
        <div class="overlay"></div>
        <div class="login-container">
            <h2>Login Sistem Keuangan Masjid</h2>
            <p>Silakan masuk untuk melanjutkan</p>
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan username" required>
                    <i class="fa fa-user"></i>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                    <i class="fa fa-lock"></i>
                </div>
                <button type="submit" class="login-btn">Login</button>
                <div class="forgot-password">
                    <a href="#">Lupa Password?</a>
                </div>
            </form>
            <?php if (isset($error)) { echo "<p class='error-message'>$error</p>"; } ?>
        </div>
    </div>
</body>
</html>
