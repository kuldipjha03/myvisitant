<?php
$conn = new mysqli("localhost", "root", "", "avmsdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$history = [];
$sql = "SELECT ID, VisitorName, MobileNumber, Apartment, Floor, WhomtoMeet, ReasontoMeet, remark, EnterDate, outtime FROM tblvisitor ORDER BY ID DESC";
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
    <title>Visitor History</title>
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
            padding-bottom: 70px;
        }

        .form-container {
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #aaa;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .history-entry {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 15px;
            background: #fafafa;
        }

        .history-entry p {
            margin: 4px 0;
            font-size: 14px;
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

        .pagination {
            text-align: center;
            margin-top: 10px;
        }

        .pagination button {
            margin: 2px;
            padding: 6px 12px;
            border: 1px solid #ccc;
            background: #eee;
            cursor: pointer;
        }

        .pagination button.active {
            background: #4CAF50;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="form-container">
        <h2>Visitor History</h2>
        <input type="text" id="searchInput" oninput="filterAndPaginate()" placeholder="Search by Mobile Number">
        <div id="historyList"></div>
        <div class="pagination" id="paginationControls"></div>
    </div>

    <!-- Footer Menu -->
    <div class="footer-menu">
        <form method="POST" action="terminal.php"><button type="submit">Check-In</button></form>
        <form method="POST" action="checkout.php"><button type="submit">Check-Out</button></form>
        <form method="POST" action="history.php"><button type="submit" class="active">History</button></form>
    </div>
</div>

<script>
    const allData = <?= json_encode($history) ?>;
    let currentPage = 1;
    const entriesPerPage = 5;

    function filterAndPaginate() {
        const input = document.getElementById("searchInput").value.toLowerCase();
        const filtered = allData.filter(item =>
            item.MobileNumber.toString().toLowerCase().includes(input)
        );
        displayPage(filtered, currentPage);
        setupPagination(filtered);
    }

    function displayPage(data, page) {
        const list = document.getElementById("historyList");
        list.innerHTML = "";
        const start = (page - 1) * entriesPerPage;
        const end = start + entriesPerPage;
        const pageData = data.slice(start, end);

        pageData.forEach(row => {
            const entry = document.createElement("div");
            entry.className = "history-entry";
            entry.innerHTML = `
                <p><strong>Name:</strong> ${row.VisitorName}</p>
                <p class="mobile"><strong>Mobile:</strong> ${row.MobileNumber}</p>
                <p><strong>Flat/Floor:</strong> ${row.Apartment} / ${row.Floor}</p>
                <p><strong>Whom to Meet:</strong> ${row.WhomtoMeet}</p>
                <p><strong>Reason:</strong> ${row.ReasontoMeet}</p>
                <p><strong>Remark:</strong> ${row.remark || ''}</p>
                <p><strong>Check-In:</strong> ${row.EnterDate}</p>
                <p><strong>Check-Out:</strong> ${row.outtime ? row.outtime : '<span style="color:red">Not yet</span>'}</p>
            `;
            list.appendChild(entry);
        });
    }

    function setupPagination(data) {
        const pageCount = Math.ceil(data.length / entriesPerPage);
        const pagination = document.getElementById("paginationControls");
        pagination.innerHTML = "";

        for (let i = 1; i <= pageCount; i++) {
            const btn = document.createElement("button");
            btn.innerText = i;
            if (i === currentPage) btn.classList.add("active");
            btn.onclick = () => {
                currentPage = i;
                displayPage(data, i);
                setupPagination(data);
            };
            pagination.appendChild(btn);
        }
    }

    // Load first time
    window.onload = function () {
        filterAndPaginate();
    };
</script>
</body>
</html>
