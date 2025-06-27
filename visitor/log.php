<?php
session_start();
if (!isset($_SESSION['avmsaid'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "avmsdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM tblvisitor ORDER BY ID DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Visitor Diary Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Handlee&display=swap" rel="stylesheet">
  <style>
    body {
      background-color: #fdfaf6;
      font-family: 'Handlee', cursive;
      padding: 30px;
    }

    .diary-container {
      max-width: 1000px;
      margin: auto;
      background: #fff;
      border: 2px dashed #aaa;
      padding: 25px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
      text-decoration: underline;
      color: #333;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-image: repeating-linear-gradient(to bottom, transparent, transparent 38px, #ddd 39px);
    }

    th, td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: center;
      font-size: 15px;
      background-color: rgba(255, 255, 255, 0.9);
    }

    th {
      background-color: #f0ede5;
    }

    .photo {
      width: 60px;
      height: 80px;
      object-fit: cover;
      border: 1px solid #888;
    }

    .print-btn {
      display: block;
      margin: 20px auto;
      padding: 10px 20px;
      background: #007bff;
      color: white;
      border: none;
      font-weight: bold;
      border-radius: 5px;
      cursor: pointer;
    }

    @media print {
      .print-btn {
        display: none;
      }
    }
  </style>
</head>
<body>

<div class="diary-container">
  <h2>📖 Visitor Entry Register</h2>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Date</th>
        <th>Visitor Name</th>
        <th>Mobile</th>
        <th>To Meet</th>
        <th>Reason</th>
        <th>ID Type</th>
        <th>ID Number</th>
        <th>Photo</th>
        <th>Signature</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= date("d-M-Y", strtotime($row['EnterDate'])) ?></td>
        <td><?= htmlspecialchars($row['VisitorName']) ?></td>
        <td><?= htmlspecialchars($row['MobileNumber']) ?></td>
        <td><?= htmlspecialchars($row['WhomtoMeet']) ?></td>
        <td><?= htmlspecialchars($row['ReasontoMeet']) ?></td>
        <td><?= htmlspecialchars($row['idCardType']) ?></td>
        <td><?= htmlspecialchars($row['idCardNumber']) ?></td>
        <td>
          <?php if (!empty($row['idCardImage'])): ?>
            <img src="<?= htmlspecialchars($row['idCardImage']) ?>" alt="ID" class="photo">
          <?php else: ?>
            N/A
          <?php endif; ?>
        </td>
        <td>✍️</td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <button class="print-btn" onclick="window.print()">🖨 Print Diary</button>
</div>

</body>
</html>
