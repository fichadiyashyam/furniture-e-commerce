<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Enforce admin login check
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true || empty($_SESSION['role'])) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Untree.co">
    <link rel="shortcut icon" href="../favicon.png">

    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap4" />

    <!-- Bootstrap CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-4.0.0.js"></script>
    <title>Furni Admin Dashboard</title>
</head>

<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <?php include 'navbar.php'; ?>