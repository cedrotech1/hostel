<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Statistics - Pie Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="col-lg-6">
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
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $dbname = 'huye'; // Replace with your database name

    $conn = new mysqli($host, $user, $password, $dbname, 3307); // Ensure correct port number

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch visitor data grouped by month
    $query = "SELECT DATE_FORMAT(visit_date, '%Y-%m') AS month, SUM(visit_count) AS total_visits
              FROM visitor_count
              GROUP BY DATE_FORMAT(visit_date, '%Y-%m')
              ORDER BY DATE_FORMAT(visit_date, '%Y-%m') ASC";

    $result = $conn->query($query);

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

    $conn->close();
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

</body>
</html>
