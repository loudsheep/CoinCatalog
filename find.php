<!-- html template -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Monety</title>
    <link rel="stylesheet" href="css/mainStyle.css?ver=<?php echo rand(10, 99999) ?>">
    <link rel="stylesheet" href="css/findStyle.css?ver=<?php echo rand(10, 99999) ?>">

    <script>
        function order(order) {
            document.getElementById("order").value = order;
            document.getElementsByClassName("searchBox")[0].submit();
            console.log(order);
        }
    </script>
</head>

<body>

    <?php
    require_once 'DB_DETAILS.php';

    error_reporting(0);
    $conn = @new mysqli($DB_ADDRESS, $DB_USER, $DB_PASSWORD, $DB_NAME);
    if ($conn->connect_error) {
        echo "<span style='font-size:32px; width:150px; margin-left:45%;'>Błąd podczas łączenia z serwerem" . $conn->connect_error . "</span>";;
        exit();
    }
    $conn->query("SET NAMES utf8");

    if (isset($_POST["view"])) {
        // if ($_POST["view"] == "tiles") {
        //     $view = "tiles";
        // } else if ($_POST["view"] == "list") {
        //     $view = "list";
        // }
        $view = $_POST["view"];
    } else {
        $view = "list";
    }
    ?>

    <div class="container">
        <div class="header">
            <h1>Baza monet</h1>

            <div class="menu">
                <a href="index.php">Strona główna</a>
                <a href="add.php">Dodaj monetę</a>
                <a href="find.php">Znajdź monety</a>
                <a href="stats.php">Statystyki</a>
            </div>
        </div>

        <form class="searchBox" method="post" action="find.php">
            <input name="name" type="text" placeholder="Nazwa monety" value=<?php echo $_POST['name'] ?>>

            <?php
            $sql = "SELECT MIN(`year`) AS `minimum` FROM `info`";
            $result = $conn->query($sql);
            $min = 0;
            if ($result->num_rows > 0) {
                $min = $result->fetch_assoc()["minimum"];
            }
            ?>
            <input name="amount" type="number" min="0" placeholder="Ilość monet" value=<?php echo $_POST['amount'] ?>>
            <input name="year" type="number" min=<?php echo $min ?> max=<?php echo date("Y") ?> placeholder="Rok wydania" value=<?php echo $_POST['year'] ?>>

            <!-- generate selection and options from database -->
            <?php
            $sql = "SELECT DISTINCT type FROM coins";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<select name='type' value='" . $_POST['amount'] . "'>";
                echo "<option value>Typ monety</option>";
                while ($row = $result->fetch_assoc()) {
                    if ($_POST["type"] == $row["type"]) {
                        echo "<option value='" . $row["type"] . "' selected>" . $row["type"] . "</option>";
                    } else {
                        echo "<option value='" . $row["type"] . "'>" . $row["type"] . "</option>";
                    }
                }
                echo "</select>";
            } else {
                echo "Brak wyników";
            }
            ?>

            <input type="hidden" id="order" name="order" value="">
            <!-- <input type="hidden" id="view" name="view" value="tiles"> -->
            <input type="submit" value="Szukaj">

            <input type="radio" name="view" id="tile-view" value="tiles" <?php if ($view == "tiles") echo "checked"; ?>>
            <img src="icons/tile_view.png" alt="" class="view-icons" for="tile-view">

            <input type="radio" name="view" id="list-view" value="list" <?php if ($view == "list") echo "checked"; ?>>
            <img src="icons/list_view.png" alt="" class="view-icons" for="list-view">

        </form>

        <div class="content">
            <!-- if any of "name", "amount", "type" or "year" post variables are set then search data base using only those which are set -->
            <?php

            $sql = "SELECT * FROM coins WHERE ";
            $nameSet = isset($_POST["name"]) && $_POST["name"] != "";
            $amountSet = isset($_POST["amount"]) && $_POST["amount"] != "";
            $typeSet = isset($_POST["type"]) && $_POST["type"] != "";
            $yearSet = isset($_POST["year"]) && $_POST["year"] != "";

            if ($nameSet) {
                $sql .= "`name` LIKE '%" . $_POST["name"] . "%'";
            }

            if ($amountSet) {
                if ($nameSet) {
                    $sql .= " AND ";
                }
                $sql .= "`sztuki` = " . $_POST["amount"];
            }

            if ($typeSet) {
                if ($nameSet || $amountSet) {
                    $sql .= " AND ";
                }
                $sql .= "`type` = '" . $_POST["type"] . "'";
            }

            if ($yearSet) {
                if ($nameSet || $amountSet || $typeSet) {
                    $sql .= " AND ";
                }
                $sql .= "`year` = " . $_POST["year"];
            }

            if (isset($_POST["order"]) && $_POST["order"] != "") {
                $sql .= " ORDER BY `" . $_POST["order"] . "`";
                if ($_POST["order"] == "year" || $_POST["order"] == "sztuki") {
                    $sql .= " DESC";
                }
            }

            if (!$nameSet && !$amountSet && !$typeSet && !$yearSet) {
                $sql = "SELECT * FROM coins";
                if (isset($_POST["order"]) && $_POST["order"] != "") {
                    $sql .= " ORDER BY `" . $_POST["order"] . "`";
                    if ($_POST["order"] == "year" || $_POST["order"] == "sztuki") {
                        $sql .= " DESC";
                    }
                }
            }

            $result = $conn->query($sql);
            if ($result->num_rows > 0) {

                // echo $result->num_rows . " znaleziono";

                if ($view == "tiles") {
                    $i = 0;
                    echo "<div id='tiles'>";
                    while ($row = $result->fetch_assoc()) {
                        $i++;
                        tile($row, $i);
                    }
                    echo "</div>";
                } else {
                    echo "<table class='monety-table'>";
                    echo "<tr class='darker-row'>";
                    echo "<th onClick=order('id')>lp.</th>";
                    echo "<th class='td-justify' onClick=order('name')>Nazwa</th>";
                    echo "<th onClick=order('sztuki')>Ilość</th>";
                    echo "<th onClick=order('type')>Typ</th>";
                    echo "<th onClick=order('year')>Rok</th>";
                    echo "</tr>";
                    $i = 0;
                    while ($row = $result->fetch_assoc()) {
                        $i++;
                        list_row($row, $i);
                    }
                    echo "</table>";
                }
            } else {
                echo "Brak wyników";
            }

            function list_row($row, $i)
            {
                if ($i % 2 == 0) {
                    echo "<tr class='darker-row'>";
                } else {
                    echo "<tr>";
                }
                echo "<td>" . $i . "</td>";
                echo "<td class='td-justify'>" . $row["name"] . "</td>";
                echo "<td>" . $row["sztuki"] . "</td>";
                echo "<td>" . $row["type"] . "</td>";
                echo "<td>" . $row["year"] . "</td>";
                echo "</tr>";
            }

            function tile($row)
            {
                echo "<div class='tile dropdown'>";
                echo "<div class='tile-content'>";
                echo "<div class='tile-image'>";
                if ($row["img"] == "" || !file_exists($row["img"])) {
                    echo "<img src='icons/not_found.jpg'/>";
                } else {
                    echo "<img src='" . $row["img"] . "'/>";
                }
                echo "</div>";
                echo "<div class='tile-title'>" . $row["name"] . "</div>";
                echo "<div class='tile-subtitle'>" . $row["type"] . "</div>";
                echo "<div class='tile-subtitle'>" . $row["year"] . "</div>";

                echo "<div class='dropdown-content'>";
                // echo "<p>Ilość: " . $row["sztuki"] . "</p>";
                // echo "<p>Typ: " . $row["type"] . "</p>";
                echo "<table>";
                echo "<tr><td class='td-left'>Ilość:</td><td class='td-right'>" . $row["sztuki"] . "</td>";
                echo "<tr><td class='td-left'>Rok:</td><td class='td-right'>" . $row["year"] . "</td>";
                echo "<tr><td class='td-left'>Typ:</td><td class='td-right'>" . $row["type"] . "</td>";
                echo "<tr><td class='td-left'>Rant:</td><td class='td-right'>" . $row["edge"] . "</td>";
                echo "<tr><td class='td-left'>Nakład:</td><td class='td-right'>" . $row["naklad"] . "</td>";
                echo "</table>";

                echo "</div>";

                echo "</div>";
                // echo "<div class='dropdown'>HELLO WORLD</div>";
                echo "</div>";
            }

            $conn->close();

            ?>
        </div>
    </div>

    <!-- popup coin -->
    <div class="form-popup" id="coinForm">
        
    </div>
</body>

</html>