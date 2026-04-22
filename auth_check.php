<?php
/**
 * auth_check.php
 * Include this at the TOP of any page that requires login.
 * Usage:  require_once 'auth_check.php';
 *
 * If the user is not logged in they are redirected to login.php
 * and the originally requested URL is saved so they can be sent
 * back after a successful login.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    $_SESSION['login_redirect'] = $_SERVER['REQUEST_URI'];
    $_SESSION['login_errors'] = ["Please log in to access that page."];
    header('Location: login.php');
    exit;
}
