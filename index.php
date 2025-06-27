<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (isset($_POST['login'])) {
    $adminuser = $_POST['username'];
    $password = md5($_POST['password']);
    $query = mysqli_query($con, "SELECT ID FROM tbladmin WHERE UserName='$adminuser' && Password='$password'");
    $ret = mysqli_fetch_array($query);
    if ($ret > 0) {
        $_SESSION['avmsaid'] = $ret['ID'];
        header('location:dashboard.php');
    } else {
        $msg = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AVMS Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts and Styles -->
    <link href="css/font-face.css" rel="stylesheet">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet">
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet">
    <link href="css/theme.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            background: url("images/bg-login.jpg") no-repeat center center fixed;
            background-size: cover;
        }
        .login-wrap {
            max-width: 420px;
            margin: 60px auto;
            background: rgba(255, 255, 255, 0.97);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.15);
        }
        .login-logo h2 {
            color: #007bff;
            font-weight: 600;
            font-size: 22px;
            margin-bottom: 20px;
        }
        .login-form input {
            border-radius: 6px !important;
        }
        .login-form .btn {
            border-radius: 6px;
        }
        .login-error {
            font-size: 15px;
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body class="animsition">
    <div class="container">
        <div class="login-wrap">
            <div class="login-logo text-center">
                <h2>AVMS - Admin Login</h2>
            </div>

            <?php if ($msg) { echo '<div class="login-error">' . htmlentities($msg) . '</div>'; } ?>

            <div class="login-form">
                <form action="" method="post" name="login">
                    <div class="form-group">
                        <label>User Name</label>
                        <input class="form-control" type="text" name="username" placeholder="Enter Username" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input class="form-control" type="password" name="password" placeholder="Enter Password" required>
                    </div>
                    <div class="form-group text-right">
                        <a href="forgot-password.php">Forgot Password?</a>
                    </div>
                    <button class="btn btn-primary btn-block" type="submit" name="login">Sign In</button>
                </form>
            </div>
        </div>
    </div>

    <!-- JS Scripts -->
    <script src="vendor/jquery-3.2.1.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
</body>
</html>
