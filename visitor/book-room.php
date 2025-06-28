<?php
session_start();
$conn = new mysqli("localhost", "root", "", "avmsdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$visitor_id = $_GET['visitor_id'] ?? null;
$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["book_room"])) {
    $room_id = $_POST["room_id"];
    $visitor_id = $_POST["visitor_id"];
    $booking_date = date("Y-m-d");

    // Update room status to booked
    $conn->query("UPDATE rooms SET status='booked' WHERE id=$room_id");

    // Insert into room_bookings table
    $stmt = $conn->prepare("INSERT INTO room_bookings (visitor_id, room_id, booking_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $visitor_id, $room_id, $booking_date);
    $stmt->execute();

    $success_msg = "Room successfully booked for Visitor ID: $visitor_id";
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Book Room</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
        }

        .wrapper {
            width: 100%;
            max-width: 400px;
            background: #fff;
            min-height: 100vh;
            padding-bottom: 80px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .form-container {
            padding: 25px 20px 10px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #4CAF50;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 500;
        }

        input[type="text"], select {
            width: 100%;
            padding: 10px;
            margin-top: 3px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button[type="submit"] {
            margin-top: 20px;
            width: 100%;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .success {
            color: green;
            font-weight: bold;
            text-align: center;
            margin-top: 15px;
        }

        .footer-menu {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 400px;
            background-color: #fff;
            border-top: 1px solid #ccc;
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 10px 0;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .footer-menu button {
            background: none;
            border: none;
            font-size: 15px;
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
        <div style="text-align: right; padding: 10px;">
            Terminal, <strong><?= htmlspecialchars($_SESSION['avmsaid']) ?></strong>
            | <a href="logout.php" style="color: red; text-decoration: none;">Logout</a>
        </div>

        <h2><i class="fas fa-bed"></i> Book Room</h2>

        <?php if (!empty($success_msg)): ?>
            <p class="success"><?= htmlspecialchars($success_msg) ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Visitor ID:</label>
            <input type="text" name="visitor_id" value="<?= htmlspecialchars($visitor_id) ?>" readonly>

            <label>Booking Date:</label>
            <input type="text" name="booking_date" value="<?= date("Y-m-d") ?>" readonly>

            <label>Select Available Room:</label>
            <select name="room_id" required>
                <option value="">Select Room</option>
                <?php
                $room_res = $conn->query("SELECT id, room_number, room_type FROM rooms WHERE status='available'");
                while ($room = $room_res->fetch_assoc()) {
                    echo "<option value='{$room['id']}'>{$room['room_number']} - {$room['room_type']}</option>";
                }
                ?>
            </select>

            <button type="submit" name="book_room"><i class="fas fa-check-circle"></i> Book Now</button>
        </form>
    </div>

    <!-- Sticky Footer Menu -->
    <div class="footer-menu">
        <form method="POST" action="terminal.php">
            <button type="submit">Check-In</button>
        </form>
        <form method="POST" action="checkout.php">
            <button type="submit">Check-Out</button>
        </form>
        <form method="POST" action="history.php">
            <button type="submit">History</button>
        </form>
    </div>
</div>

</body>
</html>
