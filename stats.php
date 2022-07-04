<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/mainStyle.css?ver=<?php echo rand(10, 99999) ?>">
    <link rel="stylesheet" href="css/chartStyle.css?ver=<?php echo rand(10, 99999) ?>">
    <script src="chart.min.js"></script>
    <title>Statistics</title>
</head>

<body>
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

        <div class="charts-row">
            <div class="canvas-container">
                <canvas id="chart1"></canvas>
                <span class="loading-span">Ładowanie...</span>
            </div>

            <div class="canvas-container">
                <canvas id="chart2"></canvas>
                <span class="loading-span">Ładowanie...</span>
            </div>

            <div class="canvas-container">
                <canvas id="chart3"></canvas>
                <span class="loading-span">Ładowanie...</span>
            </div>
        </div>


        <!-- <div class="charts-row">
            <div class="canvas-container">
                <canvas id="chart3"></canvas>
            </div>
        </div> -->

    </div>


    <script>
        function reqListener() {
            console.log(this.responseText);
        }

        var oReq = new XMLHttpRequest(); // New request object
        oReq.onload = function() {
            let json = JSON.parse(this.responseText);

            createPieChart1(json["type_count"]);
            createPieChart2(json["coin_type_count"]);
            createBarChart1(json["year_count"]);

            for(let i of document.querySelectorAll(".loading-span")) {
                i.style.display = "none";
            }
        };
        oReq.open("get", "getStats.php", true);
        oReq.send();

        let chart1 = document.getElementById("chart1").getContext('2d');
        let chart2 = document.getElementById("chart2").getContext('2d');

        function createPieChart1(json) {
            var pieChart = new Chart(chart1, {
                type: 'pie',
                data: {
                    labels: Object.keys(json),
                    datasets: [{
                        label: '# of Votes',
                        data: Object.values(json),
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Ilość kategorii monet',
                        }
                    }
                },
            });
        }

        function createPieChart2(json) {
            var pieChart = new Chart(chart2, {
                type: 'pie',
                data: {
                    labels: Object.keys(json),
                    datasets: [{
                        label: '# of Votes',
                        data: Object.values(json),
                        backgroundColor: [
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 159, 64, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Ilość monet w każdej kategorii monet',
                        }
                    }
                },
            });
        }

        function createBarChart1(json) {
            var barChart = new Chart(chart3, {
                type: 'bar',
                data: {
                    labels: Object.keys(json),
                    datasets: [{
                        data: Object.values(json),
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false,
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Ilość monet w każdym roku',
                        }
                    }
                },
            });
        }
    </script>
</body>

</html>