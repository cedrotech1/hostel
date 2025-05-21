<?php
include ('connection.php');
$idd= intval($_GET['id']);
if (!isset($idd)) {
  echo "<script>window.location.href='add_user.php'</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title> OGB GARAGE - user</title>
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


  <style>
    ul li {
      list-style: none;
    }
  </style>

</head>

<body>

  <?php
  include ("./includes/header.php");
  include ("./includes/menu.php");
  ?>

  <main id="main" class="main">

    <section class="section dashboard">
      <div class="row">
        <!-- User card -->
        <?php
        $query = "SELECT * FROM users WHERE id = $idd";
        $result = mysqli_query($connection, $query);

        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="col-xxl-4 col-md-5">
              <div class="card info-card">
                <div class="card-body">
                  <!-- User details -->
                  <br>
                  <img src="./assets/img/av.png" class="card-img-top" style='height:auto;width:100%'>
                  <div class="card-body">
                    <p class="card-title"><?php echo $row['names']; ?></p>
                    <p class="card-text"><?php echo $row['email']; ?></p>
                    <!-- Display user's privileges -->
                    <?php
                    $query = "SELECT * FROM privilages WHERE uid = $idd";
                    $privilege_result = mysqli_query($connection, $query);
                    if (mysqli_num_rows($privilege_result) > 0) {
                      echo "<p>User's privileges:</p>";
                      echo "<ul>";
                      while ($privilege_row = mysqli_fetch_assoc($privilege_result)) {
                        // echo "<li>" . $privilege_row['title'] . "</li>";
                        echo '<span class="badge bg-light text-dark me-1">' . $privilege_row['title'] . '</span>';
                      }
                      echo "</ul>";
                    }
                    ?>
                    <div class="ps-1">
                      <div class="row" style='background-color:#f6f9ff;margin-top:0.2cm'>
                        <!-- Button to delete the user -->
                        <div class="col-8">
                          <a href="user-delete.php?userId=<?php echo $row['id']; ?>">
                            <button class="btn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete">
                              <i class="fas fa-trash text-danger"></i>
                            </button>
                          </a>
                        </div>
                
                        <!-- Button to activate or deactivate the user -->
                        <div class="col-4">
                          <?php
                          // Check if the user is active or inactive and display the appropriate action button
                          if ($row['active'] == 1) {
                            // User is active, display the deactivate button
                            echo '<button class="btn " onclick="confirmDeactivation(' . $row['id'] . ', \'' . $row['names'] . '\')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Deactivate">';
                            echo '<i class="fas fa-toggle-on text-primary"></i>';
                            echo '</button>';
                          } else {
                            // User is inactive, display the activate button
                            echo '<button class="btn" onclick="confirmActivation(' . $row['id'] . ', \'' . $row['names'] . '\')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Activate">';
                            echo '<i class="fas fa-toggle-off text-success"></i>';
                            echo '</button>';
                          }
                          ?>
                        </div>
                      </div>

                      <script>
                        // Function to confirm deactivation
                        function confirmDeactivation(userId, userName) {
                          if (confirm('Are you sure you want to deactivate the user ' + userName + '?')) {
                            window.location.href = 'user-deactivate.php?userId=' + userId;
                          } else {
                            // Do nothing or handle cancellation
                          }
                        }

                        // Function to confirm activation
                        function confirmActivation(userId, userName) {
                          if (confirm('Are you sure you want to activate the user ' + userName + '?')) {
                            window.location.href = 'user-activate.php?userId=' + userId;
                          } else {
                            // Do nothing or handle cancellation
                          }
                        }
                      </script>

                    </div>
                  </div>
                </div>
              </div>
            </div>
            <?php
          }
        } else {
          echo '<p>No users found</p>';
        }
        ?>

        <!-- Update user privileges form -->
        <div class="col-lg-6">
          <div class="row">
            <div class="card">
              <div class="card-body">
                <br>
                <h5 class="card-title">UPDATE USER PRIVILEGE</h5>
                <?php
                $privileges = [
                  "Dashboard",
                  "Add Product",
                  "View Product",
                  "Stock Recorded",
                  "Add User",
                  "Messages",
                  "Report",   
                ];


                ?>
                <!-- User Form -->
                <form class="row g-3" action='one-user.php' method="post">
                  <div class="col-md-12">
                    <!-- Checkboxes for user privileges -->
                    <input type="text" name="idd" value='<?php echo $idd; ?>' hidden>
                    <?php foreach ($privileges as $privilege): ?>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="privilege[]"
                          id="privilege_<?php echo $privilege; ?>" value="<?php echo $privilege; ?>">
                        <label class="form-check-label" for="privilege_<?php echo $privilege; ?>">
                          <?php echo $privilege; ?>
                        </label>
                      </div>
                    <?php endforeach; ?>
                  </div>
                  <br>
                  <div class="text-center">
                    <button type="submit" name="saveuser" class="btn btn-primary">Save User Privilege</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                  </div>
                </form>

                <!-- End User Form -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php
  include ("./includes/footer.php");
  ?>

  <script src="assets/js/main.js"></script>

</body>

</html>

<?php
// Save user privileges
if (isset($_POST['saveuser'])) {
  $idd = $_POST['idd'];
  if (isset($_POST['privilege'])) {
    $privileges = $_POST['privilege'];
    // Get existing privileges for the user
    $existing_privileges_query = "SELECT * FROM privilages WHERE uid = $idd";
    $existing_privileges_result = mysqli_query($connection, $existing_privileges_query);
    $existing_privileges = [];
    // Store existing privileges in an array
    while ($row = mysqli_fetch_assoc($existing_privileges_result)) {
      $existing_privileges[] = $row['title'];
    }

    foreach ($privileges as $privilege) {
      // Check if privilege is already assigned to the user
      if (in_array($privilege, $existing_privileges)) {
        // If privilege is already assigned, remove it
        $remove_query = "DELETE FROM privilages WHERE uid = $idd AND title = '$privilege'";
        mysqli_query($connection, $remove_query);
      } else {
        // If privilege doesn't exist, save it
        $save_query = "INSERT INTO privilages (uid, title) VALUES ($idd, '$privilege')";
        mysqli_query($connection, $save_query);
      }
    }
  }
  // Redirect back to add_user.php
  echo "<script>window.location.href='add_user.php'</script>";
}
?>