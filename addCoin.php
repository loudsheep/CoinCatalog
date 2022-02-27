<?php
    session_start();
    require_once 'DB_DETAILS.php';
    // error_reporting(0);
    $conn = new mysqli($DB_ADDRESS, $DB_USER, $DB_PASSWORD, "test");
    if ($conn->connect_error) {
        $_SESSION['error'] = "Błąd podczas łączenia z serwerem";
        header("Location: add.php");
        exit();
    }

    if(checkParam('name') || checkParam('naklad') || checkParam('year') || 
        checkParam('edge') || checkParam('min-price') || checkParam('price1') || 
        checkParam('price2') || checkParam('price3') || checkParam('type') || 
        checkParam('image')) {
        $_SESSION['error'] = "Nie wszystkie pola zostały wypełnione";
        header("Location: add.php");
        exit();
    }

    $name= $_POST['name'];
    $naklad = $_POST['naklad'];
    $year = $_POST['year'];
    $edge = $_POST['edge'];
    $min_cena = $_POST['min-price'];
    $stan1 = $_POST['price1'];
    $stan2 = $_POST['price2'];
    $stan3 = $_POST['price3'];
    $type = $_POST['type'];
    $image = $_POST['image'];


    // add coin to database
    $conn->query("SET NAMES utf8");
    $sql = "INSERT INTO `monety` (`name`, `naklad`, `edge`, `sztuki`, `type`, `stan1`, `stan2`, `stan3`, `min_cena`, `year`) 
    VALUES (?, ?, ?, 0, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissiiiii", $name, $naklad, $edge, $type, $stan1, $stan2, $stan3, $min_cena, $year);
    $stmt->execute();

    if($stmt->error) {
        $_SESSION['error'] = "Błąd podczas dodawania monet do bazy";
        header("Location: add.php");
        exit();
    }

    echo "Dodano monetę";

    // add image
    $sql = "SELECT id FROM monety ORDER BY id DESC LIMIT 1;";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $id = $row['id'];
    $img_name = "img_test/". $id . ".jpg";

    $sql = "UPDATE `monety` SET `img`=? WHERE `id`=$id";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $img_name);
    $stmt->execute();

    if($stmt->error) {
        $_SESSION['error'] = "Błąd podczas dodawania grafiki do bazy";
        header("Location: add.php");
        exit();
    }

    // download image from url
    file_put_contents($img_name, file_get_contents($image));
    echo "File downloaded!";

    function checkParam($param) {
        if (isset($_POST[$param]) && $_POST[$param] != "") {
            return false;
        } else {
            return true;
        }
    }


    $conn->close();

    header("Location: add.php");
    exit();
?>