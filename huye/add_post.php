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
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

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


</head>

<body>

<?php  
include("./includes/header.php");
include("./includes/menu.php");
?>



<main id="main" class="main">



    <section class="section dashboard">
      <div class="row">
      <div class="col-lg-1"></div>
        <!-- Left side columns -->
        <div class="col-lg-10">
          <div class="row">

          <div class="card">
            <div class="card-body">
            <br>
              <h5 class="card-title">POST  TO USERS</h5>
              <br>

              <!-- Floating Labels Form -->
              <form class="row g-3" action='add_post.php' method="post">
            
                <div class="col-md-12">
                  <div class="form-floating">
                  <textarea rows="8" style="width:100%" name="caption"></textarea>

                  </div>
                </div>
                <div class="col-md-12">
                <div class="form-floating">
                  <input type="text" class="form-control" id="floatingName" placeholder="link" name='link'>
                  <label for="floatingName">Link</label>
                </div>
              </div>
          
                <div class="text-center">
                  <button type="submit" name="saveproduct" class="btn btn-primary">save post</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
             
              </form><!-- End floating Labels Form -->

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
<?php
if (isset($_POST['saveproduct'])) {
    $caption = $_POST['caption'];
    $link = $_POST['link'];
    $currentDateTime = date("d-m-Y");
    // $des = $_POST['des'];

    if ($caption != '') {
        // Check if the product name already exists in the database
        $caption = mysqli_real_escape_string($connection, $caption);

        $ok = mysqli_query($connection, "INSERT INTO `post`(`pid`, `image`, `caption`,`status`, `link`,`posted_at`) 
        VALUES (NULL,'','$caption','active','$link','$currentDateTime')");
        if ($ok) {
            // Get the ID of the inserted product
        
        
                echo "<script>alert('Done successfully.')</script>";
                echo "<script>window.location.href='view_posts.php'</script>";
            
        } else {
            echo "<script>alert('Failed to insert product.')</script>";
        }
        
    } else {
        echo "<script>alert('Please fill all fields.')</script>";
    }
}
?>
