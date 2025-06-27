<?php
session_start();
$conn = new mysqli("localhost", "root", "", "avmsdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function generateOTP($length = 6) {
    return rand(pow(10, $length - 1), pow(10, $length) - 1);
}

$mobile = '';
$msg = '';
$visitor = null;

// Step 1: Enter Mobile
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["check_mobile"])) {
    $mobile = $_POST["MobileNumber"];
    $_SESSION['mobile'] = $mobile;
    $otp = generateOTP();
    $_SESSION['otp'] = $otp;
    $msg = "OTP sent to $mobile (Demo OTP: $otp)";
}

// Step 2: Verify OTP
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["verify_otp"])) {
    $entered_otp = $_POST["otp"];
    if (isset($_SESSION['otp']) && $_SESSION['otp'] == $entered_otp) {
        $_SESSION['otp_verified'] = true;
        $mobile = $_SESSION['mobile'];

        $stmt = $conn->prepare("SELECT * FROM tblvisitor WHERE MobileNumber = ? AND outtime IS NULL ORDER BY ID DESC LIMIT 1");
        $stmt->bind_param("s", $mobile);
        $stmt->execute();
        $result = $stmt->get_result();
        $visitor = $result->fetch_assoc();

        if ($visitor) {
            $_SESSION['visitor_id'] = $visitor['ID'];
            $msg = "OTP verified! You can now check out.";
        } else {
            $msg = "No active check-in found. Please check-in first.";
            session_unset();
            session_destroy();
            session_start();
        }
    } else {
        $msg = "Invalid OTP. Please try again.";
    }
}

// Step 3: Final Checkout
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["checkout"])) {
    if (isset($_SESSION['visitor_id'])) {
        $checkout_time = date("Y-m-d H:i:s");
        $stmt = $conn->prepare("UPDATE tblvisitor SET outtime = ? WHERE ID = ?");
        $stmt->bind_param("si", $checkout_time, $_SESSION['visitor_id']);
        if ($stmt->execute()) {
            $msg = "Checked out successfully at $checkout_time!";
            session_unset();
            session_destroy();
            session_start();
        } else {
            $msg = "Error while checking out.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Visitor Check-Out</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f0f0f0;
            display: flex;
            justify-content: center;
        }

        .wrapper {
            width: 100%;
            max-width: 400px;
            min-height: 100vh;
            background: #fff;
            padding-bottom: 70px; /* space for footer */
        }

        .form-container {
            padding: 25px 20px 10px;
        }

        h2 {
            text-align: center;
            color: #4CAF50;
        }

        label {
            display: block;
            margin-top: 15px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background: #4CAF50;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .msg {
            text-align: center;
            font-weight: bold;
            margin-top: 15px;
            color: green;
        }

        .footer-menu {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 400px;
            background-color: #ffffff;
            border-top: 1px solid #ccc;
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 10px 0;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .footer-menu button {
            background: none;
            border: none;
            font-size: 16px;
            color: #333;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .footer-menu button.active {
            color: #4CAF50;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="form-container">
        <h2>Visitor Check Out</h2>

        <?php if (!isset($_SESSION['otp']) && !isset($_SESSION['otp_verified'])): ?>
            <form method="POST">
                <label>Enter Mobile Number:</label>
                <input type="text" name="MobileNumber" pattern="\d{10}" required>
                <button type="submit" name="check_mobile">Send OTP</button>
            </form>
        <?php elseif (isset($_SESSION['otp']) && !isset($_SESSION['otp_verified'])): ?>
            <form method="POST">
                <label>Enter OTP:</label>
                <input type="text" name="otp" required>
                <button type="submit" name="verify_otp">Verify OTP</button>
            </form>
        <?php elseif (isset($_SESSION['otp_verified']) && isset($_SESSION['visitor_id'])): ?>
            <form method="POST">
                <p>You are checked in. Proceed to check-out.</p>
                <button type="submit" name="checkout">Check-Out Now</button>
            </form>
        <?php endif; ?>

        <?php if (!empty($msg)): ?>
            <p class="msg"><?= htmlspecialchars($msg) ?></p>
        <?php endif; ?>
    </div>

    <!-- Footer Menu -->
    <div class="footer-menu">
        <form method="POST" action="terminal.php">
            <button type="submit">Check-In</button>
        </form>
        <form method="POST" action="checkout.php">
            <button type="submit" class="active">Check-Out</button>
        </form>
        <form method="POST" action="history.php">
            <button type="submit">History</button>
        </form>
    </div>
</div>
</body>
</html>
