<?php 
$page = 'shop';
include 'config/db_config.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Get wishlisted product IDs for current user
$wishlisted_ids = [];
if (!empty($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    $wl_user = intval($_SESSION['user_id']);
    $wl_result = mysqli_query($connection, "SELECT product_id FROM wishlist WHERE user_id = $wl_user");
    if ($wl_result) {
        while ($wl_row = mysqli_fetch_assoc($wl_result)) {
            $wishlisted_ids[] = intval($wl_row['product_id']);
        }
    }
}

include 'header.php';

$shop_banner = "SELECT * FROM banners WHERE title='Shop'";
$shop_banner_result = mysqli_query($connection, $shop_banner);
$shop_banner_data = [];
if ($shop_banner_result) {
    while ($row = mysqli_fetch_assoc($shop_banner_result)) {
        $shop_banner_data = $row;
    }
}


?>

		<!-- Start Hero Section -->
			<div class="hero">
				<div class="container">
					<div class="row justify-content-between">
						<div class="col-lg-5">
							<div class="intro-excerpt">
								<h1><?php echo $shop_banner_data['title'] ?></h1>
								<p class="mb-4"><?php echo $shop_banner_data['description'] ?></p>
								<p><a href="shop.php" class="btn btn-secondary me-2">Shop Now</a><a href="shop.php" class="btn btn-white-outline">Explore</a></p>
							</div>
						</div>
						<div class="col-lg-7">
							<div class="hero-img-wrap">
								<img src="images/<?php echo $shop_banner_data['banner_image'] ?>" class="img-fluid">
							</div>
						</div>
					</div>
				</div>
			</div>
		<!-- End Hero Section -->

		

		<div class="untree_co-section product-section before-footer-section">
		    <div class="container">
		      	<div class="row">

					<?php
					$query = "SELECT * FROM products WHERE status = 'active' ORDER BY id DESC";
					$result = mysqli_query($connection, $query);

					if ($result && mysqli_num_rows($result) > 0) {
						while ($product = mysqli_fetch_assoc($result)) {
							?>
							<div class="col-12 col-md-4 col-lg-3 mb-5">
								<a class="product-item" href="product_detail.php?id=<?php echo $product['id']; ?>">
									<img src="images/<?php echo htmlspecialchars($product['image']); ?>" class="img-fluid product-thumbnail" style="height: 250px; object-fit: contain;">
									<h3 class="product-title"><?php echo htmlspecialchars($product['product_name']); ?></h3>
									<strong class="product-price">$<?php echo number_format($product['price'], 2); ?></strong>

									<span class="icon-cross" onclick="addToCartEvent(event, <?php echo $product['id']; ?>)">
										<img src="images/cross.svg" class="img-fluid">
									</span>

									<?php $is_wl = in_array($product['id'], $wishlisted_ids); ?>
									<span class="icon-wishlist" onclick="toggleWishlistShop(event, <?php echo $product['id']; ?>, this)" title="<?php echo $is_wl ? 'Remove from Wishlist' : 'Add to Wishlist'; ?>">
										<i class="<?php echo $is_wl ? 'fas' : 'far'; ?> fa-heart"></i>
									</span>
								</a>
							</div>
							<?php
						}
					} else {
						echo "<div class='col-12 text-center'><p>No products available at the moment.</p></div>";
					}
					?>

		      	</div>
		    </div>
		</div>

<script>
function addToCartEvent(event, productId) {
	// Prevent navigating to the product detail page
	event.preventDefault();
	event.stopPropagation();

	$.ajax({
		url: 'backend/cart_action.php',
		type: 'POST',
		dataType: 'json',
		data: {
			action: 'add',
			id: productId,
			qty: 1,
			color: ''
		},
		success: function(response) {
			if (response.success) {
				// Show notification or alert
				showNotification(response.message || 'Added to cart');
			} else {
				alert(response.message || 'Failed to add item');
			}
		},
		error: function() {
			alert('Something went wrong. Please try again.');
		}
	});
}

function showNotification(message) {
	const notification = document.createElement('div');
	notification.className = 'toast-notification d-flex align-items-center bg-dark text-white p-3 rounded';
	notification.style.position = 'fixed';
	notification.style.bottom = '20px';
	notification.style.right = '20px';
	notification.style.zIndex = '9999';
	notification.innerHTML = `<i class="fas fa-check-circle me-2 text-success"></i> <span>${message}</span>`;
	
	document.body.appendChild(notification);
	
	setTimeout(() => {
		notification.style.transition = 'opacity 0.5s ease';
		notification.style.opacity = '0';
		setTimeout(() => notification.remove(), 500);
	}, 3000);
}

function toggleWishlistShop(event, productId, el) {
	event.preventDefault();
	event.stopPropagation();

	$.ajax({
		url: 'backend/wishlist_action.php',
		type: 'POST',
		dataType: 'json',
		data: { action: 'toggle', product_id: productId },
		success: function(response) {
			if (response.login_required) {
				window.location.href = 'login.php';
				return;
			}
			if (response.success) {
				const icon = el.querySelector('i');
				if (response.action === 'added') {
					icon.classList.remove('far');
					icon.classList.add('fas');
					el.title = 'Remove from Wishlist';
				} else {
					icon.classList.remove('fas');
					icon.classList.add('far');
					el.title = 'Add to Wishlist';
				}
				// Animate
				el.style.transform = 'scale(1.4)';
				setTimeout(() => el.style.transform = 'scale(1)', 200);
				showNotification(response.message);
			} else {
				alert(response.message);
			}
		},
		error: function() {
			alert('Something went wrong. Please try again.');
		}
	});
}
</script>

<?php include 'footer.php'; ?>
