<?php
session_start();
require_once '../config/db_config.php';
require_once '../config/mail_config.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../register.php');
    exit;
}

// ── 1. Collect & sanitize input ─────────────────────────────────────────────
$firstName = trim(mysqli_real_escape_string($connection, $_POST['firstName'] ?? ''));
$lastName = trim(mysqli_real_escape_string($connection, $_POST['lastName'] ?? ''));
$email = trim(mysqli_real_escape_string($connection, $_POST['email'] ?? ''));
$phone = trim(mysqli_real_escape_string($connection, $_POST['phone'] ?? ''));
$gender = trim(mysqli_real_escape_string($connection, $_POST['gender'] ?? ''));
$address = trim(mysqli_real_escape_string($connection, $_POST['address'] ?? ''));
$city = trim(mysqli_real_escape_string($connection, $_POST['city'] ?? ''));
$state = trim(mysqli_real_escape_string($connection, $_POST['state'] ?? ''));
$pincode = trim(mysqli_real_escape_string($connection, $_POST['pincode'] ?? ''));
$country = trim(mysqli_real_escape_string($connection, $_POST['country'] ?? ''));
$password = $_POST['password'] ?? '';
$confirmPwd = $_POST['confirmPassword'] ?? '';

// ── 2. Basic server-side validation ─────────────────────────────────────────
$errors = [];

if (empty($firstName) || empty($lastName))
    $errors[] = "First and last name are required.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    $errors[] = "Invalid email address.";
if ($password !== $confirmPwd)
    $errors[] = "Passwords do not match.";
if (strlen($password) < 8)
    $errors[] = "Password must be at least 8 characters.";

// Duplicate email check
$checkEmail = mysqli_query($connection, "SELECT id FROM users WHERE email = '$email' LIMIT 1");
if (mysqli_num_rows($checkEmail) > 0) {
    $errors[] = "An account with this email already exists.";
}

// ── 3. Profile photo upload ──────────────────────────────────────────────────
$profilePhotoPath = null;

if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/public/images/profile_photo/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5 MB

    $fileType = mime_content_type($_FILES['profile_photo']['tmp_name']);
    $fileSize = $_FILES['profile_photo']['size'];
    $fileExt = strtolower(pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION));

    if (!in_array($fileType, $allowedTypes)) {
        $errors[] = "Profile photo must be a JPG, PNG, GIF, or WEBP image.";
    }
    elseif ($fileSize > $maxSize) {
        $errors[] = "Profile photo must be smaller than 5 MB.";
    }
    else {
        // Generate unique filename
        $newFileName = uniqid('photo_', true) . '.' . $fileExt;
        $destinationPath = $uploadDir . $newFileName;

        if (!move_uploaded_file($_FILES['profile_photo']['tmp_name'], $destinationPath)) {
            $errors[] = "Failed to upload profile photo. Please try again.";
        }
        else {
            // Store only the relative path
            $profilePhotoPath = 'public/images/profile_photo/' . $newFileName;
        }
    }
}

// ── 4. Abort if any errors ───────────────────────────────────────────────────
if (!empty($errors)) {
    $_SESSION['register_errors'] = $errors;
    $_SESSION['register_old'] = $_POST; // repopulate fields
    header('Location: ../register.php');
    exit;
}

// ── 5. Hash password ─────────────────────────────────────────────────────────
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// ── 6. Generate email verification token ─────────────────────────────────────
$verificationToken = bin2hex(random_bytes(32));
$tokenExpiry = date('Y-m-d H:i:s', strtotime('+24 hours'));
$isVerified = 0;

// ── 7. Insert user into database ─────────────────────────────────────────────
$photoSql = $profilePhotoPath ? "'$profilePhotoPath'" : "NULL";

$sql = "INSERT INTO users 
        (first_name, last_name, email, phone, gender, address, city, state, pincode, country,
         password, profile_photo, verification_token, token_expiry, is_verified, created_at)
        VALUES 
        ('$firstName', '$lastName', '$email', '$phone', '$gender', '$address', '$city', '$state',
         '$pincode', '$country', '$hashedPassword', $photoSql,
         '$verificationToken', '$tokenExpiry', $isVerified, NOW())";

if (!mysqli_query($connection, $sql)) {
    $_SESSION['register_errors'] = ["Registration failed: " . mysqli_error($connection)];
    header('Location: ../register.php');
    exit;
}

// ── 8. Send verification email via PHPMailer + SMTP ─────────────────────────
$verifyLink = "http://" . $_SERVER['HTTP_HOST'] . "/verify_email.php?token=$verificationToken";

try {
    $mail = getMailer(); // from config/mail_config.php
    $mail->addAddress($email, "$firstName $lastName");
    $mail->Subject = 'Verify your Furni account';
    $mail->Body = "
    <html>
    <body style='font-family: Arial, sans-serif; color: #333;'>
      <h2>Welcome to Furni, $firstName!</h2>
      <p>Thank you for registering. Please click the button below to verify your email address.</p>
      <p style='margin: 30px 0;'>
        <a href='$verifyLink'
           style='background:#3d5a80;color:#fff;padding:12px 28px;text-decoration:none;border-radius:4px;'>
          Verify Email Address
        </a>
      </p>
      <p style='color:#888;font-size:13px;'>This link will expire in 24 hours.</p>
      <p style='color:#888;font-size:13px;'>If you did not create an account, you can safely ignore this email.</p>
    </body>
    </html>
    ";
    $mail->AltBody = "Welcome to Furni, $firstName! Verify your email: $verifyLink (expires in 24 hours)";
    $mail->send();
}
catch (Exception $e) {
    // Don't block registration — just warn. Check mail_config.php credentials if this persists.
    error_log('Mailer Error: ' . $e->getMessage());
}

// ── 9. Redirect with success message ─────────────────────────────────────────
$_SESSION['register_success'] = "Registration successful! A verification link has been sent to <strong>$email</strong>. Please check your inbox.";
header('Location: ../register.php');
exit;
