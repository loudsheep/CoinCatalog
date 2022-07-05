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

        <div class="charts-row">
            <div class="canvas-container">
                <canvas id="chart4"></canvas>
                <span class="loading-span">Ładowanie...</span>
            </div>

            <div class="canvas-container">
                <canvas id="chart5"></canvas>
                <span class="loading-span">Ładowanie...</span>
            </div>

            <div class="canvas-container">
                <canvas id="chart6"></canvas>
                <span class="loading-span">Ładowanie...</span>
            </div>
        </div>

        <div class="charts-row">
            <div class="canvas-container">
                <canvas id="chart7"></canvas>
                <span class="loading-span">Ładowanie...</span>
            </div>

            <div class="canvas-container">
                <canvas id="chart8"></canvas>
                <span class="loading-span">Ładowanie...</span>
            </div>

            <div class="canvas-container">
                <canvas id="chart9"></canvas>
                <span class="loading-span">Ładowanie...</span>
            </div>
        </div>
    </div>


    <script>
        function reqListener() {
            console.log(this.responseText);
        }

        var oReq = new XMLHttpRequest(); // New request object
        oReq.onload = function() {
            let json = JSON.parse(this.responseText);

            // pie charts
            createDoughnutChart(json["type_count"], chart1, "Ilość kategorii monet");
            createDoughnutChart(json["coin_type_count"], chart2, "Ilość monet w każdej kategorii monet");
            createDoughnutChart(json["stan1_value_count"], chart4, "Ilość monet w przedziałach cen STAN I");
            createDoughnutChart(json["stan2_value_count"], chart5, "Ilość monet w przedziałach cen STAN II");
            createDoughnutChart(json["stan3_value_count"], chart6, "Ilość monet w przedziałach cen STAN III");
            createDoughnutChart(json["min_cena_value_count"], chart7, "Ilość monet w przedziałach cen MINIMALNA CENA");
            createDoughnutChart(json["edge_count"], chart8, "Ilość różnych rantów monet");
            
            // bar charts
            createBarChart(json["year_count"], chart3 ,'Ilość monet w każdym roku');

            for(let i of document.querySelectorAll(".loading-span")) {
                i.style.display = "none";
            }
        };
        oReq.open("get", "getStats.php", true);
        oReq.send();
        
        function createDoughnutChart(json, chart, title) {
            var pieChart = new Chart(chart, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(json),
                    datasets: [{
                        data: Object.values(json),
                        backgroundColor: [
                            'rgba(255, 99, 132)',
                            'rgba(54, 162, 235)',
                            'rgba(153, 102, 255)',
                            'rgba(255, 206, 86)',
                            'rgba(75, 192, 192)',
                            'rgba(255, 159, 64)',
                            'rgba(132, 99, 180)',
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
                            text: title,
                        }
                    }
                },
            });
        }

        function createBarChart(json, chart, title) {
            var barChart = new Chart(chart, {
                type: 'bar',
                data: {
                    labels: Object.keys(json),
                    datasets: [{
                        data: Object.values(json),
                        backgroundColor: [
                            'rgba(54, 162, 235)',
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
                            text: title,
                        }
                    }
                },
            });
        }
    </script>
</body>

</html>