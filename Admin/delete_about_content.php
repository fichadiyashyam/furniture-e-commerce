<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true || empty($_SESSION['role'])) {
    header("Location: ../login.php");
    exit();
}
include '../config/db_config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // fetch image to delete physically
    $img_query = "SELECT image FROM about_content WHERE id = $id";
    $img_result = mysqli_query($connection, $img_query);
    if ($img_row = mysqli_fetch_assoc($img_result)) {
        if (!empty($img_row['image'])) {
            $img_path = "../images/" . $img_row['image'];
            if (file_exists($img_path)) {
                unlink($img_path);
            }
        }
    }

    $delete_query = "DELETE FROM about_content WHERE id = $id";
    if (mysqli_query($connection, $delete_query)) {
         header("Location: about_content.php?msg=" . urlencode("Content section deleted successfully"));
    } else {
         header("Location: about_content.php?error=" . urlencode("Failed to delete content section"));
    }
} else {
    header("Location: about_content.php");
}

mysqli_close($connection);
exit();
?>
