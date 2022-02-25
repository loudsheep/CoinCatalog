<!-- html template -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Monety</title>
    <link rel="stylesheet" href="css/mainStyle.css?ver=<?php echo rand(10, 99999) ?>">
    <link rel="stylesheet" href="css/addStyle.css?ver=<?php echo rand(10, 99999) ?>">
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

        <div class="content">
            <form method="POST" action="addCoin.php">
            <div class="image">
                <img src="icons/upload.png" alt="add" id="add-image">
                <div class="file-name">
                    <input type="text" name="image" id="image-url" placeholder="URL grafiki"><br>
                    <input type="file" id="image-file" name="image" accept="image/png, image/jpeg">
                </div>
            </div>

            <div class="add-form">
                <!-- <form method="POST" action="addCoin.php"> -->
                    <div class="field">
                        <label>Nazwa</label>
                        <input type="text" name="name" id="name" placeholder="Nazwa monety" autocomplete="off" required>
                    </div>

                    <div class="double-field">
                        <div class="field">
                            <label>Nakład</label>
                            <input type="number" name="naklad" id="naklad" min="0" placeholder="Nakład" autocomplete="off" required>
                        </div>

                        <div class="field">
                            <label>Rok wydania</label>
                            <input type="number" name="year" id="year" max="<?php echo date("Y") ?>" placeholder="Rok wydania" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="field">
                        <label>Rant</label>
                        <input type="text" name="edge" id="edge" placeholder="Rant" autocomplete="off" required>
                    </div>

                    <div class="double-field">
                        <div class="field">
                            <label>Minimalna cena</label>
                            <input type="number" name="min-price" id="min-price" min="0" placeholder="Min. cena" autocomplete="off" required>
                        </div>

                        <div class="field">
                            <label>Cena (stan I)</label>
                            <input type="number" name="price1" id="price1" placeholder="Cena (stan I)" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="double-field">
                        <div class="field">
                            <label>Cena (stan II)</label>
                            <input type="number" name="price2" id="price2" placeholder="Cena (stan II)" autocomplete="off" required>
                        </div>

                        <div class="field">
                            <label>Cena (stan III)</label>
                            <input type="number" name="price3" id="price3" placeholder="Cena (stan III)" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="double-field">
                        <div class="field" id="type-select">
                            <label>Typ monety</label>

                            <?php
                            $sql = "SELECT DISTINCT(type) FROM info";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                echo "<select name='type'>";
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['type'] . "'>" . $row['type'] . "</option>";
                                }
                                echo "</select>";
                            }
                            ?>
                        </div>

                        <div class="field" style="display:none;" id="type-text">
                            <label>Typ monety</label>
                            <input type="text" name="type" placeholder="Typ monety" autocomplete="off">
                        </div>

                        <div class="field">
                            <label>Inna</label>
                            <input type="checkbox" name="type-checkbox" id="type-checkbox" placeholder="Typ monety" autocomplete="off">
                        </div>
                    </div>

                    <!-- submit button -->
                    <div class="field">
                        <input type="submit" value="Dodaj monetę" id="submit">
                    </div>

                <!-- </form> -->
            </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('type-checkbox').onclick = function() {
            if (document.getElementById("type-text").style.display == 'none') {
                document.getElementById("type-text").style.display = "block";
                document.getElementById("type-select").style.display = "none";
            } else {
                document.getElementById("type-select").style.display = "block";
                document.getElementById("type-text").style.display = "none";
            }
        };

        document.getElementById("image-url").addEventListener('input', showImageURL);
        document.getElementById("image-file").onchange = showImageFile;

        function showImageURL() {
            document.getElementById("add-image").src = document.getElementById("image-url").value;
        }

        function showImageFile() {
            var file = document.getElementById("image-file").files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById("add-image").src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    </script>

    <!-- close connection to database -->
    <?php
    $conn->close();
    ?>
</body>

</html>