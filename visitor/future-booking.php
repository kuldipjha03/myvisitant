<?php
session_start();
$conn = new mysqli("localhost", "root", "", "avmsdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success_msg = "";

function getAvailableRooms($conn, $booking_date) {
    $available_rooms = [];

    // Get all rooms
    $rooms = $conn->query("SELECT id, room_number, room_type FROM rooms");

    while ($room = $rooms->fetch_assoc()) {
        $room_id = $room['id'];

        // Check if room is booked on given date
        $check = $conn->query("
            SELECT 1 FROM room_bookings
            WHERE room_id = $room_id
            AND (
                (booking_date <= '$booking_date' AND till_date IS NULL)
                OR ('$booking_date' BETWEEN booking_date AND till_date)
            )
        ");

        if ($check->num_rows == 0) {
            $available_rooms[] = $room;
        }
    }

    return $available_rooms;
}

// Form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["book_room"])) {
    $room_id = $_POST["room_id"];
    $visitor_id = $_POST["visitor_id"];
    $booking_date = $_POST["booking_date"];
    $till_date = !empty($_POST["till_date"]) ? $_POST["till_date"] : null;

    $stmt = $conn->prepare("INSERT INTO room_bookings (visitor_id, room_id, booking_date, till_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $visitor_id, $room_id, $booking_date, $till_date);
    $stmt->execute();

    $conn->query("UPDATE rooms SET status='booked' WHERE id=$room_id");

    $success_msg = "Room successfully booked for Visitor ID: $visitor_id";
}

$selected_date = $_POST['booking_date'] ?? date('Y-m-d');
$available_rooms = getAvailableRooms($conn, $selected_date);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Room</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { margin: 0; font-family: 'Segoe UI'; background-color: #f0f2f5; display: flex; justify-content: center; }
        .wrapper { width: 100%; max-width: 400px; background: #fff; min-height: 100vh; padding-bottom: 80px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        .form-container { padding: 25px 20px 10px; }
        h2 { text-align: center; margin-bottom: 20px; color: #4CAF50; }
        label { display: block; margin-top: 15px; font-weight: 500; }
        input, select { width: 100%; padding: 10px; margin-top: 3px; border: 1px solid #ccc; border-radius: 5px; }
        button[type="submit"] { margin-top: 20px; width: 100%; background-color: #4CAF50; color: white; padding: 12px; font-size: 16px; border-radius: 5px; border: none; cursor: pointer; }
        button:hover { background-color: #45a049; }
        .success { color: green; font-weight: bold; text-align: center; margin-top: 15px; }
        .footer-menu { position: fixed; bottom: 0; left: 50%; transform: translateX(-50%); width: 100%; max-width: 400px; background: #fff; border-top: 1px solid #ccc; display: flex; justify-content: space-around; padding: 10px 0; box-shadow: 0 -2px 8px rgba(0,0,0,0.1); z-index: 1000; }
        .footer-menu button { background: none; border: none; font-size: 15px; color: #333; cursor: pointer; padding: 5px 10px; border-radius: 5px; }
        .footer-menu button.active { color: #4CAF50; font-weight: bold; }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="form-container">
        <div style="text-align: right; padding: 10px;">
            Terminal, <strong><?= htmlspecialchars($_SESSION['avmsaid']) ?></strong> |
            <a href="logout.php" style="color: red; text-decoration: none;">Logout</a>
        </div>

        <h2><i class="fas fa-bed"></i> Book Room</h2>

        <?php if (!empty($success_msg)): ?>
            <p class="success"><?= htmlspecialchars($success_msg) ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Search Visitor (ID, Name, Mobile):</label>
            <input list="visitorList" name="visitor_id" required>
            <datalist id="visitorList">
                <?php
                $visitors = $conn->query("SELECT ID, VisitorName, MobileNumber FROM tblvisitor ORDER BY ID DESC LIMIT 50");
                while ($v = $visitors->fetch_assoc()) {
                    echo "<option value='{$v['ID']}' label='ID: {$v['ID']} - {$v['VisitorName']} ({$v['MobileNumber']})'>";
                }
                ?>
            </datalist>

            <label>From Date:</label>
            <input type="date" name="booking_date" value="<?= htmlspecialchars($selected_date) ?>" min="<?= date('Y-m-d') ?>" onchange="this.form.submit()">

            <label>Till Date (Optional):</label>
            <input type="date" name="till_date" min="<?= date('Y-m-d') ?>">

            <?php if (count($available_rooms) > 0): ?>
                <label>Select Available Room:</label>
                <select name="room_id" required>
                    <option value="">Select Room</option>
                    <?php foreach ($available_rooms as $room): ?>
                        <option value="<?= $room['id'] ?>"><?= $room['room_number'] ?> - <?= $room['room_type'] ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="book_room"><i class="fas fa-check-circle"></i> Book Now</button>
            <?php else: ?>
                <div style="text-align:center; margin-top: 30px;">
                    <p style="color: red;"><i class="fas fa-triangle-exclamation"></i> No rooms available for selected date.</p>
                    <a href="book-future.php" style="color:#4CAF50; text-decoration:underline;">Book for Future</a>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <div class="footer-menu">
        <form method="POST" action="terminal.php"><button type="submit">Check-In</button></form>
        <form method="POST" action="checkout.php"><button type="submit">Check-Out</button></form>
        <form method="POST" action="history.php"><button type="submit">History</button></form>
    </div>
</div>

</body>
</html>
