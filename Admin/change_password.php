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
$oldPassword = $_POST['oldPassword'] ?? '';
$newPassword = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';

$errors = [];

if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
    $errors[] = "All password fields are required.";
}

if ($newPassword !== $confirmPassword) {
    $errors[] = "New password and confirm password do not match.";
}

if (strlen($newPassword) < 8) {
    $errors[] = "New password must be at least 8 characters long.";
}

if (!preg_match("/[A-Z]/", $newPassword) || !preg_match("/[a-z]/", $newPassword) || !preg_match("/[0-9]/", $newPassword) || !preg_match("/[^a-zA-Z0-9]/", $newPassword)) {
    $errors[] = "New password must include uppercase, lowercase, number, and special character.";
}

if (!empty($errors)) {
    $_SESSION['profile_errors'] = $errors;
    header('Location: admin_profile.php#change-password');
    exit;
}

// Check old password
$query = "SELECT password FROM users WHERE id = '$userId'";
$result = mysqli_query($connection, $query);
$user = mysqli_fetch_assoc($result);

if (!password_verify($oldPassword, $user['password'])) {
    $_SESSION['profile_errors'] = ["Current password is incorrect."];
    header('Location: admin_profile.php#change-password');
    exit;
}

$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

$sql = "UPDATE users SET password = '$hashedPassword' WHERE id = '$userId'";
if (mysqli_query($connection, $sql)) {
    $_SESSION['profile_success'] = "Password changed successfully.";
} else {
    $_SESSION['profile_errors'] = ["Failed to change password: " . mysqli_error($connection)];
}

header('Location: admin_profile.php#change-password');
exit;
