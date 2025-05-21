<?php
include('../connection.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>UR-HUYE</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="./icon1.png" rel="icon">
    <link href="./icon1.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


</head>

<body>

    <?php
    include("./includes/header.php");
    include("./includes/menu.php");
    ?>



    <main id="main" class="main">



        <section class="section dashboard">
            <div class="row">
                <!-- <div class="col-lg-1"></div> -->
                <!-- Left side columns -->
                <div class="col-lg-12 info-card">
                    <div class="row">

                        <div class="card">
                            <div class="card-body">
                                <br>

                                <?php

                       
                                $visitors;

                                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                    $reportType = $_POST['report_type'];
                                    $query = "";
                                    // Daily Report
                                    if ($reportType === 'daily') {
                                        $dailyDate = $_POST['daily_date'];
                                        $query = "SELECT visit_date, SUM(visit_count) as total_visits
                                        FROM visitor_count
                                        WHERE visit_date = '$dailyDate'
                                        GROUP BY visit_date";
                                        $title = "Daily Report for $dailyDate";
                                    }

                                    // Monthly Report
                                    elseif ($reportType === 'monthly') {
                                        $monthlyYear = $_POST['monthly_year'];
                                        $monthlyMonth = $_POST['monthly_month'];
                                        $query = "SELECT YEAR(visit_date) AS year, MONTH(visit_date) AS month, SUM(visit_count) as total_visits
                                        FROM visitor_count
                                        WHERE YEAR(visit_date) = $monthlyYear AND MONTH(visit_date) = $monthlyMonth
                                        GROUP BY year, month";
                                        $title = "Monthly Report for $monthlyYear-$monthlyMonth";
                                    }

                                    // Specific Date Range Report
                                    elseif ($reportType === 'range') {
                                        $startDate = $_POST['start_date'];
                                        $endDate = $_POST['end_date'];
                                        $query = "SELECT visit_date, SUM(visit_count) as total_visits
                                        FROM visitor_count
                                        WHERE visit_date BETWEEN '$startDate' AND '$endDate'
                                        GROUP BY visit_date
                                        ORDER BY visit_date";

                                        $q2 = "SELECT  SUM(visit_count) as total_visits
                                        FROM visitor_count
                                        WHERE visit_date BETWEEN '$startDate' AND '$endDate'";
                                         $result = mysqli_query($connection, $q2);
                                             while ($row = mysqli_fetch_assoc($result)) {
                                                  $visitors= $row['total_visits'];
                                             }
                                         


                                        $title = "Report from $startDate to $endDate";
                                    }

                                    if (empty($query)) {
                                        header("Location: form.php");
                                        exit();
                                    }

                                    $result = mysqli_query($connection, $query);
                                    if (!$result) {
                                        die("Error generating report: " . mysqli_error($connection));
                                    }
                                    ?>

                                    <h5 class="card-title"><?php
                                    echo $title;
                                    
                                    ?></h5>
                                    <h5>report of visitors</h5>
                                    <h5>huye website</h5>



                                    <table border='1' class="table table-striped table-hover">


                                        <?php
                                        echo "<tr>";
                                        if ($reportType === 'daily' || $reportType === 'range') {
                                            echo "<th>Date</th><th>Total Visits</th>";
                                        } elseif ($reportType === 'monthly') {
                                            echo "<th>Year</th><th>Month</th><th>Total Visits</th>";
                                        }
                                        echo "</tr>";
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<tr>";
                                                if ($reportType === 'daily' || $reportType === 'range') {
                                                    echo "<td>{$row['visit_date']}</td><td>{$row['total_visits']}</td>";
                                                } elseif ($reportType === 'monthly') {
                                                    echo "<td>{$row['year']}</td><td>{$row['month']}</td><td>{$row['total_visits']}</td>";
                                                }
                                                echo "</tr>";
                                            }
                                        } else {
                                            $colspan = ($reportType === 'monthly') ? 3 : 2;
                                            echo "<tr><td colspan='$colspan'>No visits found for the selected criteria.</td></tr>";
                                        }
                                        ?>
                                        </table>
                                        

                                        <?php
                                        if($reportType === 'range'){

                                            ?>
                                            <h3>

                                            Total visits: <?php echo $visitors ?>

                                            </h3>

                                            <?php
                                            
                                        }
                                       
                                }else{
                                    echo "<script>window.location.href='report.php'</script>";
                                }
                                ?>




                            </div>
                        </div>


                    </div>
                </div><!-- End Left side columns -->


            </div>
        </section>
        <button class="btn btn-info" onclick="downloadPDF()">Download pdf</button>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->

    <?php
    include("./includes/footer.php");
    ?>

        <!-- Load jsPDF and html2canvas -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        function loadFonts() {
            const link = document.createElement('link');
            link.href = 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap';
            link.rel = 'stylesheet';
            document.head.appendChild(link);
        }

        function downloadPDF() {
            loadFonts(); // Ensure fonts are loaded
            const { jsPDF } = window.jspdf;
            const studentCards = document.querySelectorAll('.info-card'); // Select all student cards
            const totalCards = studentCards.length;
            const cardsPerFile = 50;
            let fileCount = 1; // Keeps track of file numbering

            // Helper function to generate PDF for each set of 50 cards
            function generatePDF(cardsSubset, fileIndex) {
                const doc = new jsPDF({
                    orientation: 'portrait',
                    unit: 'mm',
                    format: [100.6, 154]
                });

                doc.setFont('Poppins', 'normal'); // Set the Poppins font

                Array.from(cardsSubset).forEach((card, index) => {
                    html2canvas(card, {
                        scale: 2,
                        useCORS: true, // Ensure cross-origin images are handled
                        backgroundColor: null
                    }).then(canvas => {
                        const imgData = canvas.toDataURL('image/jpeg', 1.0);
                        doc.addImage(imgData, 'JPEG', 0, 0, 90, 55); // Add card image

                        // Add new page if it's not the last card in this subset
                        if (index < cardsSubset.length - 1) {
                            doc.addPage();
                        }

                        // Save the PDF after processing the last card of this subset
                        if (index === cardsSubset.length - 1) {
                            doc.save(`report-${fileIndex}.pdf`);
                        }
                    });
                });
            }

            // Split student cards into sets of 50 and generate PDF for each set
            for (let i = 0; i < totalCards; i += cardsPerFile) {
                const cardsSubset = Array.from(studentCards).slice(i, i + cardsPerFile); // Select subset of 50 cards
                generatePDF(cardsSubset, fileCount); // Generate PDF for this subset
                fileCount++;
            }
        }
    </script>

    <!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

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