<?php
include('../connection.php');
$id=$_GET['id'];

// echo''.$id.'';
$sql = "DELETE FROM comments WHERE cid='$id'";
$result = mysqli_query($connection, $sql);
if($result){
    // echo"<script>window.location.href('viewproduct.php')</script>";
    echo "<script>window.location.href = 'view_posts.php';</script>";

}
?>