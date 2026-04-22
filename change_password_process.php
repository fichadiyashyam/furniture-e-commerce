<?php
session_start();
require_once 'auth_check.php';
require_once 'config/db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: profile.php');
    exit;
}

$userId = (int)$_SESSION['user_id'];

// 1. Get inputs
$currentPassword = $_POST['currentPassword'] ?? '';
$newPassword = $_POST['newPassword'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';

$errors = [];

// 2. Validate inputs
if (empty($currentPassword)) {
    $errors[] = "Please enter your current password.";
}
if (empty($newPassword)) {
    $errors[] = "Please enter a new password.";
}
if ($newPassword !== $confirmPassword) {
    $errors[] = "New password and confirm password do not match.";
}

// Check new password strength (at least 8 chars, 1 uppercase, 1 lowercase, 1 number, 1 special character)
if (!empty($newPassword) && !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $newPassword)) {
    $errors[] = "New password must be at least 8 characters long and include an uppercase letter, a lowercase letter, a number, and a special character.";
}

// 3. Verify current password
if (empty($errors)) {
    $query = "SELECT password FROM users WHERE id = $userId LIMIT 1";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        if (!password_verify($currentPassword, $user['password'])) {
            $errors[] = "The current password you entered is incorrect.";
        }
    }
    else {
        $errors[] = "Error authenticating user.";
    }
}

// 4. Update password if no errors
if (empty($errors)) {
    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    // Update the database
    $updateQuery = "UPDATE users SET password = '$hashedPassword' WHERE id = $userId";
    if (mysqli_query($connection, $updateQuery)) {
        $_SESSION['password_success'] = "Your password has been changed successfully.";
        header('Location: profile.php?section=change-password');
        exit;
    }
    else {
        $errors[] = "Failed to update password. Please try again later. Error: " . mysqli_error($connection);
    }
}

// 5. If there are errors, redirect back with error messages
$_SESSION['password_errors'] = $errors;
header('Location: profile.php?section=change-password');
exit;
