<?php
include('../connection.php');

if (isset($_POST['saveproduct'])) {
    $caption = $_POST['caption'];
    $link = $_POST['link'];
    $status = $_POST['status'];
    $caption = $_POST['caption'];
    $pid = $_POST['id'];

    // Check if caption is not empty
    if ($caption != '') {
        // Escape special characters in the caption to prevent SQL injection and syntax errors
        $caption = mysqli_real_escape_string($connection, $caption);

        // Execute the update query
        $ok = mysqli_query($connection, "UPDATE `post` SET `caption`='$caption',`link`='$link', `status`='$status' WHERE `pid`=$pid");

        // Check if the query was successful
        if ($ok) {
            echo "<script>alert('Post updated successfully.')</script>";
            echo "<script>window.location.href='view_posts.php'</script>";
            exit(); // Stop script execution
        } else {
            echo "<script>alert('Failed to update post.')</script>";
        }
    } else {
        echo "<script>alert('Please provide a caption.')</script>";
        echo "<script>window.location.href='view_posts.php'</script>";
    }
}
?>
