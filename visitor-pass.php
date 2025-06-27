<?php
session_start();
include('includes/dbconnection.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid visitor ID.";
    exit;
}

$visitorId = intval($_GET['id']);

// Fetch visitor details
$sql = "SELECT * FROM tblvisitor WHERE ID = $visitorId LIMIT 1";
$res = mysqli_query($con, $sql);

if (!$res || mysqli_num_rows($res) == 0) {
    echo "Visitor not found.";
    exit;
}

$visitor = mysqli_fetch_assoc($res);

// Count visits by this visitor's mobile number
$mobile = $visitor['MobileNumber'];
$visitCountRes = mysqli_query($con, "SELECT COUNT(*) as visitCount FROM tblvisitor WHERE MobileNumber = '$mobile'");
$visitCountRow = mysqli_fetch_assoc($visitCountRes);
$visitCount = $visitCountRow['visitCount'];

// Paths to photos
$uploadsDir = 'visitor/uploads/';
$selfiePath = !empty($visitor['selfiePhoto']) ? $uploadsDir . $visitor['selfiePhoto'] : '';
$idCardPath = !empty($visitor['idCardImage']) ? $uploadsDir . $visitor['idCardImage'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Visitor Card - <?= htmlspecialchars($visitor['VisitorName']) ?></title>
<style>
    body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 20px; }
    .pass-container {
        max-width: 500px;
        background: #fff;
        padding: 20px;
        margin: auto;
        border: 2px solid #333;
        border-radius: 8px;
    }
    .header {
        text-align: center;
        border-bottom: 2px solid #333;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    .visitor-photo {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #333;
        display: block;
        margin: 10px auto;
    }
    .visitor-info {
        text-align: center;
    }
    .visitor-info h2 {
        margin: 10px 0 5px 0;
    }
    .visitor-info p {
        margin: 3px 0;
        font-size: 16px;
    }
    .visit-count {
        font-weight: bold;
        color: #007bff;
        margin: 10px 0 20px 0;
        font-size: 18px;
    }
    .documents {
        display: flex;
        justify-content: space-around;
        margin-top: 20px;
    }
    .doc-card {
        text-align: center;
        font-size: 12px;
    }
    .doc-card img {
        max-width: 100px;
        max-height: 80px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    .doc-label {
        margin-top: 5px;
        font-weight: bold;
    }
</style>
</head>
<body>

<div class="pass-container">
    <div class="header">
        <h1>Visitor Card</h1>
    </div>
    <img src="<?= htmlspecialchars($selfiePath ?: 'visitor/uploads/default-selfie.png') ?>" alt="Visitor Photo" class="visitor-photo" />
    <div class="visitor-info">
        <h2><?= htmlspecialchars($visitor['VisitorName']) ?></h2>
        <p><strong>Date Registered:</strong> <?= date('d M Y', strtotime($visitor['EnterDate'])) ?></p>
        <p class="visit-count">Total Visits: <?= $visitCount ?></p>
    </div>

    <div class="documents">
        <div class="doc-card">
            <?php if ($idCardPath && file_exists($idCardPath)) : ?>
                <img src="<?= htmlspecialchars($idCardPath) ?>" alt="ID Card" />
                <div class="doc-label"><?= htmlspecialchars($visitor['idCardNumber'] ?: 'ID Number N/A') ?></div>
            <?php else: ?>
                <div style="font-size: 14px; color: #999;">No ID Card Uploaded</div>
            <?php endif; ?>
        </div>

        <div class="doc-card">
            <?php if ($selfiePath && file_exists($selfiePath)) : ?>
                <img src="<?= htmlspecialchars($selfiePath) ?>" alt="Selfie" />
                <div class="doc-label">Selfie Photo</div>
            <?php else: ?>
                <div style="font-size: 14px; color: #999;">No Selfie Uploaded</div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
