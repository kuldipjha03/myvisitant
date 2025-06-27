<?php
$conn = new mysqli("localhost", "root", "", "avmsdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$visitor = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT ID, VisitorName, MobileNumber, Apartment, Floor, WhomtoMeet, ReasontoMeet, remark, EnterDate, outtime, idCardType, idCardNumber, visitorPhoto FROM tblvisitor WHERE ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $visitor = $result->fetch_assoc();
    $stmt->close();
}0
?>

<!DOCTYPE html>
<html>
<head>
    <title>Visitor Profile Card</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .card {
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }

        .card img.photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #ddd;
            margin-bottom: 15px;
        }

        .card p {
            margin: 6px 0;
            font-size: 14px;
        }

        .card img.qr {
            width: 80px;
            height: 80px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<?php if ($visitor): ?>
    <div class="card">
        <img class="photo" src="<?= $visitor['visitorPhoto'] ? htmlspecialchars($visitor['visitorPhoto']) : 'default-user.png' ?>" alt="Visitor Photo">
        <p><strong>Name:</strong> <?= htmlspecialchars($visitor['VisitorName']) ?></p>
        <p><strong>Mobile:</strong> <?= htmlspecialchars($visitor['MobileNumber']) ?></p>
        <p><strong>ID Card:</strong> <?= htmlspecialchars($visitor['idCardType']) ?> - <?= htmlspecialchars($visitor['idCardNumber']) ?></p>
        <p><strong>Apartment/Floor:</strong> <?= htmlspecialchars($visitor['Apartment']) ?> / <?= htmlspecialchars($visitor['Floor']) ?></p>
        <p><strong>To Meet:</strong> <?= htmlspecialchars($visitor['WhomtoMeet']) ?></p>
        <p><strong>Reason:</strong> <?= htmlspecialchars($visitor['ReasontoMeet']) ?></p>
        <p><strong>Check-In:</strong> <?= htmlspecialchars($visitor['EnterDate']) ?></p>
        <p><strong>Check-Out:</strong> <?= $visitor['outtime'] ? htmlspecialchars($visitor['outtime']) : '<span style="color:red">Not yet</span>' ?></p>
        <img class="qr" src="https://quickchart.io/qr?text=VisitorID_<?= urlencode($visitor['ID']) ?>&size=80" alt="QR Code">
    </div>
<?php else: ?>
    <p>Visitor not found.</p>
<?php endif; ?>
</body>
</html>
