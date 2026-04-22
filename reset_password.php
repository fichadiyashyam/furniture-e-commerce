<?php
session_start();
require_once 'config/db_config.php';
$token = trim($_GET['token'] ?? '');
// ── Validate token ────────────────────────────────────────────────────────────
if (empty($token)) {
    $_SESSION['fp_errors'] = ["Invalid password reset link."];
    header('Location: forgot_password.php');
    exit;
}
$safeToken = mysqli_real_escape_string($connection, $token);
$result    = mysqli_query($connection,
    "SELECT id, first_name, reset_token_expiry FROM users
     WHERE reset_token = '$safeToken' LIMIT 1"
);
if (!$result || mysqli_num_rows($result) === 0) {
    $_SESSION['fp_errors'] = ["This reset link is invalid or has already been used."];
    header('Location: forgot_password.php');
    exit;
}
$user = mysqli_fetch_assoc($result);
if (strtotime($user['reset_token_expiry']) < time()) {
    $_SESSION['fp_errors'] = ["This reset link has expired. Please request a new one."];
    header('Location: forgot_password.php');
    exit;
}
// ── Flash messages from reset_password_process ────────────────────────────────
$errors  = $_SESSION['rp_errors'] ?? [];
$success = $_SESSION['rp_success'] ?? '';
unset($_SESSION['rp_errors'], $_SESSION['rp_success']);
$page = 'login';
include 'header.php';
?>
<!-- Start Hero Section -->
<div class="hero">
  <div class="container">
    <div class="row justify-content-between">
      <div class="col-lg-5">
        <div class="intro-excerpt">
          <h1>Reset Password</h1>
          <p class="mb-4">Create a new password for your account.</p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Hero Section -->
<!-- Reset Password Form -->
<div class="untree_co-section">
  <div class="container">
    <div class="block">
      <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 pb-4">
          <?php if (!empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <ul class="mb-0">
                <?php foreach ($errors as $err): ?>
                  <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>
          <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
          <?php endif; ?>
          <form action="backend/reset_password_process.php" method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <div class="form-group">
              <label class="text-black" for="password">New Password</label>
              <input type="password" class="form-control" id="password" name="password"
                placeholder="At least 8 characters">
              <span id="password_error"></span>
            </div>
            <div class="form-group mb-5">
              <label class="text-black" for="confirm_password">Confirm New Password</label>
              <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                placeholder="Repeat new password">
              <span id="confirm_password_error"></span>
            </div>
            <button type="submit" class="btn btn-primary-hover-outline">Update Password</button>
            <div class="mt-3">
              <p><a href="login.php">&larr; Back to Login</a></p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>