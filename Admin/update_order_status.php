<?php
session_start();
require_once '../config/db_config.php';
require_once '../config/mail_config.php';

// Check if user is logged in as admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// Check for POST logic
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['order_id']) || empty($_POST['action'])) {
    header('Location: orders.php');
    exit;
}

$order_id = intval($_POST['order_id']);
$action = strtolower(trim($_POST['action'])); // accept or reject

if ($action === 'accept') {
    $new_status = 'accepted';
    $log_msg = "Order accepted securely. Client notified via email!";
} elseif ($action === 'reject') {
    $new_status = 'rejected';
    $log_msg = "Order rejected correctly. Notice sent to client.";
} else {
    $_SESSION['admin_error_msg'] = "Invalid order operation.";
    header('Location: orders.php');
    exit;
}

// Fetch order bounds to check valid order AND to catch email of user
$order_query = "SELECT o.*, u.email, u.first_name FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = $order_id LIMIT 1";
$res = mysqli_query($connection, $order_query);

if (!$res || mysqli_num_rows($res) === 0) {
    $_SESSION['admin_error_msg'] = "Order lookup failed. Does not exist.";
    header('Location: orders.php');
    exit;
}

$orderData = mysqli_fetch_assoc($res);
$currentStatus = $orderData['status'];

if ($currentStatus !== 'pending') {
    $_SESSION['admin_error_msg'] = "This order was already " . $currentStatus . ".";
    header('Location: orders.php');
    exit;
}

// UPDATE status inside Order table
mysqli_query($connection, "UPDATE orders SET status = '$new_status' WHERE id = $order_id");

// ------------------------------------------
// Email Phase
// ------------------------------------------

$userEmail = $orderData['email'];
$userName = $orderData['first_name'];
$total = number_format($orderData['total_amount'], 2);

try {
    $mail = getMailer();
    $mail->addAddress($userEmail, $userName);
    
    if ($new_status === 'accepted') {
        $mail->Subject = 'Good News! Your Furni Order was Accepted';
        $mail->Body = "
        <html>
        <body style='font-family: Arial, sans-serif; color: #333;'>
          <h2>Hello $userName,</h2>
          <p>We are delighted to inform you that your order <strong>#ORD-".str_pad($order_id, 4, '0', STR_PAD_LEFT)."</strong> has been <strong>ACCEPTED</strong>.</p>
          <p>The total billed amount evaluates to <strong>$$total</strong>. We will commence processing immediately!</p>
          <br>
          <p>Thank you for shopping at Furni!</p>
        </body>
        </html>
        ";
        $mail->AltBody = "Hello $userName, We are delighted to inform you that your order #ORD-".str_pad($order_id, 4, '0', STR_PAD_LEFT)." ($$total) has been ACCEPTED.";
    } else {
        $mail->Subject = 'Update on your Furni Order';
        $mail->Body = "
        <html>
        <body style='font-family: Arial, sans-serif; color: #333;'>
          <h2>Hello $userName,</h2>
          <p>We regret to inform you that we are currently unable to fulfill your order <strong>#ORD-".str_pad($order_id, 4, '0', STR_PAD_LEFT)."</strong>. Thus, it has been <strong>REJECTED</strong>.</p>
          <p>If you've been preemptively billed for the $$total, it will be refunded promptly automatically.</p>
          <br>
          <p>For inquiries, please contact Furni Support.</p>
        </body>
        </html>
        ";
        $mail->AltBody = "Hello $userName, We regret to inform you your order #ORD-".str_pad($order_id, 4, '0', STR_PAD_LEFT)." was REJECTED. Please contact support.";
    }
    
    $mail->send();
    $_SESSION['admin_success_msg'] = $log_msg;

} catch (Exception $e) {
    // If mail fails, status still updated, log the notification issue
    $_SESSION['admin_success_msg'] = "Order status updated to $new_status, but email notification failed: " . $e->getMessage();
}

header('Location: orders.php');
exit;
?>
