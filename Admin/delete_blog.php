<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true || empty($_SESSION['role'])) {
    header("Location: ../login.php");
    exit();
}
include '../config/db_config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $img_query = mysqli_query($connection, "SELECT image FROM blogs WHERE id = $id");
    if ($img_row = mysqli_fetch_assoc($img_query)) {
        if (!empty($img_row['image'])) {
            $path = "../images/" . $img_row['image'];
            // Only delete if it's an uploaded file (not a static asset like post-1.jpg)
            if (file_exists($path) && strpos($img_row['image'], 'post-') === false) {
                unlink($path);
            }
        }
    }

    if (mysqli_query($connection, "DELETE FROM blogs WHERE id = $id")) {
        header("Location: blogs.php?msg=" . urlencode("Blog post deleted successfully"));
    } else {
        header("Location: blogs.php?error=" . urlencode("Failed to delete post"));
    }
} else {
    header("Location: blogs.php");
}

mysqli_close($connection);
exit();
?>
