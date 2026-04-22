<?php
session_start();
require_once '../config/db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// ── 1. Basic validation ───────────────────────────────────────────────────────
$errors = [];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}
if (empty($password)) {
    $errors[] = "Password is required.";
}

if (!empty($errors)) {
    $_SESSION['login_errors'] = $errors;
    $_SESSION['login_old'] = ['email' => $email];
    header('Location: ../login.php');
    exit;
}

// ── 2. Look up user by email ──────────────────────────────────────────────────
$safeEmail = mysqli_real_escape_string($connection, $email);
$result = mysqli_query($connection,
    "SELECT id, first_name, last_name, email, password, profile_photo, is_verified, role
     FROM users WHERE email = '$safeEmail' LIMIT 1"
);

if (!$result || mysqli_num_rows($result) === 0) {
    $_SESSION['login_errors'] = ["Invalid email or password."];
    $_SESSION['login_old'] = ['email' => $email];
    header('Location: ../login.php');
    exit;
}

$user = mysqli_fetch_assoc($result);

// ── 3. Verify password hash ───────────────────────────────────────────────────
if (!password_verify($password, $user['password'])) {
    $_SESSION['login_errors'] = ["Invalid email or password."];
    $_SESSION['login_old'] = ['email' => $email];
    header('Location: ../login.php');
    exit;
}

// ── 4. Check email verified ───────────────────────────────────────────────────
if (!$user['is_verified']) {
    $_SESSION['login_errors'] = ["Please verify your email address before logging in. Check your inbox for the verification link."];
    $_SESSION['login_old'] = ['email' => $email];
    header('Location: ../login.php');
    exit;
}

// ── 5. Regenerate session ID to prevent session fixation ─────────────────────
session_regenerate_id(true);

// ── 6. Store user info in session ─────────────────────────────────────────────
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
$_SESSION['user_photo'] = $user['profile_photo'];
$_SESSION['is_logged_in'] = true;

// ── 7. Redirect based on role ─────────────────────────────────────────────────
$safeRole = strtolower(trim($user['role'] ?? ''));

if ($safeRole === 'admin') {
    $_SESSION['role'] = 'admin';
    $_SESSION['dashboard_success'] = "Welcome back, " . $user['first_name'] . "!";
    session_write_close(); // Ensure session is fully saved before redirecting
    header('Location: ../Admin/index.php');
    exit;
} else {
    $_SESSION['role'] = $safeRole ?: 'user';
    $_SESSION['dashboard_success'] = "Welcome back, " . $user['first_name'] . "!";
    session_write_close();
    header('Location: ../profile.php');
    exit;
}
