<?php
session_start();
require_once '../config/db_config.php';

// Check if user is logged in as admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // delete item
    $query = "DELETE FROM cart_items WHERE id = $id";
    mysqli_query($connection, $query);

    // Provide a simple redirect with success flag
    $_SESSION['admin_success_msg'] = "Cart item removed successfully!";
} else {
    $_SESSION['admin_error_msg'] = "Invalid cart item ID.";
}

header('Location: cart.php');
exit;
?>
