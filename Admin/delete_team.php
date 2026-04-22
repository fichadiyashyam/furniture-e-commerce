<?php 
include '../config/db_config.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = mysqli_real_escape_string($connection, $_GET['id']);
    
    // Get image name to delete file
    $img_query = "SELECT image FROM team WHERE id = '$id'";
    $img_result = mysqli_query($connection, $img_query);
    if ($img_result && mysqli_num_rows($img_result) > 0) {
        $img_row = mysqli_fetch_assoc($img_result);
        $image = $img_row['image'];

        $delete_query = "DELETE FROM team WHERE id = '$id'";
        
        if (mysqli_query($connection, $delete_query)) {
            // Delete image file if it exists
            if (!empty($image) && file_exists("../images/" . $image)) {
                unlink("../images/" . $image);
            }
            echo "<script>window.location.href='team.php?msg=" . urlencode("Team member deleted successfully") . "';</script>";
        } else {
            echo "<script>window.location.href='team.php?error=" . urlencode("Failed to delete member: " . mysqli_error($connection)) . "';</script>";
        }
    } else {
        echo "<script>window.location.href='team.php?error=" . urlencode("Member not found.") . "';</script>";
    }
} else {
    echo "<script>window.location.href='team.php';</script>";
}

mysqli_close($connection);
?>
