<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Visitor Entry Record</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 20px;
    }
    .diary-entry {
      max-width: 700px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border: 2px dashed #999;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    h2 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 30px;
    }
    .entry-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 12px;
      border-bottom: 1px dashed #ddd;
      padding-bottom: 6px;
    }
    .entry-row strong {
      width: 40%;
      color: #444;
    }
    .entry-row span {
      width: 58%;
      color: #000;
    }
    .footer {
      text-align: center;
      font-size: 0.9rem;
      margin-top: 30px;
      color: #555;
    }
    .print-btn {
      display: block;
      width: 120px;
      margin: 20px auto;
      padding: 10px;
      background-color: #007bff;
      color: #fff;
      text-align: center;
      border-radius: 4px;
      text-decoration: none;
      font-weight: bold;
    }
    @media print {
      .print-btn {
        display: none;
      }
    }
  </style>
</head>
<body>

  <div class="diary-entry">
    <h2>📖 Visitor Entry Record</h2>

    <div class="entry-row">
      <strong>Visitor Name:</strong><span>Ravi Kumar</span>
    </div>
    <div class="entry-row">
      <strong>Mobile Number:</strong><span>+91-9876543210</span>
    </div>
    <div class="entry-row">
      <strong>Purpose of Visit:</strong><span>Meeting Guest - Room 203</span>
    </div>
    <div class="entry-row">
      <strong>Date:</strong><span>27 June 2025</span>
    </div>
    <div class="entry-row">
      <strong>Time In:</strong><span>02:45 PM</span>
    </div>
    <div class="entry-row">
      <strong>Time Out:</strong><span>--</span>
    </div>
    <div class="entry-row">
      <strong>ID Type:</strong><span>Aadhaar</span>
    </div>
    <div class="entry-row">
      <strong>Visitor Photo:</strong>
      <span><img src="https://via.placeholder.com/80x100" alt="Visitor Photo" style="border:1px solid #ccc;"/></span>
    </div>

    <a href="#" class="print-btn" onclick="window.print()">🖨 Print Entry</a>

    <div class="footer">
      Powered by Smart Visitor System • www.yourcompany.com
    </div>
  </div>

</body>
</html>
