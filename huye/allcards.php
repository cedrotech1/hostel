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
                <div class="col-lg-12">
                    <div class="row">

                        <div class="card">
                            <div class="card-body">
                                <br>
                                <h5 class="card-title">LIST OF ALL CARDS</h5>

                                <div class="col-md-12 table-responsive">
                                    <table class="table datatable table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <b>#</b>
                                                </th>

                                                <th><b>Reg Number</b></th>
                                                <th><b>Campus</b></th>
                                                <th><b>College</b></th>
                                                <th><b>Names</b></th>
                                                <th><b>School</b></th>
                                                <th><b>Program</b></th>
                                                <th><b>Year</b></th>
                                                <th><b>image</b></th>
                                                <th><b>Modify</b></th>



                                            </tr>
                                        </thead>
                                        <tbody>



                                            <?php
                                            $ok = mysqli_query($connection, "SELECT * FROM `info` ORDER BY regnumber DESC");
                                            $i = 0;
                                            while ($row = mysqli_fetch_array($ok)) {
                                                $i++;
                                                ?>

                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $row['regnumber']; ?></td>
                                                    <td><?php echo $row['campus']; ?></td>
                                                    <td><?php echo $row['college']; ?></td>
                                                    <td><?php echo $row['names']; ?></td>
                                                    <td><?php echo $row['school']; ?></td>
                                                    <td><?php echo $row['program']; ?></td>
                                                    <td><?php echo $row['yearofstudy']; ?></td>
                                                    <td>
                                                        <?php 
                                                        if ($row['picture']) {

                                                            if(!$row['status']){
                                                                ?>
                                                                yes
                                                                <?php

                                                            }else{
                                                                ?>
                                                                <p style="color:red">rejected</p>
                                                                <?php
                                                            }
                                                            
                                                           
                                                        } else {
                                                            ?>
                                                            no
                                                            <?php
                                                        } 
                                                        ?>
                                                    </td>



                                                    <td>

                                                        <!-- <button type="button" class="btn btn-success"><i
                                                                class="bi bi-check-circle"></i></button>
                                                        <button type="button" class="btn btn-danger"><i
                                                                class="bi bi-exclamation-octagon"></i></button> -->

                                                        <div class="row">
                                                            <div class="col-3">
                                                                <a href="#"
                                                                    onclick="confirmDelete(<?php echo $row['regnumber']; ?>, '<?php echo $row['names']; ?>')">
                                                                    <button class="btn" data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" title="Delete">
                                                                        <i class="fas fa-trash text-danger"></i>
                                                                    </button>
                                                                </a>

                                                                <script>
                                                                    function confirmDelete(regnumber, names) {
                                                                        if (confirm('Are you sure you want to delete the product ' + names + '?')) {
                                                                            window.location.href = 'card-delete.php?regnumber=' + regnumber;
                                                                        } else {
                                                                            // Do nothing or handle cancellation
                                                                        }
                                                                    }
                                                                </script>

                                                            </div>
                                                       
                                                            <div class="col-3">
                                                                <a
                                                                    href="view_cards.php?regnumber=<?php echo $row['regnumber']; ?>">
                                                                    <button class="btn" data-bs-toggle="tooltip"
                                                                        data-bs-placement="bottom" title="View"><i
                                                                            class="fas fa-eye text-success"></i>
                                                                    </button></a>
                                                            </div>
                                                        </div>





                                                        <!-- <button type="button" class="btn btn-warning"><i class="bi bi-exclamation-triangle"></i></button>
              <button type="button" class="btn btn-info"><i class="bi bi-info-circle"></i></button> -->

                                                    </td>



                                                </tr>
                                                <?php
                                            }

                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                    </div>
                </div><!-- End Left side columns -->


            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->

    <?php
    include("./includes/footer.php");
    ?>

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