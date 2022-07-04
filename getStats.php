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


// counting how many rows in DB are with the same type
$result = $conn->query($sql);
$type_count_arr = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $type_count_arr[$row["type"]] =  intval($row["count"]);
    }
}

// counting how many coins are with the same type
$sql = "SELECT type, SUM(sztuki) AS 'sztuki' FROM coins GROUP BY type ORDER BY sztuki DESC";
$result = $conn->query($sql);
$coin_type_count_arr = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $coin_type_count_arr[$row["type"]] =  intval($row["sztuki"]);
    }
}

// counting how many coins are with the same year
$sql = "SELECT year, SUM(sztuki) AS 'sztuki' FROM coins GROUP BY year ORDER BY sztuki DESC";
$result = $conn->query($sql);
$year_count_arr = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $year_count_arr[$row["year"]] =  intval($row["sztuki"]);
    }
}
foreach ($year_count_arr as $key => $value) {
    if ($value == 0) {
        unset($year_count_arr[$key]);
    }
}




// joining arrays and creating JSON
$json = [];
$json["type_count"] = $type_count_arr;
$json["coin_type_count"] = $coin_type_count_arr;
$json["year_count"] = $year_count_arr;
echo json_encode($json, JSON_UNESCAPED_UNICODE);


$conn->close();
