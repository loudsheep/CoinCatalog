<!-- html template -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Monety</title>
    <link rel="stylesheet" href="css/mainStyle.css?ver=<?php echo rand(111, 999) ?>">
    <link rel="stylesheet" href="css/indexStyle.css?ver=<?php echo rand(111, 999) ?>">
</head>

<body>

    <?php
    require_once 'DB_DETAILS.php';

    error_reporting(0);
    $conn = @new mysqli($DB_ADDRESS, $DB_USER, $DB_PASSWORD, $DB_NAME);
    if ($conn->connect_error) {
        echo "<span style='font-size:32px; width:150px; margin-left:45%;'>Błąd podczas łączenia z serwerem</span>";
        exit();
    }
    $conn->query("SET NAMES utf8");
    ?>

    <div class="container">
        <div class="header">
            <h1>Baza monet</h1>

            <div class="menu">
                <a href="index.php">Strona główna</a>
                <a href="add.php">Dodaj monetę</a>
                <a href="find.php">Znajdź monety</a>
                <a href="about.php">O projekcie</a>
            </div>
        </div>

        <form class="searchBox" action="find.php" , method="POST">
            <input name="name" type="text" placeholder="Nazwa monety">
            <?php
            $sql = "SELECT MIN(`rok`) AS `minimum` FROM `spis`";
            $result = $conn->query($sql);
            $min = 0;
            if ($result->num_rows > 0) {
                $min = $result->fetch_assoc()["minimum"];
            }
            ?>
            <input name="amount" type="number" min="0" placeholder="Ilość monet">
            <input name="year" type="number" min=<?php echo $min ?> max=<?php echo date("Y") ?> placeholder="Rok wydania">

            <!-- generate selection and options from database -->
            <?php
            $sql = "SELECT DISTINCT type FROM spis";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<select name='type'>";
                echo "<option value>Typ monety</option>";
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["type"] . "'>" . $row["type"] . "</option>";
                }
                echo "</select>";
            } else {
                echo "Brak wyników";
            }
            ?>

            <input type="hidden" id="order" name="order" value="">
            <input type="hidden" id="view" name="view" value="list">
            <input type="submit" value="Szukaj">
        </form>

        <div class="content">
        </div>
        <div class="footer">
        </div>
    </div>
</body>

</html>