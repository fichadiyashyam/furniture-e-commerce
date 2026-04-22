<?php 
include '../config/db_config.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = mysqli_real_escape_string($connection, $_GET['id']);
    
    // Get image name to delete file
    $img_query = "SELECT image FROM services_content WHERE id = '$id'";
    $img_result = mysqli_query($connection, $img_query);
    $img_row = mysqli_fetch_assoc($img_result);
    $image = $img_row['image'];

    $delete_query = "DELETE FROM services_content WHERE id = '$id'";
    
    if (mysqli_query($connection, $delete_query)) {
        // Delete image file if it exists and is not a default icon
        $default_icons = ['truck.svg', 'bag.svg', 'support.svg', 'return.svg'];
        if (!empty($image) && !in_array($image, $default_icons) && file_exists("../images/" . $image)) {
            unlink("../images/" . $image);
        }
        echo "<script>window.location.href='services_content.php?msg=" . urlencode("Feature deleted successfully") . "';</script>";
    } else {
        echo "<script>window.location.href='services_content.php?error=" . urlencode("Failed to delete feature: " . mysqli_error($connection)) . "';</script>";
    }
} else {
    echo "<script>window.location.href='services_content.php';</script>";
}

mysqli_close($connection);
?>
