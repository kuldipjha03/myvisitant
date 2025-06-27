<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>QR Code Scanner</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      background: #f0f0f0;
      padding: 30px;
    }

    #qr-reader {
      width: 300px;
      margin: auto;
    }

    .card {
      display: none;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      margin-top: 20px;
      max-width: 400px;
      margin-left: auto;
      margin-right: auto;
    }

    .card img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
      border: 2px solid #ddd;
      margin-bottom: 10px;
    }

    .card p {
      margin: 5px;
      font-size: 16px;
    }

    .btn-stop, .btn-ok {
      margin-top: 15px;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .btn-stop {
      background: #e53935;
      color: #fff;
    }

    .btn-ok {
      background: #4CAF50;
      color: #fff;
    }

    .btn-stop:hover { background: #c62828; }
    .btn-ok:hover { background: #388e3c; }
  </style>
</head>
<body>

  <h2>QR Code Scanner</h2>
  <div id="qr-reader"></div>
  <div id="qr-result">Scan a QR code</div>

  <div class="card" id="visitorCard">
    <img src="default-user.png" id="photo" alt="Visitor Photo">
    <p><strong>Visitor ID:</strong> <span id="vDbId"></span></p>
    <p><strong>Name:</strong> <span id="vName"></span></p>
    <p><strong>Mobile:</strong> <span id="vMobile"></span></p>
    <p><strong>ID:</strong> <span id="vId"></span></p>
    <p><strong>Check-In:</strong> <span id="vCheckin"></span></p>
    <button class="btn-ok" onclick="resetScanner()">OK</button>
  </div>

  <button class="btn-stop" onclick="stopScanner()">Stop Scanner</button>

  <script>
    let scanner = new Html5QrcodeScanner("qr-reader", {
      fps: 10,
      qrbox: 250
    });

    function onScanSuccess(decodedText, decodedResult) {
      scanner.clear();

      const id = decodedText.replace("VisitorID_", "");
  console.log("Scanned Visitor ID:", id); // ✅ Log here
      fetch(`get-visitor.php?id=${id}`)
        .then(res => res.json())
        .then(visitor => {
          if (visitor.error) {
            document.getElementById("qr-result").innerText = "Visitor not found.";
            return;
          }

          document.getElementById("vDbId").innerText = visitor.id || "-";
          document.getElementById("vName").innerText = visitor.name || "-";
          document.getElementById("vMobile").innerText = visitor.mobile || "-";
          document.getElementById("vId").innerText = visitor.idcard || "-";
          document.getElementById("vCheckin").innerText = visitor.checkin || "-";
          document.getElementById("photo").src = visitor.photo || "default-user.png";

          document.getElementById("qr-result").innerText = "Visitor found!";
          document.getElementById("visitorCard").style.display = "block";
        })
        .catch(err => {
          console.error(err);
          document.getElementById("qr-result").innerText = "Error retrieving visitor.";
        });
    }

    function resetScanner() {
      document.getElementById("visitorCard").style.display = "none";
      document.getElementById("qr-result").innerText = "Scan a QR code";
      scanner.render(onScanSuccess);
    }

    function stopScanner() {
      scanner.clear().then(() => {
        document.getElementById("qr-result").innerText = "Scanner stopped.";
      }).catch(err => {
        console.error("Failed to stop scanner: ", err);
      });
    }

    scanner.render(onScanSuccess);
  </script>

</body>
</html>
