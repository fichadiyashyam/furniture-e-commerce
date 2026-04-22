<?php
$page = 'admin_profile';
include 'header.php';
require_once '../config/db_config.php';

$userId = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = '$userId'";
$result = mysqli_query($connection, $query);
$user = mysqli_fetch_assoc($result);

$profilePhoto = !empty($user['profile_photo']) ? '../' . $user['profile_photo'] : 'https://images.unsplash.com/photo-1633332755192-727a05c4013d?w=300&q=80';
$firstName = htmlspecialchars($user['first_name'] ?? '');
$lastName = htmlspecialchars($user['last_name'] ?? '');
$fullName = trim($firstName . ' ' . $lastName);
$email = htmlspecialchars($user['email'] ?? '');
$phone = htmlspecialchars($user['phone'] ?? '');
$gender = htmlspecialchars($user['gender'] ?? '');
$address = htmlspecialchars($user['address'] ?? '');
$city = htmlspecialchars($user['city'] ?? '');
$state = htmlspecialchars($user['state'] ?? '');
$pincode = htmlspecialchars($user['pincode'] ?? '');
$country = htmlspecialchars($user['country'] ?? '');
$memberSince = !empty($user['created_at']) ? date("F Y", strtotime($user['created_at'])) : 'Unknown';
?>
<link rel="stylesheet" href="admin_profile.css">
<script src="/js/validation.js"></script>
<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <?php if (isset($_SESSION['profile_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['profile_success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['profile_success']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['profile_errors'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        <?php foreach ($_SESSION['profile_errors'] as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['profile_errors']); ?>
            <?php endif; ?>
        </div>
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="profile-sidebar">
                <!-- Profile Photo Section -->
                <div class="profile-photo-section text-center p-4 bg-white rounded shadow-sm mb-3">
                    <form id="photoForm" action="update_profile_photo.php" method="POST" enctype="multipart/form-data">
                        <div class="profile-photo-wrapper position-relative d-inline-block mb-3">
                            <img id="profilePhoto"
                                src="<?= $profilePhoto ?>"
                                alt="Profile Photo" class="profile-photo rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                            <label for="photoUpload" class="photo-upload-btn">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" name="profile_photo" id="photoUpload" accept="image/*" style="display: none;" onchange="this.form.submit()">
                        </div>
                    </form>
                    <h5 class="mb-1"><?= $fullName ?></h5>
                    <p class="text-muted mb-0"><?= $email ?></p>
                </div>

                <!-- Navigation Menu -->
                <div class="profile-menu bg-white rounded shadow-sm">
                    <ul class="list-unstyled mb-0">
                        <li>
                            <a href="#profile" class="menu-item active" onclick="showSection('profile')">
                                <i class="fas fa-user me-2"></i> Profile Details
                            </a>
                        </li>
                        <li>
                            <a href="#edit-profile" class="menu-item" onclick="showSection('edit-profile')">
                                <i class="fas fa-edit me-2"></i> Edit Profile
                            </a>
                        </li>
                        <li>
                            <a href="#change-password" class="menu-item" onclick="showSection('change-password')">
                                <i class="fas fa-lock me-2"></i> Change Password
                            </a>
                        </li>

                        <li>
                            <a href="#addresses" class="menu-item" onclick="showSection('addresses')">
                                <i class="fas fa-map-marker-alt me-2"></i> Addresses
                            </a>
                        </li>

                        <li>
                            <a href="../logout.php" class="menu-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-lg-9">
            <!-- Profile Details Section -->
            <div id="profile" class="content-section">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Profile Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <label class="detail-label">Full Name</label>
                                    <p class="detail-value"><?= !empty($fullName) ? $fullName : 'Not provided' ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <label class="detail-label">Email Address</label>
                                    <p class="detail-value"><?= $email ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <label class="detail-label">Phone Number</label>
                                    <p class="detail-value"><?= !empty($phone) ? $phone : 'Not provided' ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <label class="detail-label">Date of Birth</label>
                                    <p class="detail-value">Not configured in DB</p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <label class="detail-label">Gender</label>
                                    <p class="detail-value"><?= !empty($gender) ? ucfirst($gender) : 'Not provided' ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <label class="detail-label">Member Since</label>
                                    <p class="detail-value"><?= $memberSince ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="detail-item">
                                    <label class="detail-label">Address</label>
                                    <p class="detail-value">
                                        <?php if (!empty($address)): ?>
                                            <?= $address ?><?= !empty($city) ? ', ' . $city : '' ?><?= !empty($state) ? ', ' . $state : '' ?>
                                            <?= !empty($pincode) ? ' - ' . $pincode : '' ?><?= !empty($country) ? ', ' . $country : '' ?>
                                        <?php else: ?>
                                            Not provided
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button class="btn btn-primary" onclick="showSection('edit-profile')">
                                <i class="fas fa-edit me-2"></i>Edit Profile
                            </button>
                        </div>
                    </div>
                </div>



            </div>

            <!-- Edit Profile Section -->
            <div id="edit-profile" class="content-section" style="display: none;">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Edit Profile</h4>
                    </div>
                    <div class="card-body">
                        <form action="update_profile.php" method="POST">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="firstName" class="form-label">First Name *</label>
                                    <input type="text" class="form-control" id="firstName" name="firstName"
                                        placeholder="Shyam" data-validation="required min max alphabetic" data-min="2"
                                        data-max="100" value="<?= $firstName ?>">
                                    <span id="firstName_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="lastName" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control" id="lastName" name="lastName"
                                        placeholder="Fichadiya" data-validation="required min max alphabetic"
                                        data-min="2" data-max="100" value="<?= $lastName ?>">
                                    <span id="lastName_error"></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="<?= $email ?>" data-validation="required email" readonly>
                                    <span id="email_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        placeholder="+91 98765 43210" data-validation="required number min max"
                                        data-min="10" data-max="10" value="<?= $phone ?>">
                                    <span id="phone_error"></span>
                                </div>
                            </div>

                            <div class="row mb-3">

                                <div class="col-md-12">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select" id="gender" name="gender"
                                        data-validation="required select">
                                        <option value="">Select Gender</option>
                                        <option value="male" <?= $gender == 'male' ? 'selected' : '' ?>>Male</option>
                                        <option value="female" <?= $gender == 'female' ? 'selected' : '' ?>>Female</option>
                                        <option value="other" <?= $gender == 'other' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                    <span id="gender_error"></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    placeholder="123, MG Road" placeholder="Street Address"
                                    data-validation="required min" data-min="2" value="<?= $address ?>">
                                <span id="address_error"></span>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city"
                                        placeholder="Ahmedabad" data-validation="required min" data-min="2" value="<?= $city ?>">
                                    <span id="city_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="state" class="form-label">State</label>
                                    <input type="text" class="form-control" id="state" name="state"
                                        placeholder="Gujarat" data-validation="required min" data-min="2" value="<?= $state ?>">
                                    <span id="state_error"></span>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="pincode" class="form-label">Pin Code</label>
                                    <input type="text" class="form-control" id="pincode" name="pincode"
                                        placeholder="380001" data-validation="required number min max" data-max="6"
                                        data-min="6" value="<?= $pincode ?>">
                                    <span id="pincode_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="country" class="form-label">Country</label>
                                    <input type="text" class="form-control" id="country" name="country"
                                        placeholder="India" data-validation="required max min alphabetic" data-min="2"
                                        data-max="10" value="<?= $country ?>">
                                    <span id="country_error"></span>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save Changes
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="showSection('profile')">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Change Password Section -->
            <!-- Change Password Section -->
            <div id="change-password" class="content-section" style="display: none;">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Change Password</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Password must be at least 8 characters long and include uppercase, lowercase, number, and
                            special character.
                        </div>

                        <form action="change_password.php" method="POST">
                            <div class="mb-3">
                                <label for="currentPassword" class="form-label">Current Password *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="currentPassword" name="oldPassword"
                                        data-validation="required strongPassword">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('currentPassword')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <span id="oldPassword_error"></span>
                            </div>

                            <div class="mb-3">
                                <label for="newPassword" class="form-label">New Password *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password"
                                        data-validation="required strongPassword">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('newPassword')">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                </div>

                                <span id="password_error"></span>

                            </div>

                            <div class="mb-4">
                                <label for="confirmPassword" class="form-label">Confirm New Password *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirmPassword"
                                        name="confirmPassword" data-validation="required confirmPassword">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('confirmPassword')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div id="passwordMatch" class="mt-2"></div>
                                <span id="confirmPassword_error"></span>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-lock me-2"></i>Update Password
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    Reset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>


            <!-- Orders Section -->


            <!-- Addresses Section -->
            <div id="addresses" class="content-section" style="display: none;">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Saved Addresses</h4>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                            <i class="fas fa-plus me-2"></i>Add New Address
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Address 1 -->
                            <div class="col-md-6 mb-3">
                                <div class="address-card border rounded p-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h6>Home</h6>
                                        <span class="badge bg-primary">Default</span>
                                    </div>
                                    <p class="mb-1"><strong>Shyam fichadiya</strong></p>
                                    <p class="mb-1">123, MG Road</p>
                                    <p class="mb-1">Ahmedabad, Gujarat - 380001</p>
                                    <p class="mb-3">Phone: +91 98765 43210</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary">Edit</button>
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Address 2 -->
                            <div class="col-md-6 mb-3">
                                <div class="address-card border rounded p-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h6>Office</h6>
                                    </div>
                                    <p class="mb-1"><strong>Shyam fichadiya</strong></p>
                                    <p class="mb-1">456, SG Highway</p>
                                    <p class="mb-1">Ahmedabad, Gujarat - 380015</p>
                                    <p class="mb-3">Phone: +91 98765 43210</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary">Edit</button>
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                        <button class="btn btn-sm btn-outline-success">Set as Default</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="/add-address" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Address Type</label>
                        <select class="form-select" name="addressType" required>
                            <option value="">Select Type</option>
                            <option value="home">Home</option>
                            <option value="office">Office</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" class="form-control" name="fullName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number *</label>
                        <input type="tel" class="form-control" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address *</label>
                        <input type="text" class="form-control" name="address" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">City *</label>
                            <input type="text" class="form-control" name="city" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">State *</label>
                            <input type="text" class="form-control" name="state" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Pin Code *</label>
                            <input type="text" class="form-control" name="pincode" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Country *</label>
                            <input type="text" class="form-control" name="country" value="India" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="setDefault" id="setDefault">
                            <label class="form-check-label" for="setDefault">
                                Set as default address
                            </label>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Save Address</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="<https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js>"></script>
<script src="<https://code.jquery.com/jquery-3.6.0.min.js>"></script>
<script src="<https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js>"></script>
<script>
    function showSection(sectionId) {
        // Hide all sections
        const sections = document.querySelectorAll('.content-section');
        sections.forEach(section => {
            section.style.display = 'none';
        });

        const selectedSection = document.getElementById(sectionId);
        if (selectedSection) {
            selectedSection.style.display = 'block';
        }

        // Update active menu item
        const menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            item.classList.remove('active');
        });

        const activeItem = document.querySelector(`a[href="#${sectionId}"]`);
        if (activeItem) {
            activeItem.classList.add('active');
        }

        // Prevent default anchor behavior
        event.preventDefault();
    }

    // Toggle Password Visibility
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = event.currentTarget.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        showSection('profile');
    });

</script>


<?php include 'footer.php'; ?>