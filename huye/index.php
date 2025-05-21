<?php
// Enable error reporting for debugging (remove or comment out in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection
include('../connection.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

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

    <!-- Optional: Include Chart.js for visual charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .col-lg-12 {
            margin-top: 20px;
        }
        .card {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background: #fff;
        }
        .card-body {
            padding: 20px;
        }
        #lineChart {
            max-width: 100%;
            margin: 0 auto;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>

<body>

    <?php
    include("./includes/header.php");
    include("./includes/menu.php");
    ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Dashboard</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">
                    <!-- Left side columns -->
        <div class="col-lg-8">
          <div class="row">

          <?php
        //   include('dynamic.php');

          ?>


          <div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Visitor Statistics Line Charts (one for last 7 days) other for each nonth per month
                <a href="./dynamic.php">click here to view more...</a>
            </h5>
            <div id="lineChart"></div>
        </div>
    </div>
</div>

<!-- Customers Card -->
<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Visitor Statistics Line Chart</h5>
            <div id="lineChart"></div>
        </div>
    </div>
</div>

<?php
// Adjust SQL query for the latest 30 days
$query = "
    SELECT DATE(visit_date) AS day, SUM(visit_count) AS total_visits
    FROM visitor_count
    WHERE visit_date >= CURDATE() - INTERVAL 7 DAY
    GROUP BY DATE(visit_date)
    ORDER BY DATE(visit_date) ASC
";

$result = $connection->query($query);

$days = [];
$totals = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $days[] = $row['day'];
        $totals[] = $row['total_visits'];
    }
} else {
    echo "No results found.";
}
?>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Prepare data from PHP
        const days = <?php echo json_encode($days); ?>;
        const totals = <?php echo json_encode($totals); ?>;

        // Create the line chart using ApexCharts
        new ApexCharts(document.querySelector("#lineChart"), {
            series: [{
                name: "Total Visits",
                data: totals
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
                    colors: ['#f3f3f3', 'transparent'], // alternating row colors
                    opacity: 0.5
                }
            },
            xaxis: {
                categories: days,
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
    });
</script>


            <!-- Sales Card -->
            <div class="col-xxl-4 col-md-12">
              <div class="card info-card sales-card">





              

       

                <div class="card-body">
                  <h5 class="card-title">Monthly Visitor Statistics <span>| in bar chart</span></h5>

                
    <canvas id="visitorChart" width="400" height="200"></canvas>

    <?php
  

    // Fetch visitor data grouped by month
    $query = "SELECT DATE_FORMAT(visit_date, '%Y-%m') AS month, SUM(visit_count) AS total_visits
              FROM visitor_count
              GROUP BY DATE_FORMAT(visit_date, '%Y-%m')
              ORDER BY DATE_FORMAT(visit_date, '%Y-%m') ASC";

    $result = $connection->query($query);

    $months = [];
    $totals = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $months[] = $row['month'];
            $totals[] = $row['total_visits'];
        }
    }

    // $connection->close();
    ?>

    <script>
        $(document).ready(function () {
            // Prepare data from PHP
            const months = <?php echo json_encode($months); ?>;
            const totals = <?php echo json_encode($totals); ?>;

            // Create the chart
            const ctx = document.getElementById('visitorChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Total Visits',
                        data: totals,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
                </div>

              </div>
            </div><!-- End Sales Card -->

   

            <!-- Customers Card -->
            <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Visitor Statistics Line Chart</h5>
                <div id="lineChart"></div>
            </div>
        </div>
    </div>

    <?php
    // Database connecti

  // Fetch visitor data grouped by month
$query = "SELECT DATE_FORMAT(visit_date, '%Y-%m') AS month, SUM(visit_count) AS total_visits
FROM visitor_count
GROUP BY DATE_FORMAT(visit_date, '%Y-%m')
ORDER BY DATE_FORMAT(visit_date, '%Y-%m') ASC";

$result = $connection->query($query);

$months = [];
$totals = [];

if ($result->num_rows > 0) {
while ($row = $result->fetch_assoc()) {
$months[] = $row['month'];
$totals[] = $row['total_visits'];
}
} else {
echo "No results found.";
}

// Close the connection
// $connection->close();
    ?>




<div class="col-lg-12">
<div class="card">
            <div class="card-body">
                <h5 class="card-title">Visitor Statistics by Month (Pie Chart)</h5>
                <!-- Pie Chart -->
                <div id="pieChart"></div>
            </div>
        </div>
    </div>

    <?php
    // Database connection

    // Fetch visitor data grouped by month
    $query = "SELECT DATE_FORMAT(visit_date, '%Y-%m') AS month, SUM(visit_count) AS total_visits
              FROM visitor_count
              GROUP BY DATE_FORMAT(visit_date, '%Y-%m')
              ORDER BY DATE_FORMAT(visit_date, '%Y-%m') ASC";

    $result = $connection->query($query);

    // Prepare data for the chart
    $months = [];
    $totals = [];

    // Fetch data from the database and populate months and totals arrays
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $months[] = date("M Y", strtotime($row['month'])); // Format to display month in text
            $totals[] = (int)$row['total_visits']; // Convert to integer for ApexCharts
        }
    } else {
        $months[] = 'No data'; // Default label if no data is found
        $totals[] = 0; // Default value if no data is found
    }

    // $connection->close();
    ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Data from PHP
            const months = <?php echo json_encode($months); ?>;
            const totals = <?php echo json_encode($totals); ?>;

            // Log to console to verify the data
            console.log("Months:", months);
            console.log("Totals:", totals);

            // Create the pie chart using ApexCharts
            if (months.length > 0 && totals.length > 0) {
                new ApexCharts(document.querySelector("#pieChart"), {
                    series: totals,
                    chart: {
                        height: 350,
                        type: 'pie',
                        toolbar: {
                            show: true
                        }
                    },
                    labels: months
                }).render();
            } else {
                console.log("Data is invalid or empty. Pie chart will not render.");
            }
        });
    </script>




           

        
          </div>
        </div><!-- End Left side columns -->


    
                <!-- Right side columns -->
                <div class="col-lg-4">
                    <!-- Recent Activity -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">UR-HUYE CAMPUS STATISTICS <span>| Today</span></h5>
                            <img src="./icon1.png" alt="Campus Image" style="width:100%;">
                        </div>
                    </div><!-- End Recent Activity -->

                    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Visitor Count by Month</h5>
                <table>
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Total Visitors</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Database connection
                     
                        // Fetch visitor data grouped by month
                        $query = "SELECT DATE_FORMAT(visit_date, '%Y-%m') AS month, SUM(visit_count) AS total_visits
                                  FROM visitor_count
                                  GROUP BY DATE_FORMAT(visit_date, '%Y-%m')
                                  ORDER BY DATE_FORMAT(visit_date, '%Y-%m') ASC";

                        $result = $connection->query($query);

                        // Display data in the table
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['month'] . "</td>";
                                echo "<td>" . $row['total_visits'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='2'>No data available</td></tr>";
                        }

                        // $connection->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Prepare data from PHP
            const months = <?php echo json_encode($months); ?>;
            const totals = <?php echo json_encode($totals); ?>;

            // Create the line chart using ApexCharts
            new ApexCharts(document.querySelector("#lineChart"), {
                series: [{
                    name: "Total Visits",
                    data: totals
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
                        colors: ['#f3f3f3', 'transparent'], // alternating row colors
                        opacity: 0.5
                    }
                },
                xaxis: {
                    categories: months,
                }
            }).render();
        });
    </script>
   

                    <!-- Additional Right Side Cards can be added here -->
                </div><!-- End Right side columns -->
                

            </div>
        </section>

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
