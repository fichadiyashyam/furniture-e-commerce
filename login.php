<?php
$page = 'login';
include 'header.php';

$errors = $_SESSION['login_errors'] ?? [];
$old    = $_SESSION['login_old']    ?? [];
unset($_SESSION['login_errors'], $_SESSION['login_old']);
// If already logged in, redirect to correct dashboard
if (!empty($_SESSION['is_logged_in'])) {
    if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        header('Location: Admin/index.php');
        exit;
    }
    header('Location: profile.php');
    exit;
}

?>
<script src="js/validation.js"></script>
<!-- Start Hero Section -->
<div class="hero">
  <div class="container">
    <div class="row justify-content-between">
      <div class="col-lg-5">
        <div class="intro-excerpt">
          <h1>Login</h1>
          <p class="mb-4">Welcome back! Please login to your account.</p>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="hero-img-wrap">
          <!-- Optional: You can put an image here or leave it empty -->
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Hero Section -->


<!-- Start Login Form -->
<div class="untree_co-section">
  <div class="container">

    <div class="block">
      <div class="row justify-content-center">


        <div class="col-md-8 col-lg-8 pb-4">
          <?php $success_msg = $_SESSION['login_success'] ?? null; unset($_SESSION['login_success']); ?>
          <?php if ($success_msg): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <?= htmlspecialchars($success_msg) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

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

        <form action="backend/login_process.php" method="POST">
            <div class="form-group">
              <label class="text-black" for="email">Email address</label>
             <input type="email" class="form-control" id="email" name="email"
                data-validation="required email"
                value="<?= htmlspecialchars($old['email'] ?? '') ?>">
              <span id="email_error"></span>
            </div>

            <div class="form-group mb-5">
              <label class="text-black" for="password">Password</label>
              <input type="password" class="form-control" id="password" data-validation="required strongPassword" name="password">
              <span id="password_error"></span>
              <a href="forgot_password.php" class="h6 ">forgot password</a>
            </div>

            <button type="submit" class="btn btn-primary-hover-outline">Login</button>
            <div class="mt-3">
              <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
          </form>

        </div>

      </div>

    </div>

  </div>


</div>
</div>

<!-- End Login Form -->

<?php include 'footer.php'; ?>