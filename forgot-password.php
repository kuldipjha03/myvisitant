<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (isset($_POST['submit'])) {
    $contactno = $_POST['contactno'];
    $email = $_POST['email'];

    $query = mysqli_query($con, "SELECT ID FROM tbladmin WHERE Email='$email' AND MobileNumber='$contactno'");
    $ret = mysqli_fetch_array($query);
    if ($ret > 0) {
        $_SESSION['contactno'] = $contactno;
        $_SESSION['email'] = $email;
        header('location:resetpassword.php');
    } else {
        $msg = "Invalid details. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AVMS - Forgot Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts & CSS -->
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet">
    <link href="css/font-face.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('images/bg-login.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
        }

        .wrapper {
            max-width: 450px;
            margin: 90px auto;
            background: rgba(255, 255, 255, 0.96);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .wrapper h2 {
            color: #007bff;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 6px;
        }

        .btn-reset {
            background-color: #007bff;
            color: white;
            border-radius: 6px;
        }

        .btn-reset:hover {
            background-color: #0056b3;
        }

        .error-msg {
            color: red;
            text-align: center;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .back-login {
            display: block;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="wrapper">
        <h2>Forgot Password</h2>

        <?php if ($msg) echo '<div class="error-msg">' . htmlentities($msg) . '</div>'; ?>

        <form method="post">
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email Address</label>
                <input type="email" class="form-control" name="email" placeholder="Enter email" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-phone"></i> Mobile Number</label>
                <input type="text" class="form-control" name="contactno" placeholder="Enter mobile number" required>
            </div>

            <button type="submit" name="submit" class="btn btn-reset btn-block">Reset Password</button>
            <a class="back-login" href="index.php">← Back to Sign In</a>
        </form>
    </div>
</div>

<!-- Scripts -->
<script src="vendor/jquery-3.2.1.min.js"></script>
<script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
</body>
</html>
