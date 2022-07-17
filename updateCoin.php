<?php

$id = $_POST['id'];
$amount = $_POST['amount'];

if(!is_numeric($id) || !is_numeric($amount)) {
    http_response_code(508);
    // echo "Invalid ID or amount " . $id . " " . $amount;
    exit();
}

$id = intval($id);
$amount = intval($amount);


session_start();
require_once 'DB_DETAILS.php';
// error_reporting(0);
$conn = new mysqli($DB_ADDRESS, $DB_USER, $DB_PASSWORD, $DB_NAME);
if ($conn->connect_error) {
    http_response_code(5023);
    header("Location: find.php");
    exit();
}
$conn->query("SET NAMES utf8");

$sql = "UPDATE `coins` SET `sztuki` = `sztuki` + $amount WHERE `id` = $id";

$result = $conn->query($sql);
if ($result) {
    http_response_code(200);
    header("Location: find.php");
    exit();
}

$conn->close();

?>