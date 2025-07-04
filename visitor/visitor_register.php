<?php
session_start();

// DB connection
$conn = new mysqli("localhost", "root", "", "avmsdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Helper: Generate OTP
function generateOTP($length = 6) {
    return rand(pow(10, $length - 1), pow(10, $length) - 1);
}

$mobile = '';
$visitor = null;
$msg = '';
$qr_image_url = '';
$pass_data = '';

// Step 1: Submit Mobile Number
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
        $stmt = $conn->prepare("SELECT * FROM tblvisitor WHERE MobileNumber = ? ORDER BY ID DESC LIMIT 1");
        $stmt->bind_param("s", $mobile);
        $stmt->execute();
        $result = $stmt->get_result();
        $visitor = $result->fetch_assoc();
        $msg = "OTP verified successfully!";
    } else {
        $msg = "Invalid OTP. Please try again.";
    }
}

// Step 3: Resend OTP
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["resend_otp"])) {
    $mobile = $_SESSION['mobile'];
    $otp = generateOTP();
    $_SESSION['otp'] = $otp;
    $msg = "OTP resent to $mobile (Demo OTP: $otp)";
}

// Step 4: Reset
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reset_process"])) {
    session_unset();
    session_destroy();
    session_start();
    $msg = "Registration has been reset.";
}

// Step 5: Final Registration with optional ID card
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_form"])) {
    $stmt = $conn->prepare("INSERT INTO tblvisitor 
        (categoryName, VisitorName, MobileNumber, Address, Apartment, Floor, WhomtoMeet, ReasontoMeet, remark, idCardType, idCardNumber, idCardImage)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Handle ID card fields
    $idCardType = $_POST['idCardType'] ?? '';
    $idCardNumber = $_POST['idCardNumber'] ?? '';
    $idCardImage = '';

    if (isset($_FILES['idCardImage']) && $_FILES['idCardImage']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $filename = time() . '_' . basename($_FILES['idCardImage']['name']);
        $targetPath = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['idCardImage']['tmp_name'], $targetPath)) {
            $idCardImage = $targetPath;
        }
    }

    // Handle Selfie Photo upload
$selfiePhoto = '';

