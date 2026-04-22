<!-- /*
* Bootstrap 5
* Template Name: Furni
* Template Author: Untree.co
* Template URI: https://untree.co/
* License: https://creativecommons.org/licenses/by/3.0/
*/ -->
<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Untree.co">
	<link rel="shortcut icon" href="favicon.png">

	<meta name="description" content="" />
	<meta name="keywords" content="bootstrap, bootstrap4" />

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
	<link href="css/tiny-slider.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-4.0.0.js"></script>
	<title>Furni Free Bootstrap 5 Template for Furniture and Interior Design Websites by Untree.co </title>
</head>

<body>

	<!-- Start Header/Navigation -->
	<nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">

		<div class="container">
			<a class="navbar-brand" href="index.php">Furni<span>.</span></a>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni"
				aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarsFurni">
				<ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
					<li class="nav-item <?php echo (isset($page) && $page == 'home') ? 'active' : ''; ?>">
						<a class="nav-link" href="index.php">Home</a>
					</li>
					<li class="nav-item <?php echo (isset($page) && $page == 'shop') ? 'active' : ''; ?>"><a
							class="nav-link" href="shop.php">Shop</a></li>
					<li class="nav-item <?php echo (isset($page) && $page == 'about') ? 'active' : ''; ?>"><a
							class="nav-link" href="about.php">About us</a></li>
					<li class="nav-item <?php echo (isset($page) && $page == 'services') ? 'active' : ''; ?>"><a
							class="nav-link" href="services.php">Services</a></li>
					<li class="nav-item <?php echo (isset($page) && $page == 'blog') ? 'active' : ''; ?>"><a
							class="nav-link" href="blog.php">Blog</a></li>
					<li class="nav-item <?php echo (isset($page) && $page == 'contact') ? 'active' : ''; ?>"><a
							class="nav-link" href="contact.php">Contact us</a></li>
				</ul>

				<ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5=">
					<li class="nav-item <?php echo (isset($page) && $page == 'login') ? 'active' : ''; ?>"><a
							class="nav-link" href="login.php"><img src="images/user.svg"></a></li>
					<li class="nav-item"><a class="nav-link" href="profile.php#wishlist" onclick="<?php echo (!empty($_SESSION['is_logged_in'])) ? '' : "window.location.href='login.php'; return false;"; ?>"><i class="fas fa-heart" style="font-size: 18px; color: rgba(255,255,255,0.5); transition: .3s;"></i></a></li>
					<li class="nav-item <?php echo (isset($page) && $page == 'cart') ? 'active' : ''; ?>"><a
							class="nav-link" href="cart.php"><img src="images/cart.svg"></a></li>
				</ul>
			</div>
		</div>

	</nav>
	<!-- End Header/Navigation -->