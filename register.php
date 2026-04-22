<?php

session_start();
$page = 'register';
include 'header.php';


$errors = $_SESSION['register_errors'] ?? [];
$success = $_SESSION['register_success'] ?? '';
$old = $_SESSION['register_old'] ?? [];
unset($_SESSION['register_errors'], $_SESSION['register_success'], $_SESSION['register_old']);
?>
<script src="js/validation.js"></script>
<!-- Start Hero Section -->
<div class="hero">
  <div class="container">
    <div class="row justify-content-between">
      <div class="col-lg-5">
        <div class="intro-excerpt">
          <h1>Register</h1>
          <p class="mb-4">Create a new account to get started.</p>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="hero-img-wrap">
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Hero Section -->


<!-- Start Register Form -->
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
          <form action="backend/register_process.php" method="POST" enctype="multipart/form-data">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="text-black" for="fname">First name</label>
                  <input type="text" class="form-control" id="fname" data-validation="required min max" data-min="2"
                    data-max="100" name="firstName">
                  <span id="firstName_error"></span>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="text-black" for="lname">Last name</label>
                  <input type="text" class="form-control" id="lname" data-validation="required min max" data-min="2"
                    data-max="100" name="lastName">
                  <span id="lastName_error"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="text-black" for="email">Email address</label>
                  <input type="email" class="form-control" id="email" name="email" data-validation="required email">
                  <span id="email_error"></span>
                </div>
              </div>
              <div class="col-md-6">
                <label for="phone" class="form-label">Phone Number *</label>
                <input type="tel" class="form-control" id="phone" name="phone" placeholder="+91 98765 43210"
                  data-validation="required number min max" data-min="10" data-max="10">
                <span id="phone_error"></span>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-12">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-select" id="gender" name="gender" data-validation="required select">
                  <option value="">Select Gender</option>
                  <option value="male" selected>Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                </select>
                <span id="gender_error"></span>
              </div>
            </div>

            <div class="mb-3">
              <label for="address" class="form-label">Address</label>
              <input type="text" class="form-control" id="address" name="address" placeholder="123, MG Road"
                placeholder="Street Address" data-validation="required min" data-min="2">
              <span id="address_error"></span>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="Ahmedabad"
                  data-validation="required min" data-min="2">
                <span id="city_error"></span>
              </div>
              <div class="col-md-6">
                <label for="state" class="form-label">State</label>
                <input type="text" class="form-control" id="state" name="state" placeholder="Gujarat"
                  data-validation="required min" data-min="2">
                <span id="state_error"></span>
              </div>
            </div>


            <div class="row mb-4">
              <div class="col-md-6">
                <label for="pincode" class="form-label">Pin Code</label>
                <input type="text" class="form-control" id="pincode" name="pincode" placeholder="380001"
                  data-validation="required number min max" data-max="6" data-min="6">
                <span id="pincode_error"></span>
              </div>
              <div class="col-md-6">
                <label for="country" class="form-label">Country</label>
                <input type="text" class="form-control" id="country" name="country" placeholder="India"
                  data-validation="required max min alphabetic" data-min="2" data-max="10">
                <sapn id="country_error"></sapn>
              </div>
            </div>

            <div class="form-group">
              <label class="text-black" for="password">Password</label>
              <input type="password" class="form-control" id="password" data-validation="required strongPassword"
                name="password">
              <span id="password_error"></span>
            </div>

            <div class="form-group">
              <label class="text-black" for="confirm_password">Confirm Password</label>
              <input type="password" class="form-control" id="confirm_password"
                data-validation="required " name="confirmPassword">
              <span id="confirmPassword_error"></span>
            </div>

            <div class="form-group mb-5 mt-2">
              <label class="text-black" for="profile_photo">Profile Photo</label>
              <input type="file" class="form-control" id="profile_photo" name="profile_photo"
                data-validation="required file">
              <span id="profile_photo_error"></span>
            </div>

            <button type="submit" class="btn btn-primary-hover-outline">Register</button>
            <div class="mt-3">
              <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
          </form>

        </div>

      </div>

    </div>

  </div>


</div>
</div>

<!-- End Register Form -->

<?php include 'footer.php'; ?>