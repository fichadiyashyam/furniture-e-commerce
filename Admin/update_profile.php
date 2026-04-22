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
$firstName = trim(mysqli_real_escape_string($connection, $_POST['firstName'] ?? ''));
$lastName = trim(mysqli_real_escape_string($connection, $_POST['lastName'] ?? ''));
$phone = trim(mysqli_real_escape_string($connection, $_POST['phone'] ?? ''));
$gender = trim(mysqli_real_escape_string($connection, $_POST['gender'] ?? ''));
$address = trim(mysqli_real_escape_string($connection, $_POST['address'] ?? ''));
$city = trim(mysqli_real_escape_string($connection, $_POST['city'] ?? ''));
$state = trim(mysqli_real_escape_string($connection, $_POST['state'] ?? ''));
$pincode = trim(mysqli_real_escape_string($connection, $_POST['pincode'] ?? ''));
$country = trim(mysqli_real_escape_string($connection, $_POST['country'] ?? ''));

$errors = [];

if (empty($firstName) || empty($lastName)) {
    $errors[] = "First name and last name are required.";
}

if (!empty($errors)) {
    $_SESSION['profile_errors'] = $errors;
    header('Location: admin_profile.php#edit-profile');
    exit;
}

$sql = "UPDATE users SET 
        first_name = '$firstName', 
        last_name = '$lastName', 
        phone = '$phone', 
        gender = '$gender', 
        address = '$address', 
        city = '$city', 
        state = '$state', 
        pincode = '$pincode', 
        country = '$country' 
        WHERE id = '$userId'";

if (mysqli_query($connection, $sql)) {
    $_SESSION['profile_success'] = "Profile updated successfully.";
    $_SESSION['user_name'] = $firstName . ' ' . $lastName;
} else {
    $_SESSION['profile_errors'] = ["Failed to update profile: " . mysqli_error($connection)];
}

header('Location: admin_profile.php#edit-profile');
exit;
