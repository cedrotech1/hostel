<?php
// Enable error reporting for debugging (remove or comment out in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection
include('connection.php');
include ('./includes/auth.php');
checkUserRole(['admin','cards_manager','information_modifier']);


// Function Definitions

/**
 * Get counts grouped by a specific column.
 *
 * @param mysqli $connection Database connection object.
 * @param string $column The column name to group by.
 * @return array An array of associative arrays with 'column' and 'count'.
 */
function getCountByColumn($connection, $column) {
    $query = "SELECT `$column`, COUNT(*) as count FROM info where campus='huye' GROUP BY `$column`";
    $result = mysqli_query($connection, $query);
    $data = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    return $data;
}

/**
 * Get counts of students without images, grouped by a specific column.
 *
 * @param mysqli $connection Database connection object.
 * @param string $column The column name to group by.
 * @return array An array of associative arrays with 'column' and 'count'.
 */
function getCountWithoutImagesByColumn($connection, $column) {
    $query = "SELECT `$column`, COUNT(*) as count FROM info WHERE `picture` IS NULL OR `picture` = '' and campus='huye' GROUP BY `$column`";
    $result = mysqli_query($connection, $query);
    $data = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    return $data;
}

/**
 * Get the total number of cards.
 *
 * @param mysqli $connection Database connection object.
 * @return int Total number of cards.
 */
function getTotalCards($connection) {
    $query = "SELECT COUNT(*) as total FROM info where campus='huye'";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}

/**
 * Get the total number of students without images.
 *
 * @param mysqli $connection Database connection object.
 * @return int Total number of students without images.
 */
function getTotalWithoutImages($connection) {
    $query = "SELECT COUNT(*) as total FROM info WHERE `picture` IS NULL OR `picture` = '' and campus='huye'";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}

/**
 * Calculate the percentage.
 *
 * @param int $part Part value.
 * @param int $total Total value.
 * @return float Percentage value rounded to two decimal places.
 */
function calculatePercentage($part, $total) {
    if ($total == 0) return 0;
    return round(($part / $total) * 100, 2);
}

// Retrieve Overall Statistics
$totalCards = getTotalCards($connection);
$totalWithoutImages = getTotalWithoutImages($connection);
$totalWithoutImagesPercentage = calculatePercentage($totalWithoutImages, $totalCards);

// Retrieve Statistics by Category
$campusStats = getCountByColumn($connection, 'campus');
$collegeStats = getCountByColumn($connection, 'college');
$schoolStats = getCountByColumn($connection, 'school');
$programStats = getCountByColumn($connection, 'program');

