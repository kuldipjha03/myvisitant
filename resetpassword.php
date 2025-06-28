<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (isset($_POST['submit'])) {
    $contactno = $_SESSION['contactno'];
    $email = $_SESSION['email'];
    $password = md5($_POST['newpassword']);

    $query = mysqli_query($con, "UPDATE tbladmin SET Password='$password' WHERE Email='$email' AND MobileNumber='$contactno'");
    if ($query) {
        echo "<script>alert('Password successfully changed');</script>";
        session_destroy();
    } else {
        $msg = "Something went wrong. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - AVMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Stylesheets -->
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet">
    <link href="css/font-face.css" rel="stylesheet">

    <style>
        body {
            background: url('images/bg-login.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
        }

        .reset-box {
            max-width: 450px;
            margin: 90px auto;
            background: rgba(255, 255, 255, 0.96);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .reset-box h2 {
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

    <script>
        function checkpass() {
            const newPass = document.changepassword.newpassword.value;
            const confirmPass = document.changepassword.confirmpassword.value;

            if (newPass !== confirmPass) {
                alert('New Password and Confirm Password do not match');
                document.changepassword.confirmpassword.focus();
                return false;
            }
            return true;
        }
    </script>
</head>

<body>

<div class="container">
    <div class="reset-box">
        <h2>Reset Your Password</h2>

        <?php if ($msg) echo '<div class="error-msg">' . htmlentities($msg) . '</div>'; ?>

        <form method="post" name="changepassword" onsubmit="return checkpass();">
            <div class="form-group">
                <label><i class="fas fa-key"></i> New Password</label>
                <input type="password" class="form-control" name="newpassword" placeholder="New Password" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-lock"></i> Confirm Password</label>
                <input type="password" class="form-control" name="confirmpassword" placeholder="Confirm Password" required>
            </div>

            <button type="submit" name="submit" class="btn btn-reset btn-block">Reset Password</button>
            <a class="back-login" href="index.php">← Back to Sign In</a>
        </form>
    </div>
</div>

<!-- JS Scripts -->
<script src="vendor/jquery-3.2.1.min.js"></script>
<script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
</body>
</html>