if (!empty($_POST['hasSelfiePhoto'])) {
    if (isset($_FILES['selfiePhoto']) && $_FILES['selfiePhoto']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/selfies/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $filename = time() . '_' . basename($_FILES['selfiePhoto']['name']);
        $targetPath = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['selfiePhoto']['tmp_name'], $targetPath)) {
            $selfiePhoto = $targetPath;
        }
    }
}

    $stmt->bind_param("ssisssssssss",
        $_POST['categoryName'],
        $_POST['VisitorName'],
        $_POST['MobileNumber'],
        $_POST['Address'],
        $_POST['Apartment'],
        $_POST['Floor'],
        $_POST['WhomtoMeet'],
        $_POST['ReasontoMeet'],
        $_POST['remark'],
        $idCardType,
        $idCardNumber,
        $idCardImage
    );

    if ($stmt->execute()) {
        $visitor_id = $stmt->insert_id;
        $pass_data = "Visitor Pass ID: " . $visitor_id;
        $encoded_data = urlencode($pass_data);
        $qr_image_url = "https://quickchart.io/qr?text=$encoded_data&size=200";
        $msg = "Visitor registered successfully! Pass ID: $visitor_id";
        session_unset();
        session_destroy();
        session_start();
    } else {
        $msg = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Visitor Self Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-container {
            background-color: #fff;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 500px;
        }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input[type="text"],
        input[type="number"],
        select {
            width: 100%; padding: 8px; margin-top: 5px;
            border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;
        }
        input[type="file"] { margin-top: 5px; }
        button {
            margin-top: 15px; padding: 10px 15px; background-color: #4CAF50;
            color: white; border: none; border-radius: 4px; cursor: pointer; width: 100%;
        }
        button:hover { background-color: #45a049; }
        .success { color: green; text-align: center; margin-top: 10px; }
        .qr-section { text-align: center; margin-top: 20px; }
        .qr-section img { width: 200px; height: 200px; }
        form + form { margin-top: 10px; }
        .button-group { display: flex; gap: 10px; margin-top: 10px; }
        .button-group form { flex: 1; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Visitor Registration</h2>

    <!-- Step 1: Mobile -->
    <?php if (!isset($_SESSION['otp']) && !isset($_SESSION['otp_verified'])): ?>
        <form method="POST">
            <label>Enter Mobile Number:</label>
            <input type="text" name="MobileNumber" value="<?= htmlspecialchars($mobile) ?>" required>
            <button type="submit" name="check_mobile">Send OTP</button>
        </form>
    <?php endif; ?>

    <!-- Step 2: OTP -->
    <?php if (isset($_SESSION['otp']) && !isset($_SESSION['otp_verified'])): ?>
        <form method="POST">
            <label>Enter OTP:</label>
            <input type="text" name="otp" required>
            <button type="submit" name="verify_otp">Verify OTP</button>
        </form>

        <div class="button-group">
            <form method="POST"><button type="submit" name="resend_otp">Resend OTP</button></form>
            <form method="POST"><button type="submit" name="reset_process">Reset</button></form>
        </div>
    <?php endif; ?>

    <!-- Step 3: Registration Form -->
    <?php if (isset($_SESSION['otp_verified']) && $_SESSION['otp_verified']): ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="MobileNumber" value="<?= htmlspecialchars($_SESSION['mobile']) ?>">

            <label>Category Name:</label>
            <input type="text" name="categoryName" value="<?= $visitor['categoryName'] ?? '' ?>" required>

            <label>Visitor Name:</label>
            <input type="text" name="VisitorName" value="<?= $visitor['VisitorName'] ?? '' ?>" required>

            <label>Address:</label>
            <input type="text" name="Address" value="<?= $visitor['Address'] ?? '' ?>" required>

            <label>Apartment:</label>
            <input type="text" name="Apartment" value="<?= $visitor['Apartment'] ?? '' ?>" required>

            <label>Floor:</label>
            <input type="text" name="Floor" value="<?= $visitor['Floor'] ?? '' ?>" required>

            <label>Whom to Meet:</label>
            <input type="text" name="WhomtoMeet" value="<?= $visitor['WhomtoMeet'] ?? '' ?>" required>

            <label>Reason to Meet:</label>
            <input type="text" name="ReasontoMeet" value="<?= $visitor['ReasontoMeet'] ?? '' ?>" required>

            <label>Remark:</label>
            <input type="text" name="remark" value="<?= $visitor['remark'] ?? '' ?>">

            <!-- ID Card Option -->
            <label><input type="checkbox" id="hasIDCard" name="hasIDCard" onchange="toggleIDCardSection()"> Provide ID Card?</label>

            <div id="idCardSection" style="display:none;">
                <label for="idCardType">ID Card Type:</label>
                <select name="idCardType" id="idCardType">
                    <option value="">Select ID Type</option>
                    <option value="Aadhar">Aadhar</option>
                    <option value="Passport">Passport</option>
                    <option value="Driving License">Driving License</option>
                    <option value="Voter ID">Voter ID</option>
                </select>

                <label for="idCardNumber">ID Card Number:</label>
                <input type="text" name="idCardNumber" id="idCardNumber">

                <label for="idCardImage">Upload ID Card Photo:</label>
                <input type="file" name="idCardImage" id="idCardImage" accept="image/*">
            </div>

            <!-- Selfie Photo Option -->
<label><input type="checkbox" id="hasSelfiePhoto" name="hasSelfiePhoto" onchange="toggleSelfieSection()"> Provide Selfie Photo?</label>

<div id="selfieSection" style="display:none;">
    <label for="selfiePhoto">Upload Selfie Photo:</label>
    <input type="file" name="selfiePhoto" id="selfiePhoto" accept="image/*">
</div>




            <button type="submit" name="submit_form">Submit</button>
        </form>
    <?php endif; ?>

    <?php if (!empty($msg)): ?>
        <p class="success"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>

    <?php if (!empty($qr_image_url)): ?>
        <div class="qr-section">
            <h3>Your Visitor QR Pass</h3>
            <img src="<?= $qr_image_url ?>" alt="QR Code">
            <p><?= htmlspecialchars($pass_data) ?></p>
            <a href="<?= $qr_image_url ?>" download="visitor-pass.png">Download QR</a>
        </div>
    <?php endif; ?>
</div>

<script>
function toggleIDCardSection() {
    const checkbox = document.getElementById("hasIDCard");
    const section = document.getElementById("idCardSection");
    section.style.display = checkbox.checked ? "block" : "none";
}
</script>

<script>
function toggleSelfieSection() {
    const checkbox = document.getElementById("hasSelfiePhoto");
    const section = document.getElementById("selfieSection");

    if (checkbox.checked) {
        section.style.display = "block";
        document.getElementById("selfiePhoto").required = true;
    } else {
        section.style.display = "none";
        document.getElementById("selfiePhoto").required = false;
        document.getElementById("selfiePhoto").value = "";
    }
}
</script>

</body>
</html>