// Retrieve Statistics for Students Without Images
$campusNoImageStats = getCountWithoutImagesByColumn($connection, 'campus');
$collegeNoImageStats = getCountWithoutImagesByColumn($connection, 'college');
$schoolNoImageStats = getCountWithoutImagesByColumn($connection, 'school');
$programNoImageStats = getCountWithoutImagesByColumn($connection, 'program');
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

    <!-- Optional: Custom styles for the dashboard -->
    <style>
        .stat-section {
            margin-bottom: 1rem;
        }

        .stat-title {
            font-weight: bold;
        }

        .percentage {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .progress {
            height: 10px;
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

                        <!-- Total Cards Card -->
                        <div class="col-12 mb-4">
                            <div class="card info-card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Cards <span>| All</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div class="ps-3">
                                            <h6><?php echo htmlspecialchars($totalCards); ?> cards</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Total Cards Card -->

                        <!-- Total Without Images Card -->
                        <div class="col-12 mb-4">
                            <div class="card info-card">
                                <div class="card-body">
                                    <h5 class="card-title">Students Without Images <span>| All</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div class="ps-3">
                                            <h6><?php echo htmlspecialchars($totalWithoutImages); ?> students</h6>
                                            <span class="percentage">(<?php echo htmlspecialchars($totalWithoutImagesPercentage); ?>%)</span>
                                        </div>
                                    </div>
                                    <div class="progress">
                                      <?php
                                      if($totalWithoutImagesPercentage>70){
                                        ?>
                                         <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo htmlspecialchars($totalWithoutImagesPercentage); ?>%;" aria-valuenow="<?php echo htmlspecialchars($totalWithoutImagesPercentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        <?php
                                      }else if(($totalWithoutImagesPercentage>50)){
                                        ?>
                                        <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo htmlspecialchars($totalWithoutImagesPercentage); ?>%;" aria-valuenow="<?php echo htmlspecialchars($totalWithoutImagesPercentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                       <?php
      
                                        }else{
                                          ?>
                                          <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo htmlspecialchars($totalWithoutImagesPercentage); ?>%;" aria-valuenow="<?php echo htmlspecialchars($totalWithoutImagesPercentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                         
                                         <?php


                                        }

                                      ?>
                                     
                                       
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Total Without Images Card -->

                        <!-- Campus Statistics Card -->
                        <div class="col-12 mb-4">
                            <div class="card info-card">
                                <div class="card-body">
                                    <h5 class="card-title">Campus Statistics</h5>
                                    <div class="stat-section">
                                        <?php foreach ($campusStats as $campus):
                                            // Find the corresponding no-image count
                                            $noImageCount = 0;
                                            foreach ($campusNoImageStats as $noImage) {
                                                if ($noImage['campus'] === $campus['campus']) {
                                                    $noImageCount = $noImage['count'];
                                                    break;
                                                }
                                            }
                                            $percentage = calculatePercentage($noImageCount, $campus['count']);
                                        ?>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <span class="stat-title"><?php echo htmlspecialchars($campus['campus']); ?></span>
                                                    <br>
                                                    <small>Total Cards: <?php echo htmlspecialchars($campus['count']); ?></small>
                                                </div>
                                                <div>
                                                    <span>Without Images: <?php echo htmlspecialchars($noImageCount); ?></span>
                                                    <br>
                                                    <span class="percentage">(<?php echo htmlspecialchars($percentage); ?>%)</span>
                                                </div>
                                            </div>
                                            <div class="progress mb-3">
                                            <?php
                                      if($percentage>70){
                                        ?>
                                         <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo htmlspecialchars($percentage); ?>%;" aria-valuenow="<?php echo htmlspecialchars($totalWithoutImagesPercentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        <?php
                                      }else if(($percentage>50)){
                                        ?>
                                        <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo htmlspecialchars($percentage); ?>%;" aria-valuenow="<?php echo htmlspecialchars($totalWithoutImagesPercentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                       <?php
      
                                        }else{
                                          ?>
                                          <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo htmlspecialchars($percentage); ?>%;" aria-valuenow="<?php echo htmlspecialchars($totalWithoutImagesPercentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                         
                                         <?php


                                        }

                                      ?>
                                     
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Campus Statistics Card -->

                        <!-- College Statistics Card -->
                        <div class="col-12 mb-4">
                            <div class="card info-card">
                                <div class="card-body">
                                    <h5 class="card-title">College Statistics</h5>
                                    <div class="stat-section">
                                        <?php foreach ($collegeStats as $college):
                                            // Find the corresponding no-image count
                                            $noImageCount = 0;
                                            foreach ($collegeNoImageStats as $noImage) {
                                                if ($noImage['college'] === $college['college']) {
                                                    $noImageCount = $noImage['count'];
                                                    break;
                                                }
                                            }
                                            $percentage = calculatePercentage($noImageCount, $college['count']);
                                        ?>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <span class="stat-title"><?php echo htmlspecialchars($college['college']); ?></span>
                                                    <br>
                                                    <small>Total Cards: <?php echo htmlspecialchars($college['count']); ?></small>
                                                </div>
                                                <div>
                                                    <span>Without Images: <?php echo htmlspecialchars($noImageCount); ?></span>
                                                    <br>
                                                    <span class="percentage">(<?php echo htmlspecialchars($percentage); ?>%)</span>
                                                </div>
                                            </div>
                                            <div class="progress mb-3">
                                            <?php
                                      if($percentage>70){
                                        ?>
                                         <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo htmlspecialchars($percentage); ?>%;" aria-valuenow="<?php echo htmlspecialchars($totalWithoutImagesPercentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        <?php
                                      }else if(($percentage>50)){
                                        ?>
                                        <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo htmlspecialchars($percentage); ?>%;" aria-valuenow="<?php echo htmlspecialchars($totalWithoutImagesPercentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                       <?php
      
                                        }else{
                                          ?>
                                          <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo htmlspecialchars($percentage); ?>%;" aria-valuenow="<?php echo htmlspecialchars($totalWithoutImagesPercentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                         
                                         <?php


                                        }

                                      ?>
                                     
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End College Statistics Card -->

                        <!-- School Statistics Card -->
                        <div class="col-12 mb-4">
                            <div class="card info-card">
                                <div class="card-body">
                                    <h5 class="card-title">School Statistics</h5>
                                    <div class="stat-section">
                                        <?php foreach ($schoolStats as $school):
                                            // Find the corresponding no-image count
                                            $noImageCount = 0;
                                            foreach ($schoolNoImageStats as $noImage) {
                                                if ($noImage['school'] === $school['school']) {
                                                    $noImageCount = $noImage['count'];
                                                    break;
                                                }
                                            }
                                            $percentage = calculatePercentage($noImageCount, $school['count']);
                                        ?>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <span class="stat-title"><?php echo htmlspecialchars($school['school']); ?></span>
                                                    <br>
                                                    <small>Total Cards: <?php echo htmlspecialchars($school['count']); ?></small>
                                                </div>
                                                <div>
                                                    <span>Without Images: <?php echo htmlspecialchars($noImageCount); ?></span>
                                                    <br>
                                                    <span class="percentage">(<?php echo htmlspecialchars($percentage); ?>%)</span>
                                                </div>
                                            </div>
                                            <div class="progress mb-3">
                                            <?php
                                      if($percentage>70){
                                        ?>
                                         <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo htmlspecialchars($percentage); ?>%;" aria-valuenow="<?php echo htmlspecialchars($totalWithoutImagesPercentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        <?php
                                      }else if(($percentage>50)){
                                        ?>
                                        <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo htmlspecialchars($percentage); ?>%;" aria-valuenow="<?php echo htmlspecialchars($totalWithoutImagesPercentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                       <?php
      
                                        }else{
                                          ?>
                                          <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo htmlspecialchars($percentage); ?>%;" aria-valuenow="<?php echo htmlspecialchars($totalWithoutImagesPercentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                         
                                         <?php


                                        }

                                      ?>
                                     
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End School Statistics Card -->

                        <!-- Program Statistics Card -->
                        <div class="col-12 mb-4">
                            <div class="card info-card">
                                <div class="card-body">
                                    <h5 class="card-title">Program Statistics</h5>
                                    <div class="stat-section">
                                        <?php foreach ($programStats as $program):
                                            // Find the corresponding no-image count
                                            $noImageCount = 0;
                                            foreach ($programNoImageStats as $noImage) {
                                                if ($noImage['program'] === $program['program']) {
                                                    $noImageCount = $noImage['count'];
                                                    break;
                                                }
                                            }
                                            $percentage = calculatePercentage($noImageCount, $program['count']);
                                        ?>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <span class="stat-title"><?php echo htmlspecialchars($program['program']); ?></span>
                                                    <br>
                                                    <small>Total Cards: <?php echo htmlspecialchars($program['count']); ?></small>
                                                </div>
                                                <div>
                                                    <span>Without Images: <?php echo htmlspecialchars($noImageCount); ?></span>
                                                    <br>
                                                    <span class="percentage">(<?php echo htmlspecialchars($percentage); ?>%)</span>
                                                </div>
                                            </div>
                                            <div class="progress mb-3">
                                            <?php
                                      if($percentage>70){
                                        ?>
                                         <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo htmlspecialchars($percentage); ?>%;" aria-valuenow="<?php echo htmlspecialchars($totalWithoutImagesPercentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        <?php
                                      }else if(($percentage>50)){
                                        ?>
                                        <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo htmlspecialchars($percentage); ?>%;" aria-valuenow="<?php echo htmlspecialchars($totalWithoutImagesPercentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                       <?php
      
                                        }else{
                                          ?>
                                          <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo htmlspecialchars($percentage); ?>%;" aria-valuenow="<?php echo htmlspecialchars($totalWithoutImagesPercentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                         
                                         <?php


                                        }

                                      ?>
                                     
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Program Statistics Card -->

                    </div>
                </div><!-- End Left side columns -->

                <!-- Right side columns -->
                <div class="col-lg-4">
                    <!-- Recent Activity -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">UR-HUYE CAMPUS <span>| Today</span></h5>
                            <img src="./icon1.png" alt="Campus Image" style="width:100%;">
                        </div>
                    </div><!-- End Recent Activity -->

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
