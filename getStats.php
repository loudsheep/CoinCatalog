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


// counting how many coins are with the same stan 1,2,3 and min_cena
function checkIfInArrayAndAdd(&$arr, $key, $value)
{
    if (array_key_exists($key, $arr)) {
        $arr[$key] += $value;
    } else {
        $arr[$key] = $value;
    }
}

function addToArray(&$arr, $stan, $sztuki)
{
    if ($sztuki <= 0) {
        return;
    }

    if ($stan <= 5) {
        // $arr["0-10"] += 1;
        checkIfInArrayAndAdd($arr, "0-5", $sztuki);
    } else if ($stan <= 10) {
        // $arr["0-10"] += 1;
        checkIfInArrayAndAdd($arr, "5-10", $sztuki);
    } else if ($stan <= 20) {
        // $arr["10-20"] += 1;
        checkIfInArrayAndAdd($arr, "10-20", $sztuki);
    } else if ($stan <= 50) {
        // $arr["20-50"] += 1;
        checkIfInArrayAndAdd($arr, "20-50", $sztuki);
    } else if ($stan <= 100) {
        // $arr["50-100"] += 1;
        checkIfInArrayAndAdd($arr, "50-100", $sztuki);
    } else if ($stan <= 200) {
        // $arr["100-200"] += 1;
        checkIfInArrayAndAdd($arr, "100-200", $sztuki);
    } else if ($stan <= 300) {
        // $arr["200-300"] += 1;
        checkIfInArrayAndAdd($arr, "200-300", $sztuki);
    } else {
        // $arr["300+"] += 1;
        checkIfInArrayAndAdd($arr, "300+", $sztuki);
    }
}

$sql = "SELECT stan1,stan2,stan3,min_cena,sztuki FROM `coins` ORDER BY stan1 ASC";
$result = $conn->query($sql);
$stan1_arr = [];
$stan2_arr = [];
$stan3_arr = [];
$min_cena_arr = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        addToArray($stan1_arr, $row["stan1"], $row["sztuki"]);
        addToArray($stan2_arr, $row["stan2"], $row["sztuki"]);
        addToArray($stan3_arr, $row["stan3"], $row["sztuki"]);
        addToArray($min_cena_arr, $row["min_cena"], $row["sztuki"]);
    }
}


$sql = "SELECT edge, SUM(sztuki) AS 'sztuki' FROM coins GROUP BY edge ORDER BY sztuki DESC";
$result = $conn->query($sql);
$edge_count_arr = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $edge_count_arr[$row["edge"]] =  intval($row["sztuki"]);
    }
}





$conn->close();

// joining arrays and creating JSON
$json = [];
$json["type_count"] = $type_count_arr;
$json["coin_type_count"] = $coin_type_count_arr;
$json["year_count"] = $year_count_arr;
$json["stan1_value_count"] = $stan1_arr;
$json["stan2_value_count"] = $stan2_arr;
$json["stan3_value_count"] = $stan3_arr;
$json["min_cena_value_count"] = $min_cena_arr;
$json["edge_count"] = $edge_count_arr;
echo json_encode($json, JSON_UNESCAPED_UNICODE);