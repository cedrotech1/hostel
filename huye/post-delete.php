<?php
include('../connection.php');
$id1=$_GET['pid'];
if (!isset($id1)) {
    echo "<script>window.location.href='view_posts.php'</script>";
  }


  
            $ok=mysqli_query($connection,"delete from post where pid='$id1'");
            if($ok){
                echo "<script>alert('deleted')</script>";
                echo "<script>window.location.href='view_posts.php'</script>";
            }
    



?>