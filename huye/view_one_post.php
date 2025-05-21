<?php
include('../connection.php');
$id1=$_GET['pid'];
// if (!isset($id)) {
//     echo "<script>window.location.href='stockstatus.php'</script>";
//   }


    $ok1 = mysqli_query($connection, "select * from post where pid=$id1");
        while ($row = mysqli_fetch_array($ok1)) {
        $caption = $row["caption"];
        $image1 = $row["image"];
        $link1 = $row["link"];
        $link = $_POST['link'];
        $status = $row['status'];

        }




?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>HUYE</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="icon1.png" rel="icon">
  <link href="icon1.png" rel="apple-touch-icon">

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
        <!-- <div class="col-lg-1"></div> -->

        <div class="col-lg-6">
          <div class="row">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">EDIT POST FORM</h5>

              <!-- Floating Labels Form -->
              <form class="row g-3" action='post-final.php' method="post">
                <div class="col-md-12">
                <input type="text" class="form-control" id="floatingName" placeholder="product name" name='id' value="<?php echo $id1; ?>" hidden>
                  <div class="form-floating">
                  <!-- <textarea class="form-control" id="floatingName" placeholder="Caption" name="caption" rows="14"><?php echo $caption; ?></textarea> -->
                  <textarea disabled rows="8" style="width:100%" name="caption"><?php echo $caption; ?></textarea>

                    <!-- <label for="floatingName">CAPTION UPDATED..</label> -->
                  </div>
            <br>
            <?php
            if ($link1 == "") {

            }else{
                ?>
                <div class="col-md-12">
                <div class="form-floating">
                  <input disabled type="text" class="form-control" id="floatingName" placeholder="link" name='link' value="<?php echo $link1; ?>" > 
                  <label for="floatingName">Link</label>
                </div>

                <?php

            }

            ?>
                  
                <br>

                <div class="form-floating">
                  <input disabled type="text" class="form-control" id="floatingName" placeholder="link" name='link' value="<?php echo $status; ?>" > 
                  <label for="floatingName">Post status</label>
                </div>
              </div>
              <br>

              
          
                </div>

          
                <div class="text-center">
                  <button type="submit" name="saveproduct" class="btn btn-primary">save change</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form><!-- End floating Labels Form -->

            </div>
          </div>
    
   
          </div>
        </div><!-- End Left side columns -->


      </div>
    </section>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Comments</h5>

              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>
                      <b>N</b>
                    </th>
                    <th>comment.</th>
                    <th>names</th>
                    <th>dates</th>
                    <th></th>
                 
                  </tr>
                </thead>
                <tbody>


                  <?php
                  $id1=$_GET['pid'];
                  $sql = "select * from comments where pid=$id1";
                  $result = mysqli_query($connection, $sql);
                  while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <tr>
                    <td><?php echo $row['0']; ?></td>
                      <td><?php echo $row['2']; ?></td>
                      <td><?php echo $row['3']; ?></td>
                        <td><?php echo $row['4']; ?></td>
                      <td>
                        <a href="delete.php?id=<?php echo $row['cid']; ?>">
                          <button class="btn btn-danger">delete</button>
                   
                        </a>

                       
                      </td>

                    </tr>


                    <?php
                  }
                  ?>


                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
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

