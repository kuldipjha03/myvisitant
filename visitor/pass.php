<?php
$conn = new mysqli("localhost", "root", "", "avmsdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$history = [];
$sql = "SELECT ID, VisitorName, MobileNumber, Apartment, Floor, WhomtoMeet, ReasontoMeet, remark, EnterDate, outtime, idCardType, idCardNumber, visitorPhoto FROM tblvisitor ORDER BY ID DESC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $history[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Visitor Profile Cards</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f2f2f2;
        }

        .card {
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            gap: 15px;
        }

        .photo {
            flex-shrink: 0;
        }

        .photo img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #ddd;
        }

        .details {
            flex-grow: 1;
        }

        .details p {
            margin: 4px 0;
            font-size: 14px;
        }

        .qr img {
            width: 60px;
            height: 60px;
        }
    </style>
</head>
<body>
    <h2>Visitor Cards</h2>
    <?php foreach ($history as $row): ?>
        <div class="card">
            <div class="photo">
                <img src="<?= $row['visitorPhoto'] ? htmlspecialchars($row['visitorPhoto']) : 'default-user.png' ?>" alt="Visitor Photo">
            </div>
            <div class="details">
                <p><strong>Name:</strong> <?= htmlspecialchars($row['VisitorName']) ?></p>
                <p><strong>Mobile:</strong> <?= htmlspecialchars($row['MobileNumber']) ?></p>
                <p><strong>ID Card:</strong> <?= htmlspecialchars($row['idCardType']) ?> - <?= htmlspecialchars($row['idCardNumber']) ?></p>
                <p><strong>Meet:</strong> <?= htmlspecialchars($row['WhomtoMeet']) ?></p>
            </div>
            <div class="qr">
                <img src="https://quickchart.io/qr?text=VisitorID_<?= urlencode($row['ID']) ?>&size=60" alt="QR">
            </div>
        </div>
    <?php endforeach; ?>
</body>
</html>
