<?php

session_start();
require_once 'DB_DETAILS.php';
// error_reporting(0);
$conn = new mysqli($DB_ADDRESS, $DB_USER, $DB_PASSWORD, $DB_NAME);
if ($conn->connect_error) {
    $_SESSION['error'] = "Błąd podczas łączenia z serwerem";
    header("Location: add.php");
    exit();
}


$conn->query("SET NAMES utf8");
$sql = "SELECT type,COUNT(*) as count FROM coins GROUP BY type ORDER BY count DESC";

$result = $conn->query($sql);
$arr = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $arr[$row["type"]] =  intval($row["count"]);
    }
}

$sql = "SELECT type, SUM(sztuki) AS 'sztuki' FROM coins GROUP BY type ORDER BY sztuki DESC";
$result = $conn->query($sql);
$arr2 = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $arr2[$row["type"]] =  intval($row["sztuki"]);
    }
}

echo json_encode([$arr, $arr2], JSON_UNESCAPED_UNICODE);


$conn->close();
