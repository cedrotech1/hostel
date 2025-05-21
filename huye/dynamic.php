<?php
include('../connection.php');

// Check if it's an AJAX request
if (isset($_GET['days'])) {
    $days = intval($_GET['days']);

    // Fetch visitor data for the specified number of days
    $query = "
        SELECT DATE(visit_date) AS day, SUM(visit_count) AS total_visits
        FROM visitor_count
        WHERE visit_date >= CURDATE() - INTERVAL ? DAY
        GROUP BY DATE(visit_date)
        ORDER BY DATE(visit_date) ASC
    ";

    $stmt = $connection->prepare($query);
    $stmt->bind_param('i', $days);
    $stmt->execute();
    $result = $stmt->get_result();

    $daysData = [];
    $totals = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $daysData[] = $row['day'];
            $totals[] = $row['total_visits'];
        }
    }

    // Return the data as JSON and exit
    header('Content-Type: application/json');
    echo json_encode(['days' => $daysData, 'totals' => $totals]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UR-HUYE Dashboard</title>
    <meta content="Dashboard for UR-HUYE Cards" name="description">
    <meta content="cards, statistics, dashboard" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/icon1.png" rel="icon">
    <link href="assets/img/icon1.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|
    Nunito:300,300i,400,400i,600,600i,700,700i|
    Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .card {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        select {
            padding: 5px;
            margin-right: 10px;
        }
        .chart-container {
            display: block;
        }
    </style>
</head>
<body>

<?php
    include("./includes/header.php");
    include("./includes/menu.php");
    ?>

    <main id="main" class="main">

    <!-- Days Selector -->
    <div>
        <label for="daysSelector">Select Days:</label>
        <select id="daysSelector">
            <option value="7">Last 7 Days</option>
            <option value="15">Last 15 Days</option>
            <option value="30" selected>Last 30 Days</option>
            <option value="60">Last 60 Days</option>
           
        </select>
    </div>
    <br>

    <!-- Line Chart Card -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Visitor Statistics Line Chart</h5>
            <div id="lineChart" class="chart-container"></div>
        </div>
    </div>

    <!-- Bar Chart Card -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Visitor Statistics Bar Chart</h5>
            <div id="barChart" class="chart-container"></div>
        </div>
    </div>

    <!-- Pie Chart Card -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Visitor Statistics Pie Chart</h5>
            <div id="pieChart" class="chart-container"></div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Function to fetch and render chart data
            function fetchAndRenderChart(days) {
                fetch(`?days=${days}`)
                    .then(response => {
                        if (!response.ok) throw new Error("Network response was not ok");
                        return response.json();
                    })
                    .then(data => {
                        // Clear previous charts
                        document.querySelector("#lineChart").innerHTML = "";
                        document.querySelector("#barChart").innerHTML = "";
                        document.querySelector("#pieChart").innerHTML = "";

                        // Line Chart
                        new ApexCharts(document.querySelector("#lineChart"), {
                            series: [{
                                name: "Total Visits",
                                data: data.totals
                            }],
                            chart: {
                                height: 350,
                                type: 'line',
                                zoom: {
                                    enabled: false
                                }
                            },
                            dataLabels: {
                                enabled: false
                            },
                            stroke: {
                                curve: 'smooth'
                            },
                            grid: {
                                row: {
                                    colors: ['#f3f3f3', 'transparent'],
                                    opacity: 0.5
                                }
                            },
                            xaxis: {
                                categories: data.days,
                                title: {
                                    text: 'Date'
                                }
                            },
                            yaxis: {
                                title: {
                                    text: 'Visits'
                                }
                            }
                        }).render();

                        // Bar Chart
                        new ApexCharts(document.querySelector("#barChart"), {
                            series: [{
                                name: "Total Visits",
                                data: data.totals
                            }],
                            chart: {
                                height: 350,
                                type: 'bar'
                            },
                            dataLabels: {
                                enabled: false
                            },
                            xaxis: {
                                categories: data.days,
                                title: {
                                    text: 'Date'
                                }
                            },
                            yaxis: {
                                title: {
                                    text: 'Visits'
                                }
                            }
                        }).render();

                        // Pie Chart (summarized data)
                        const totalVisits = data.totals.reduce((acc, value) => acc + value, 0);
                        const pieData = data.totals.map(value => (value / totalVisits) * 100);
                        new ApexCharts(document.querySelector("#pieChart"), {
                            series: pieData,
                            chart: {
                                height: 350,
                                type: 'pie'
                            },
                            labels: data.days,
                            title: {
                                text: 'Visit Proportions'
                            }
                        }).render();
                    })
                    .catch(error => {
                        console.error("Error fetching data:", error);
                    });
            }

            // Render charts with default data (30 days)
            fetchAndRenderChart(30);

            // Event listener for days selector
            document.querySelector("#daysSelector").addEventListener("change", (e) => {
                const selectedDays = e.target.value;
                fetchAndRenderChart(selectedDays);
            });
        });
    </script>



</main><!-- End #main -->

<!-- ======= Footer ======= -->
<?php
include("./includes/footer.php");
?>
<!-- End Footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/chart.js/chart.umd.js"></script>
<script src="assets/vendor/echarts/echarts.min.js"></script>
<script src="assets/vendor/quill/quill.min.js"></script>
<script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="assets/vendor/tinymce/tinymce.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>
</body>
</html>
