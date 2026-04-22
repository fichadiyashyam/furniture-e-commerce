<?php
session_start();
$page = 'login';
include 'header.php';

$errors  = $_SESSION['fp_errors']  ?? [];
$success = $_SESSION['fp_success'] ?? '';
unset($_SESSION['fp_errors'], $_SESSION['fp_success']);

?>
<script src="js/validation.js"></script>
<!-- Start Hero Section -->
<div class="hero">
  <div class="container">
    <div class="row justify-content-between">
      <div class="col-lg-5">
        <div class="intro-excerpt">
          <h1>Forgot Password</h1>
          <p class="mb-4">Enter your email and we'll send you a reset link.</p>
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
          <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <?= $success ?>
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

        <form action="backend/forgot_password_process.php" method="POST">
            <div class="form-group">
              <label class="text-black" for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email"
                data-validation="required email" placeholder="Enter your registered email">
              <span id="email_error"></span>
            </div>

              <button type="submit" class="btn btn-primary-hover-outline mt-3">Send Reset Link</button>
            <div class="mt-3">
              <p><a href="login.php">&larr; Back to Login</a></p>
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