<?php
require_once 'auth_check.php';
require_once 'config/db_config.php';
 // must be logged in to view this page
$page = 'product_detail';
include 'header.php';

$user_email = $_SESSION['user_email'];
$user_data = 'select * from users where email = "'.$user_email.'"';
$user_data = mysqli_query($connection, $user_data);
$user_data = mysqli_fetch_assoc($user_data);

?>
<link rel="stylesheet" href="css/profile.css">
<script src="js/validation.js"></script>
<div class="container my-5">
    <?php 
        $dashboard_success = $_SESSION['dashboard_success'] ?? null; unset($_SESSION['dashboard_success']); 
        $passwordSuccess = $_SESSION['password_success'] ?? null; unset($_SESSION['password_success']);
        $passwordErrors = $_SESSION['password_errors'] ?? []; unset($_SESSION['password_errors']);
    ?>
    <?php $dashboard_success = $_SESSION['dashboard_success'] ?? null; unset($_SESSION['dashboard_success']); ?>
    <?php if ($dashboard_success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($dashboard_success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="profile-sidebar">
                <!-- Profile Photo Section -->
                <div class="profile-photo-section text-center p-4 bg-white rounded shadow-sm mb-3">
                    <div class="profile-photo-wrapper position-relative d-inline-block mb-3">
                        <?php
                        $photo = $_SESSION['user_photo'] ?? '';
                        $photoSrc = (!empty($photo) && file_exists(__DIR__ . '/' . $photo))
                            ? $photo
                            : 'https://images.unsplash.com/photo-1633332755192-727a05c4013d?w=300&q=80';
                        ?>
                        <img id="profilePhoto"
                            src="<?= htmlspecialchars($photoSrc) ?>"
                            alt="Profile Photo" class="profile-photo rounded-circle">
                        <label for="photoUpload" class="photo-upload-btn">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" id="photoUpload" accept="image/*" style="display: none;">
                    </div>
                    <h5 class="mb-1"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></h5>
                    <p class="text-muted mb-0"><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></p>
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
                            <a href="#orders" class="menu-item" onclick="showSection('orders')">
                                <i class="fas fa-shopping-bag me-2"></i> My Orders
                            </a>
                        </li>
                        <li>
                            <a href="#addresses" class="menu-item" onclick="showSection('addresses')">
                                <i class="fas fa-map-marker-alt me-2"></i> Addresses
                            </a>
                        </li>
                        <li>
                            <a href="#wishlist" class="menu-item" onclick="showSection('wishlist')">
                                <i class="fas fa-heart me-2"></i> Wishlist
                            </a>
                        </li>
                        <li>
                            <a href="logout.php" class="menu-item text-danger">
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
                                    <p class="detail-value"><?= $user_data['first_name'] . ' ' . $user_data['last_name'] ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <label class="detail-label">Email Address</label>
                                    <p class="detail-value"><?= $user_data['email'] ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <label class="detail-label">Phone Number</label>
                                    <p class="detail-value"><?= $user_data['phone'] ?></p>
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="detail-item">
                                    <label class="detail-label">Gender</label>
                                    <p class="detail-value"><?= $user_data['gender'] ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                           <div class="col-md-6">
                                <div class="detail-item">
                                    <label class="detail-label">Address</label>
                                    <p class="detail-value"><?= $user_data['address'] ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <label class="detail-label">Member Since</label>
                                    <p class="detail-value"><?= $user_data['created_at'] ?></p>
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

                <!-- Account Statistics -->
                <?php
                // Get real counts
                $uid = intval($_SESSION['user_id']);
                $order_count_q = mysqli_query($connection, "SELECT COUNT(*) AS cnt FROM orders WHERE user_id = $uid");
                $order_count = ($order_count_q && $r = mysqli_fetch_assoc($order_count_q)) ? intval($r['cnt']) : 0;
                $wishlist_count_q = mysqli_query($connection, "SELECT COUNT(*) AS cnt FROM wishlist WHERE user_id = $uid");
                $wishlist_count = ($wishlist_count_q && $r = mysqli_fetch_assoc($wishlist_count_q)) ? intval($r['cnt']) : 0;
                ?>
                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <div class="stat-card bg-primary text-white rounded shadow-sm p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="mb-0"><?php echo $order_count; ?></h3>
                                    <p class="mb-0">Total Orders</p>
                                </div>
                                <i class="fas fa-shopping-cart fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stat-card bg-success text-white rounded shadow-sm p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="mb-0" id="wishlistStatCount"><?php echo $wishlist_count; ?></h3>
                                    <p class="mb-0">Wishlist Items</p>
                                </div>
                                <i class="fas fa-heart fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stat-card bg-warning text-white rounded shadow-sm p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="mb-0">3</h3>
                                    <p class="mb-0">Saved Addresses</p>
                                </div>
                                <i class="fas fa-map-marker-alt fa-2x opacity-50"></i>
                            </div>
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
                                        value="<?= $user_data['first_name'] ?>" data-validation="required min max alphabetic" data-min="2"
                                        data-max="100">
                                    <span id="firstName_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="lastName" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control" id="lastName" name="lastName"
                                        value="<?= $user_data['last_name'] ?>" data-validation="required min max alphabetic" data-min="2"
                                        data-max="100">
                                    <span id="lastName_error"></span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="<?= $user_data['email'] ?>" data-validation="required email" readonly>
                                    <span id="email_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        value="<?= $user_data['phone'] ?>" data-validation="required number min max" data-min="10"
                                        data-max="10">
                                        <span id="phone_error"></span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                
                                <div class="col-md-12">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select" id="gender" name="gender" data-validation="required select">
                                        <option value="">Select Gender</option>
                                        <option value="male" <?php if ($user_data['gender'] == 'male') echo 'selected'; ?>>Male</option>
                                        <option value="female" <?php if ($user_data['gender'] == 'female') echo 'selected'; ?>>Female</option>
                                        <option value="other" <?php if ($user_data['gender'] == 'other') echo 'selected'; ?>>Other</option>
                                    </select>
                                    <span id="gender_error"></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?= $user_data['address'] ?>"
                                    placeholder="Street Address" data-validation="required min" data-min="2">
                                <span id="address_error"></span>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city" value="<?= $user_data['city'] ?>" data-validation="required min" data-min="2">
                                    <span id="city_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="state" class="form-label">State</label>
                                    <input type="text" class="form-control" id="state" name="state" value="<?= $user_data['state'] ?>" data-validation="required min" data-min="2">
                                    <span id="state_error"></span>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="pincode" class="form-label">Pin Code</label>
                                    <input type="text" class="form-control" id="pincode" name="pincode" value="<?= $user_data['pincode'] ?>" data-validation="required number min max" data-max="6" data-min="6">
                                    <span id="pincode_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="country" class="form-label">Country</label>
                                    <input type="text" class="form-control" id="country" name="country" value="<?= $user_data['country'] ?>" data-validation="required max min alphabetic" data-min="2" data-max="10">
                                    <sapn id="country_error"></sapn>
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
                         <?php if (!empty($passwordSuccess)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($passwordSuccess) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($passwordErrors)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    <?php foreach ($passwordErrors as $err): ?>
                                        <li><?= htmlspecialchars($err) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Password must be at least 8 characters long and include uppercase, lowercase, number, and
                            special character.
                        </div>

                        <form action="change_password_process.php" method="POST">
                            <div class="mb-3">
                                <label for="currentPassword" class="form-label">Current Password *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="currentPassword" name="currentPassword"
                                        data-validation="required strongPassword">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('currentPassword')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <span id="currentPassword_error"></span>
                            </div>

                            <div class="mb-3">
                                <label for="newPassword" class="form-label">New Password *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="newPassword" name="newPassword"
                                        data-validation="required strongPassword">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('newPassword')">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                </div>

                                <span id="newPassword_error"></span>

                            </div>

                            <div class="mb-4">
                                <label for="confirmPassword" class="form-label">Confirm New Password *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirmPassword"
                                        name="confirmPassword" data-validation="required">
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
            <div id="orders" class="content-section" style="display: none;">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">My Orders</h4>
                        <div>
                            <select class="form-select form-select-sm">
                                <option>All Orders</option>
                                <option>Pending</option>
                                <option>Delivered</option>
                                <option>Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Order Item 1 -->
                        <div class="order-item border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-md-2">
                                    <img src="https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=200&q=80"
                                        alt="Product" class="img-fluid rounded">
                                </div>
                                <div class="col-md-7">
                                    <h6>Modern Velvet Accent Chair</h6>
                                    <p class="text-muted mb-1">Color: Navy Blue | Qty: 1</p>
                                    <p class="mb-1"><strong>Order ID:</strong> #ORD-2026-001234</p>
                                    <p class="mb-0"><small class="text-muted">Ordered on: Jan 25, 2026</small></p>
                                </div>
                                <div class="col-md-3 text-end">
                                    <h5 class="text-primary mb-2">₹12,999</h5>
                                    <span class="badge bg-success mb-2">Delivered</span>
                                    <div>
                                        <button class="btn btn-sm btn-outline-primary">Track Order</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Item 2 -->
                        <div class="order-item border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-md-2">
                                    <img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=200&q=80"
                                        alt="Product" class="img-fluid rounded">
                                </div>
                                <div class="col-md-7">
                                    <h6>Luxury Sofa Set</h6>
                                    <p class="text-muted mb-1">Color: Grey | Qty: 1</p>
                                    <p class="mb-1"><strong>Order ID:</strong> #ORD-2026-001235</p>
                                    <p class="mb-0"><small class="text-muted">Ordered on: Jan 30, 2026</small></p>
                                </div>
                                <div class="col-md-3 text-end">
                                    <h5 class="text-primary mb-2">₹45,999</h5>
                                    <span class="badge bg-warning mb-2">In Transit</span>
                                    <div>
                                        <button class="btn btn-sm btn-outline-primary">Track Order</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Item 3 -->
                        <div class="order-item border rounded p-3">
                            <div class="row">
                                <div class="col-md-2">
                                    <img src="https://images.unsplash.com/photo-1503602642458-232111445657?w=200&q=80"
                                        alt="Product" class="img-fluid rounded">
                                </div>
                                <div class="col-md-7">
                                    <h6>Wooden Dining Chair (Set of 4)</h6>
                                    <p class="text-muted mb-1">Color: Walnut | Qty: 4</p>
                                    <p class="mb-1"><strong>Order ID:</strong> #ORD-2026-001236</p>
                                    <p class="mb-0"><small class="text-muted">Ordered on: Feb 01, 2026</small></p>
                                </div>
                                <div class="col-md-3 text-end">
                                    <h5 class="text-primary mb-2">₹24,999</h5>
                                    <span class="badge bg-info mb-2">Processing</span>
                                    <div>
                                        <button class="btn btn-sm btn-outline-primary">Track Order</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                                    <p class="mb-1"><strong>Jay Santoki</strong></p>
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
                                    <p class="mb-1"><strong>Jay Santoki</strong></p>
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

            <!-- Wishlist Section -->
            <div id="wishlist" class="content-section" style="display: none;">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">My Wishlist</h4>
                        <span class="badge bg-primary" id="wishlistCountBadge">0 items</span>
                    </div>
                    <div class="card-body">
                        <div class="row" id="wishlistContainer">
                            <!-- Wishlist items loaded dynamically -->
                        </div>
                        <div id="wishlistEmpty" class="text-center py-5" style="display: none;">
                            <i class="far fa-heart fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Your wishlist is empty</h5>
                            <p class="text-muted mb-3">Browse our products and add items you love!</p>
                            <a href="shop.php" class="btn btn-primary">Browse Products</a>
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
    // Show/Hide Sections
    function showSection(sectionId) {
        // Hide all sections
        const sections = document.querySelectorAll('.content-section');
        sections.forEach(section => {
            section.style.display = 'none';
        });

        // Show selected section
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

    // Profile Photo Upload
    document.getElementById('photoUpload').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('profilePhoto').src = e.target.result;
                showNotification('Profile photo updated successfully!', 'success');
            };
            reader.readAsDataURL(file);
        }
    });

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

    // Password Strength Checker


    // Password Match Checker


    // Show Notification
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Initialize - Show section based on URL hash or default to profile
    document.addEventListener('DOMContentLoaded', function () {
        const hash = window.location.hash.replace('#', '');
        const validSections = ['profile', 'edit-profile', 'change-password', 'orders', 'addresses', 'wishlist'];
        if (hash && validSections.includes(hash)) {
            showSection(hash);
        } else {
            showSection('profile');
        }
        loadWishlist();
    });

    // ── Wishlist Functions ──
    function loadWishlist() {
        $.ajax({
            url: 'backend/wishlist_action.php',
            type: 'POST',
            dataType: 'json',
            data: { action: 'list' },
            success: function(response) {
                if (response.success) {
                    renderWishlist(response.items);
                    updateWishlistCounts(response.wishlist_count);
                }
            }
        });
    }

    function renderWishlist(items) {
        const container = document.getElementById('wishlistContainer');
        const emptyState = document.getElementById('wishlistEmpty');

        if (!items || items.length === 0) {
            container.innerHTML = '';
            emptyState.style.display = 'block';
            return;
        }

        emptyState.style.display = 'none';
        let html = '';
        items.forEach(item => {
            const price = parseFloat(item.price).toFixed(2);
            const originalPrice = item.original_price ? parseFloat(item.original_price).toFixed(2) : null;
            const inStock = parseInt(item.stock) > 0;

            html += `
            <div class="col-md-4 mb-4" id="wishlist-item-${item.product_id}">
                <div class="wishlist-card border rounded overflow-hidden" style="transition: .3s all ease;">
                    <div class="position-relative">
                        <a href="product_detail.php?id=${item.product_id}">
                            <img src="images/${item.image}" alt="${item.product_name}" 
                                 class="img-fluid" style="width: 100%; height: 200px; object-fit: contain; background: #f8f9fa; padding: 10px;">
                        </a>
                        <button class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle" 
                                style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;"
                                onclick="removeFromWishlist(${item.product_id})" title="Remove from Wishlist">
                            <i class="fas fa-times" style="font-size: 12px;"></i>
                        </button>
                        ${!inStock ? '<span class="badge bg-secondary position-absolute bottom-0 start-0 m-2">Out of Stock</span>' : ''}
                    </div>
                    <div class="p-3">
                        <a href="product_detail.php?id=${item.product_id}" class="text-decoration-none">
                            <h6 class="mb-1 text-dark">${item.product_name}</h6>
                        </a>
                        <div class="mb-2">
                            <strong class="text-primary">₹${price}</strong>
                            ${originalPrice ? `<small class="text-muted text-decoration-line-through ms-1">₹${originalPrice}</small>` : ''}
                        </div>
                        <button class="btn btn-primary btn-sm w-100" ${!inStock ? 'disabled' : ''}
                                onclick="addWishlistToCart(${item.product_id})">
                            <i class="fas fa-shopping-cart me-2"></i>${inStock ? 'Add to Cart' : 'Out of Stock'}
                        </button>
                    </div>
                </div>
            </div>`;
        });
        container.innerHTML = html;
    }

    function updateWishlistCounts(count) {
        const badge = document.getElementById('wishlistCountBadge');
        const stat = document.getElementById('wishlistStatCount');
        if (badge) badge.textContent = count + ' item' + (count !== 1 ? 's' : '');
        if (stat) stat.textContent = count;
    }

    function removeFromWishlist(productId) {
        $.ajax({
            url: 'backend/wishlist_action.php',
            type: 'POST',
            dataType: 'json',
            data: { action: 'remove', product_id: productId },
            success: function(response) {
                if (response.success) {
                    const el = document.getElementById('wishlist-item-' + productId);
                    if (el) {
                        el.style.transform = 'scale(0.8)';
                        el.style.opacity = '0';
                        setTimeout(() => {
                            el.remove();
                            // Check if empty
                            const container = document.getElementById('wishlistContainer');
                            if (container.children.length === 0) {
                                document.getElementById('wishlistEmpty').style.display = 'block';
                            }
                        }, 300);
                    }
                    updateWishlistCounts(response.wishlist_count);
                    showNotification('Removed from wishlist');
                }
            }
        });
    }

    function addWishlistToCart(productId) {
        $.ajax({
            url: 'backend/cart_action.php',
            type: 'POST',
            dataType: 'json',
            data: { action: 'add', id: productId, qty: 1, color: '' },
            success: function(response) {
                if (response.success) {
                    showNotification('Added to cart!', 'success');
                } else {
                    showNotification(response.message || 'Failed to add to cart', 'danger');
                }
            },
            error: function() {
                showNotification('Something went wrong', 'danger');
            }
        });
    }

</script>


<?php include 'footer.php'; ?>