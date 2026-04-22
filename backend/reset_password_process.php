<?php
session_start();
require_once '../config/db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../forgot_password.php');
    exit;
}

$token = trim($_POST['token'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

// ── 1. Re-validate token ──────────────────────────────────────────────────────
$safeToken = mysqli_real_escape_string($connection, $token);
$query = "SELECT id, reset_token_expiry FROM users 
          WHERE reset_token = '$safeToken'
          LIMIT 1";

$result = mysqli_query($connection, $query);

if (empty($token) || !$result || mysqli_num_rows($result) === 0) {
    $_SESSION['fp_errors'] = ["This reset link is invalid or has already been used. Please request a new one."];
    header('Location: ../forgot_password.php');
    exit;
}

$user = mysqli_fetch_assoc($result);

// Check expiry in PHP (avoids MySQL timezone vs PHP timezone mismatch)
if (strtotime($user['reset_token_expiry']) < time()) {
    $_SESSION['fp_errors'] = ["This reset link has expired (valid for 1 hour). Please request a new one."];
    header('Location: ../forgot_password.php');
    exit;
}

// ── 2. Validate new password ──────────────────────────────────────────────────

$errors = [];

if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
}
if (!preg_match('/[A-Z]/', $password)) {
    $errors[] = "Password must contain at least one uppercase letter.";
}
if (!preg_match('/[0-9]/', $password)) {
    $errors[] = "Password must contain at least one number.";
}
if ($password !== $confirmPassword) {
    $errors[] = "Passwords do not match.";
}
if (!empty($errors)) {
    $_SESSION['rp_errors'] = $errors;
    header("Location: ../reset_password.php?token=$token");
    exit;
}


// ── 3. Hash and save new password, clear the reset token ─────────────────────
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

mysqli_query($connection,
    "UPDATE users
     SET password = '$hashedPassword', reset_token = NULL, reset_token_expiry = NULL
     WHERE id = {$user['id']}"
);

// ── 4. Redirect to login with success message ─────────────────────────────────
$_SESSION['login_errors'] = []; // clear any old errors
$_SESSION['fp_success'] = "Your password has been reset successfully. You can now <a href='login.php'>log in</a>.";
header('Location: ../forgot_password.php');
exit;






























