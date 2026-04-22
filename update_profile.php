<?php
session_start();
require_once 'auth_check.php';
require_once 'config/db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: profile.php');
    exit;
}

$userId = $_SESSION['user_id'];

// ── 1. Collect & sanitize inputs ─────────────────────────────────────────────
$firstName = trim(mysqli_real_escape_string($connection, $_POST['firstName'] ?? ''));
$lastName = trim(mysqli_real_escape_string($connection, $_POST['lastName'] ?? ''));
$phone = trim(mysqli_real_escape_string($connection, $_POST['phone'] ?? ''));
$gender = trim(mysqli_real_escape_string($connection, $_POST['gender'] ?? ''));
$address = trim(mysqli_real_escape_string($connection, $_POST['address'] ?? ''));
$city = trim(mysqli_real_escape_string($connection, $_POST['city'] ?? ''));
$state = trim(mysqli_real_escape_string($connection, $_POST['state'] ?? ''));
$pincode = trim(mysqli_real_escape_string($connection, $_POST['pincode'] ?? ''));
$country = trim(mysqli_real_escape_string($connection, $_POST['country'] ?? ''));

// ── 2. Basic validation ───────────────────────────────────────────────────────
$errors = [];
if (empty($firstName) || empty($lastName))
    $errors[] = "First and last name are required.";
if (empty($phone))
    $errors[] = "Phone number is required.";
if (empty($address))
    $errors[] = "Address is required.";
if (empty($city))
    $errors[] = "City is required.";
if (empty($state))
    $errors[] = "State is required.";
if (empty($pincode))
    $errors[] = "Pin code is required.";
if (empty($country))
    $errors[] = "Country is required.";

// ── 3. Profile photo update (optional) ───────────────────────────────────────
$profilePhotoSql = ""; // empty unless a new photo is uploaded

if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/public/images/profile_photo/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 5 * 1024 * 1024;

    $fileType = mime_content_type($_FILES['profile_photo']['tmp_name']);
    $fileSize = $_FILES['profile_photo']['size'];
    $fileExt = strtolower(pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION));

    if (!in_array($fileType, $allowedTypes)) {
        $errors[] = "Profile photo must be JPG, PNG, GIF, or WEBP.";
    }
    elseif ($fileSize > $maxSize) {
        $errors[] = "Profile photo must be smaller than 5 MB.";
    }
    else {
        $newFileName = uniqid('photo_', true) . '.' . $fileExt;
        $destinationPath = $uploadDir . $newFileName;

        if (!move_uploaded_file($_FILES['profile_photo']['tmp_name'], $destinationPath)) {
            $errors[] = "Failed to upload profile photo. Please try again.";
        }
        else {
            $newPhotoPath = 'public/images/profile_photo/' . $newFileName;
            $safePhoto = mysqli_real_escape_string($connection, $newPhotoPath);
            $profilePhotoSql = ", profile_photo = '$safePhoto'";
        }
    }
}

// ── 4. Abort on errors ────────────────────────────────────────────────────────
if (!empty($errors)) {
    $_SESSION['profile_errors'] = $errors;
    header('Location: profile.php?section=edit-profile');
    exit;
}

// ── 5. Update DB ──────────────────────────────────────────────────────────────
$sql = "UPDATE users SET
            first_name = '$firstName',
            last_name  = '$lastName',
            phone      = '$phone',
            gender     = '$gender',
            address    = '$address',
            city       = '$city',
            state      = '$state',
            pincode    = '$pincode',
            country    = '$country'
            $profilePhotoSql
        WHERE id = $userId";

if (!mysqli_query($connection, $sql)) {
    $_SESSION['profile_errors'] = ["Update failed: " . mysqli_error($connection)];
    header('Location: profile.php?section=edit-profile');
    exit;
}

// ── 6. Update session values ──────────────────────────────────────────────────
$_SESSION['user_name'] = $firstName . ' ' . $lastName;
if (!empty($newPhotoPath)) {
    $_SESSION['user_photo'] = $newPhotoPath;
}

$_SESSION['profile_success'] = "Profile updated successfully!";
header('Location: profile.php');
exit;
