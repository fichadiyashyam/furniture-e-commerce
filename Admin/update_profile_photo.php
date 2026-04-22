<?php
session_start();
require_once '../config/db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin_profile.php');
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$userId = $_SESSION['user_id'];

if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = dirname(__DIR__) . '/public/images/profile_photo/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5 MB

    $fileType = mime_content_type($_FILES['profile_photo']['tmp_name']);
    $fileSize = $_FILES['profile_photo']['size'];
    $fileExt = strtolower(pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION));

    if (!in_array($fileType, $allowedTypes)) {
        $_SESSION['profile_errors'] = ["Profile photo must be a JPG, PNG, GIF, or WEBP image."];
    } elseif ($fileSize > $maxSize) {
        $_SESSION['profile_errors'] = ["Profile photo must be smaller than 5 MB."];
    } else {
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $newFileName = uniqid('photo_', true) . '.' . $fileExt;
        $destinationPath = $uploadDir . $newFileName;

        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $destinationPath)) {
            $profilePhotoPath = 'public/images/profile_photo/' . $newFileName;
            
            // Delete old photo if exists
            $query = "SELECT profile_photo FROM users WHERE id = '$userId'";
            $result = mysqli_query($connection, $query);
            if ($row = mysqli_fetch_assoc($result)) {
                $oldPhoto = $row['profile_photo'];
                if (!empty($oldPhoto) && file_exists(dirname(__DIR__) . '/' . $oldPhoto)) {
                    unlink(dirname(__DIR__) . '/' . $oldPhoto);
                }
            }
            
            $sql = "UPDATE users SET profile_photo = '$profilePhotoPath' WHERE id = '$userId'";
            if (mysqli_query($connection, $sql)) {
                $_SESSION['profile_success'] = "Profile photo updated successfully.";
                $_SESSION['user_photo'] = $profilePhotoPath;
            } else {
                $_SESSION['profile_errors'] = ["Database error: " . mysqli_error($connection)];
            }
        } else {
            $_SESSION['profile_errors'] = ["Failed to upload profile photo."];
        }
    }
} else {
    $_SESSION['profile_errors'] = ["No file uploaded or an error occurred."];
}

header('Location: admin_profile.php');
exit;
