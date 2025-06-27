<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "avmsdb");
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $res = $conn->query("SELECT ID, VisitorName, MobileNumber, idCardType, idCardNumber, EnterDate, visitorPhoto FROM tblvisitor WHERE ID = $id");

    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        echo json_encode([
            "id" => $row['ID'],
            "name" => $row['VisitorName'],
            "mobile" => $row['MobileNumber'],
            "idcard" => $row['idCardType'] . ' - ' . $row['idCardNumber'],
            "checkin" => $row['EnterDate'],
            "photo" => $row['visitorPhoto']
        ]);
    } else {
        echo json_encode(["error" => "Visitor not found"]);
    }
} else {
    echo json_encode(["error" => "Invalid ID"]);
}
