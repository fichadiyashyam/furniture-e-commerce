<?php
session_start();
require_once 'config/db_config.php';
$token = trim($_GET['token'] ?? '');
if (empty($token)) {
    $_SESSION['register_errors'] = ["Invalid verification link."];
    header('Location: register.php');
    exit;
}
// Sanitize token
$token = mysqli_real_escape_string($connection, $token);
// Find user with this token that hasn't expired yet
$result = mysqli_query($connection,
    "SELECT id, is_verified, token_expiry FROM users 
     WHERE verification_token = '$token' LIMIT 1"
);
if (!$result || mysqli_num_rows($result) === 0) {
    $message = "Invalid or expired verification link.";
    $type    = "error";
} else {
    $user = mysqli_fetch_assoc($result);
    if ($user['is_verified']) {
        $message = "Your email is already verified. You can <a href='login.php'>log in</a>.";
        $type    = "info";
    } elseif (strtotime($user['token_expiry']) < time()) {
        $message = "This verification link has expired. Please register again.";
        $type    = "error";
    } else {
        // Mark as verified and clear the token
        $updateResult = mysqli_query($connection,
            "UPDATE users 
             SET is_verified = 1, verification_token = NULL, token_expiry = NULL 
             WHERE id = {$user['id']}"
        );
        if ($updateResult) {
            $message = "Your email has been verified successfully! You can now <a href='login.php'>log in</a>.";
            $type    = "success";
        } else {
            $message = "Something went wrong. Please try again later.";
            $type    = "error";
        }
    }
}
?>
<?php
$page = 'register';
include 'header.php';
?>
<!-- Start Hero Section -->
<div class="hero">
  <div class="container">
    <div class="row justify-content-between">
      <div class="col-lg-5">
        <div class="intro-excerpt">
          <h1>Email Verification</h1>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Hero Section -->
<div class="untree_co-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 text-center py-5">
        <?php if ($type === 'success'): ?>
          <div class="alert alert-success fs-5"><?= $message ?></div>
        <?php elseif ($type === 'info'): ?>
          <div class="alert alert-info fs-5"><?= $message ?></div>
        <?php else: ?>
          <div class="alert alert-danger fs-5"><?= $message ?></div>
        <?php endif; ?>
        <a href="register.php" class="btn btn-outline-secondary mt-3">Back to Register</a>
      </div>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>