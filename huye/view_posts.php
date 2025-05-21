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
  <link href="assets/img/icon1.png" rel="icon">
  <link href="assets/img/icon1.png" rel="apple-touch-icon">

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
  include ("./includes/header.php");
  include ("./includes/menu.php");
  ?>



  <main id="main" class="main">



    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">


            <!-- <div class="col-xxl-3 col-md-4">
              <div class="card info-card">
                <div class="card-body">
                  <br>
                  <img src="assets/img/sp.png" class="card-img-top" alt="...">
                  <div class="card-body">
                    <p class="card-title">product name</p>
                    <p class="card-text">description</p>
                      <div class="ps-1">
                      <h6>0 quantities</h6>
                      <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">quantity</span>

                    </div>
                  </div>
                </div>

              </div>
            </div> -->

            <div class="col-lg-12">
              <div class="row">
                <?php
                $query = "SELECT * from post";
                $result = mysqli_query($connection, $query);
                if (mysqli_num_rows($result) > 0) {
                  // Loop through each product
                  while ($row = mysqli_fetch_assoc($result)) {

                    ?>

                    <div class="col-xxl-4 col-md-6">
                      <a href="view_one_post.php?pid=<?php echo $row['pid'] ?>">
                        <div class="card info-card">
                          <div class="card-body">
                            <br>
                            <?php if (!empty($row['image'])): ?>
                              <img src="<?php echo $row['image']; ?>" class="card-img-top"
                                alt="<?php echo $row['caption']; ?>" style='height:auto;width:100%'>
                            <?php endif; ?>
                            <div class="card-body">

                              <p class="card-text"  style="color:black;padding:0.3cm;background-color:whitesmoke"><?php echo $row['caption']; ?></p>
                              <?php if (!empty($row['link'])) { ?>
                         <br> <a href="<?php echo htmlspecialchars($row['link']); ?>"><?php echo htmlspecialchars($row['link']); ?></a>
                        <?php } ?>
                        <p class="card-text" 
                        style="color:black; padding:0.0cm; 
                                color:<?php 
                                    echo ($row['status'] === 'active') ? 'blue' : 
                                        (($row['status'] === 'disactive') ? 'red' : 'whitesmoke'); 
                                ?>;">
                          Notice Status---- <?php echo htmlspecialchars($row['status']); ?>
                      </p>

                              <div class="ps-1">
                                
                              <p class="card-text" style="color:black">posted: <?php echo $row['posted_at']; ?></p>

                                <div class="row" style='background-color:#f6f9ff;margin-top:0.2cm'>
                                <div class="col-4">
                                <a href="post-update.php?pid=<?php echo $row['pid']; ?>">
                                    <button class="btn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
                                        <i class="fas fa-edit text-info"></i>
                                    </button>
                                </a>
                            </div>
                           
                                  <div class="col-4">
                                    <a href="post-delete.php?pid=<?php echo $row['pid'] ?>" >
                                     
                                      <button class="btn" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                        title="Delete">
                                        <i class="fas fa-trash text-danger"></i>
                                      </button>
                                    </a>
                                   
                                  </div>

                                  <div class="col-4">
                                    <a href="view_one_post.php?pid=<?php echo $row['pid'] ?>" >
                                     
                                      <button class="btn" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                        title="view one post">
                                        <i class="fas fa-eye text-primary"></i>
                                      </button>
                                    </a>
                                   
                                  </div>

                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </a>
                    </div><!-- End Sales Card -->

                    <?php
                  }
                } else {
                  // If no products found
                  echo '<p>No posts found</p>';
                }
                ?>
              </div>
            </div>





          </div><!-- End Customers Card -->


        </div>
      </div><!-- End Left side columns -->


      <!-- </div> -->
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->

  <?php
  include ("./includes/footer.php");
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