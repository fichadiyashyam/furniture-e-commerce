<?php
session_start();
require_once '../config/db_config.php';
require_once '../config/mail_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: forgot_password.php');
    exit;
}

$email = trim($_POST['email'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['fp_errors'] = ["Please enter a valid email address."];
    header('Location: forgot_password.php');
    exit;
}

// ── 1. Look up user ───────────────────────────────────────────────────────────
$safeEmail = mysqli_real_escape_string($connection, $email);
$result = mysqli_query($connection,
    "SELECT id, first_name FROM users WHERE email = '$safeEmail' AND is_verified = 1 LIMIT 1"
);

// Always show success message — don't reveal whether email exists (security)
$successMsg = "If that email is registered and verified, you'll receive a password reset link shortly.";

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    // ── 2. Generate reset token (expires in 1 hour) ───────────────────────────
    $resetToken = bin2hex(random_bytes(32));
    $tokenExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
    $safeToken = mysqli_real_escape_string($connection, $resetToken);

    mysqli_query($connection,
        "UPDATE users SET reset_token = '$safeToken', reset_token_expiry = '$tokenExpiry'
         WHERE id = {$user['id']}"
    );

    // ── 3. Send reset email ───────────────────────────────────────────────────
    $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/reset_password.php?token=$resetToken";
    $firstName = htmlspecialchars($user['first_name']);

    try {
        $mail = getMailer();
        $mail->addAddress($email);
        $mail->Subject = 'Reset your Furni password';
        $mail->Body = "
        <html>
        <body style='font-family: Arial, sans-serif; color: #333;'>
          <h2>Password Reset Request</h2>
          <p>Hi $firstName,</p>
          <p>We received a request to reset the password for your Furni account.</p>
          <p style='margin: 30px 0;'>
            <a href='$resetLink'
               style='background:#3d5a80;color:#fff;padding:12px 28px;text-decoration:none;border-radius:4px;'>
              Reset Password
            </a>
          </p>
          <p style='color:#888;font-size:13px;'>This link expires in <strong>1 hour</strong>.</p>
          <p style='color:#888;font-size:13px;'>If you did not request a password reset, you can safely ignore this email.</p>
        </body>
        </html>
        ";
        $mail->AltBody = "Reset your Furni password: $resetLink (expires in 1 hour)";
        $mail->send();
    }
    catch (Exception $e) {
        error_log('Reset mail error: ' . $e->getMessage());
    }
}

$_SESSION['fp_success'] = $successMsg;
header('Location: ../forgot_password.php');
exit;
