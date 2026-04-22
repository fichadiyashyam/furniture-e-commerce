<?php
$page = 'product_detail';
include 'config/db_config.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'header.php';

// Check if product is in wishlist
$is_wishlisted = false;
if (!empty($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    $wl_user = intval($_SESSION['user_id']);
    // We'll check after we have the product id
}

if (!isset($_GET['id'])) {
    echo "<script>window.location.href='shop.php';</script>";
    exit();
}

$id = intval($_GET['id']);
$query = "SELECT * FROM products WHERE id = $id AND status = 'active'";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<div class='container my-5'><h2>Product not found.</h2><a href='shop.php' class='btn btn-primary'>Back to Shop</a></div>";
    include 'footer.php';
    exit();
}
$product = mysqli_fetch_assoc($result);

// Check wishlist status
if (!empty($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    $wl_user = intval($_SESSION['user_id']);
    $wl_check = mysqli_query($connection, "SELECT id FROM wishlist WHERE user_id = $wl_user AND product_id = $id LIMIT 1");
    $is_wishlisted = ($wl_check && mysqli_num_rows($wl_check) > 0);
}
?>

<link rel="stylesheet" href="css/product_detail.css">
    <!-- Product Details Section -->

    <!-- Product Details Section -->
    <div class="container my-5">
        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-6 mb-4">
                <div class="product-images">
                    <div class="main-image mb-3">
                        <img id="mainImage" src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="img-fluid rounded" style="width: 100%; object-fit: contain;">
                    </div>
                    <div class="thumbnail-images d-flex gap-2">
                        <img src="images/<?php echo htmlspecialchars($product['image']); ?>" class="img-thumbnail thumbnail" onclick="changeImage(this)" style="width: 80px; height: 80px; object-fit: cover;">
                        <?php if (!empty($product['gallery_image_1'])): ?>
                            <img src="images/<?php echo htmlspecialchars($product['gallery_image_1']); ?>" class="img-thumbnail thumbnail" onclick="changeImage(this)" style="width: 80px; height: 80px; object-fit: cover;">
                        <?php endif; ?>
                        <?php if (!empty($product['gallery_image_2'])): ?>
                            <img src="images/<?php echo htmlspecialchars($product['gallery_image_2']); ?>" class="img-thumbnail thumbnail" onclick="changeImage(this)" style="width: 80px; height: 80px; object-fit: cover;">
                        <?php endif; ?>
                        <?php if (!empty($product['gallery_image_3'])): ?>
                            <img src="images/<?php echo htmlspecialchars($product['gallery_image_3']); ?>" class="img-thumbnail thumbnail" onclick="changeImage(this)" style="width: 80px; height: 80px; object-fit: cover;">
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="product-info">
                    <h1 class="product-title mb-3"><?php echo htmlspecialchars($product['product_name']); ?></h1>

                    <!-- Rating -->
                    <div class="rating mb-3">
                        <span class="stars">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star-half-alt text-warning"></i>
                        </span>
                        <span class="ms-2">(4.5/5) - <a href="#reviews">96 Reviews</a></span>
                    </div>

                    <!-- Price -->
                    <div class="price mb-3">
                        <span class="current-price h2 text-danger">₹<?php echo number_format($product['price'], 2); ?></span>
                        <?php if (!empty($product['original_price'])): ?>
                            <span class="original-price text-muted text-decoration-line-through ms-2">₹<?php echo number_format($product['original_price'], 2); ?></span>
                            <?php 
                            $discount = round((($product['original_price'] - $product['price']) / $product['original_price']) * 100);
                            ?>
                            <span class="badge bg-success ms-2"><?php echo $discount; ?>% OFF</span>
                        <?php endif; ?>
                    </div>

                    <!-- Short Description -->
                    <p class="product-short-desc mb-4">
                        <?php echo !empty($product['short_description']) ? nl2br(htmlspecialchars($product['short_description'])) : htmlspecialchars(substr($product['description'], 0, 150)) . '...'; ?>
                    </p>

                    <!-- Product Options -->
                    <div class="product-options mb-4">
                        <!-- Color Selection -->
                        <?php if (!empty($product['color_options'])): ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Color:</label>
                            <div class="color-options d-flex gap-2 flex-wrap">
                                <?php 
                                $colors = explode(',', $product['color_options']);
                                foreach ($colors as $index => $color): 
                                    $color = trim($color);
                                    $id = "color_" . $index;
                                ?>
                                    <input type="radio" class="btn-check" name="color" id="<?php echo $id; ?>" <?php echo $index === 0 ? 'checked' : ''; ?>>
                                    <label class="btn btn-outline-secondary" for="<?php echo $id; ?>"><?php echo htmlspecialchars($color); ?></label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Quantity -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Quantity:</label>
                            <div class="quantity-selector d-flex align-items-center">
                                <button class="btn btn-outline-secondary" onclick="decreaseQuantity()">-</button>
                                <input type="number" id="quantity" class="form-control mx-2 text-center" value="1" min="1" max="<?php echo filter_var($product['stock'], FILTER_VALIDATE_INT) > 0 ? $product['stock'] : 1; ?>" style="width: 80px;">
                                <button class="btn btn-outline-secondary" onclick="increaseQuantity()">+</button>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons d-flex gap-3 mb-4">
                        <?php if ($product['stock'] > 0): ?>
                            <button class="btn btn-primary btn-lg flex-grow-1" onclick="addToCart(<?php echo $product['id']; ?>)">
                                <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                            </button>
                        <?php else: ?>
                            <button class="btn btn-secondary btn-lg flex-grow-1" disabled>
                                <i class="fas fa-shopping-cart me-2"></i>Out of Stock
                            </button>
                        <?php endif; ?>
                        
                        <button class="btn btn-outline-danger btn-lg wishlist-btn" id="wishlistBtn" onclick="toggleWishlist(<?php echo $product['id']; ?>)" title="<?php echo $is_wishlisted ? 'Remove from Wishlist' : 'Add to Wishlist'; ?>">
                            <i class="<?php echo $is_wishlisted ? 'fas' : 'far'; ?> fa-heart"></i>
                        </button>
                    </div>

                    <!-- Product Meta -->
                    <div class="product-meta">
                        <?php if (!empty($product['sku'])): ?>
                            <p><strong>SKU:</strong> <?php echo htmlspecialchars($product['sku']); ?></p>
                        <?php endif; ?>
                        <p><strong>Category:</strong> <?php echo ucfirst(htmlspecialchars($product['category'])); ?></p>
                        <?php if (!empty($product['material'])): ?>
                            <p><strong>Material:</strong> <?php echo htmlspecialchars($product['material']); ?></p>
                        <?php endif; ?>
                        <p><strong>Availability:</strong> 
                            <?php if ($product['stock'] > 0): ?>
                                <span class="text-success">In Stock (<?php echo $product['stock']; ?> available)</span>
                            <?php else: ?>
                                <span class="text-danger">Out of Stock</span>
                            <?php endif; ?>
                        </p>
                        <p><strong>Delivery:</strong> Free delivery within 7-10 business days</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details Tabs -->
        <div class="row mt-5">
            <div class="col-12">
                <ul class="nav nav-tabs" id="productTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button">
                            Description
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button">
                            Specifications
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">
                            Reviews (96)
                        </button>
                    </li>
                </ul>

                <div class="tab-content p-4 border border-top-0" id="productTabContent">
                    <!-- Description Tab -->
                    <div class="tab-pane fade show active" id="description">
                        <h3>Product Description</h3>
                        <p>
                            <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                        </p>
                        <h5>Key Features:</h5>
                        <ul>
                            <li>Premium quality velvet fabric with soft texture</li>
                            <li>Sturdy solid wood legs with natural finish</li>
                            <li>Ergonomic design with comfortable backrest</li>
                            <li>High-density foam cushioning for optimal comfort</li>
                            <li>Easy to assemble with included hardware</li>
                            <li>Weight capacity: 120 kg</li>
                            <li>Perfect for living room, bedroom, or office</li>
                            <li>Available in multiple elegant colors</li>
                        </ul>
                        <h5>Care Instructions:</h5>
                        <ul>
                            <li>Vacuum regularly to remove dust</li>
                            <li>Spot clean stains immediately with mild soap and water</li>
                            <li>Avoid direct sunlight to prevent fading</li>
                            <li>Professional cleaning recommended for deep stains</li>
                        </ul>
                    </div>

                    <!-- Specifications Tab -->
                    <div class="tab-pane fade" id="specifications">
                        <h3>Technical Specifications</h3>
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td><strong>Dimensions</strong></td>
                                    <td><?php echo !empty($product['dimensions']) ? htmlspecialchars($product['dimensions']) : 'N/A'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Weight</strong></td>
                                    <td><?php echo !empty($product['weight']) ? htmlspecialchars($product['weight']) : 'N/A'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Material</strong></td>
                                    <td><?php echo !empty($product['material']) ? htmlspecialchars($product['material']) : 'N/A'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Category</strong></td>
                                    <td><?php echo ucfirst(htmlspecialchars($product['category'])); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Assembly Required</strong></td>
                                    <td>Yes (Included instructions)</td>
                                </tr>
                                <tr>
                                    <td><strong>Warranty</strong></td>
                                    <td>1 Year Manufacturing Defect</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Reviews Tab -->
                    <div class="tab-pane fade" id="reviews">
                        <h3>Customer Reviews</h3>

                        <!-- Overall Rating -->
                        <div class="overall-rating mb-4 p-4 bg-light rounded">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-center">
                                    <h1 class="display-3 mb-0">4.5</h1>
                                    <div class="stars mb-2">
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star-half-alt text-warning"></i>
                                    </div>
                                    <p class="text-muted">Based on 96 reviews</p>
                                </div>
                                <div class="col-md-9">
                                    <div class="rating-breakdown">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="me-2">5★</span>
                                            <div class="progress flex-grow-1 me-2">
                                                <div class="progress-bar bg-warning" style="width: 65%"></div>
                                            </div>
                                            <span>65%</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="me-2">4★</span>
                                            <div class="progress flex-grow-1 me-2">
                                                <div class="progress-bar bg-warning" style="width: 25%"></div>
                                            </div>
                                            <span>25%</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="me-2">3★</span>
                                            <div class="progress flex-grow-1 me-2">
                                                <div class="progress-bar bg-warning" style="width: 7%"></div>
                                            </div>
                                            <span>7%</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="me-2">2★</span>
                                            <div class="progress flex-grow-1 me-2">
                                                <div class="progress-bar bg-warning" style="width: 2%"></div>
                                            </div>
                                            <span>2%</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">1★</span>
                                            <div class="progress flex-grow-1 me-2">
                                                <div class="progress-bar bg-warning" style="width: 1%"></div>
                                            </div>
                                            <span>1%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Individual Reviews -->
                        <div class="reviews-list mb-4">
                            <!-- Review 1 -->
                            <div class="review-item border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <strong>Priya Sharma</strong>
                                        <div class="stars">
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                        </div>
                                    </div>
                                    <small class="text-muted">Jan 28, 2026</small>
                                </div>
                                <h6>Beautiful and Comfortable!</h6>
                                <p class="text-muted">
                                    This chair is absolutely stunning! The navy blue velvet looks luxurious and the comfort
                                    level is amazing. Easy to assemble and the quality is top-notch. Highly recommend!
                                </p>
                            </div>

                            <!-- Review 2 -->
                            <div class="review-item border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <strong>Rajesh Kumar</strong>
                                        <div class="stars">
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="far fa-star text-warning"></i>
                                        </div>
                                    </div>
                                    <small class="text-muted">Jan 25, 2026</small>
                                </div>
                                <h6>Great value for money</h6>
                                <p class="text-muted">
                                    Very good quality chair for the price. The wooden legs are sturdy and the velvet fabric
                                    feels premium. Perfect for my reading corner. Delivery was also on time.
                                </p>
                            </div>

                            <!-- Review 3 -->
                            <div class="review-item pb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <strong>Neha Patel</strong>
                                        <div class="stars">
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                        </div>
                                    </div>
                                    <small class="text-muted">Jan 20, 2026</small>
                                </div>
                                <h6>Exceeded expectations!</h6>
                                <p class="text-muted">
                                    I was skeptical about ordering furniture online but this chair proved me wrong.
                                    The pink color is exactly as shown in pictures. Very comfortable and stylish.
                                    Love it!
                                </p>
                            </div>
                        </div>

                        <!-- Add Review Form -->
                        <div class="add-review-form">
                            <h4>Write a Review</h4>
                            <form id="reviewForm" class="mt-3">
                                <div class="mb-3">
                                    <label class="form-label">Your Rating *</label>
                                    <div class="star-rating">
                                        <input type="radio" name="rating" value="5" id="star5">
                                        <label for="star5"><i class="fas fa-star"></i></label>
                                        <input type="radio" name="rating" value="4" id="star4">
                                        <label for="star4"><i class="fas fa-star"></i></label>
                                        <input type="radio" name="rating" value="3" id="star3">
                                        <label for="star3"><i class="fas fa-star"></i></label>
                                        <input type="radio" name="rating" value="2" id="star2">
                                        <label for="star2"><i class="fas fa-star"></i></label>
                                        <input type="radio" name="rating" value="1" id="star1">
                                        <label for="star1"><i class="fas fa-star"></i></label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="reviewName" class="form-label">Name *</label>
                                        <input type="text" class="form-control" id="reviewName" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="reviewEmail" class="form-label">Email *</label>
                                        <input type="email" class="form-control" id="reviewEmail" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="reviewTitle" class="form-label">Review Title *</label>
                                    <input type="text" class="form-control" id="reviewTitle" required>
                                </div>

                                <div class="mb-3">
                                    <label for="reviewText" class="form-label">Your Review *</label>
                                    <textarea class="form-control" id="reviewText" rows="4" required></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">Submit Review</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Bootstrap JS -->
    <script src="<https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js>"></script>
    <script>
        // Change main product image
        function changeImage(thumbnail) {
            const mainImage = document.getElementById('mainImage');
            mainImage.src = thumbnail.src;

            // Remove active class from all thumbnails
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.style.borderColor = 'transparent';
            });

            // Add active class to clicked thumbnail
            thumbnail.style.borderColor = '#0d6efd';
        }

        // Quantity functions
        function increaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            let currentValue = parseInt(quantityInput.value);
            if (currentValue < parseInt(quantityInput.max)) {
                quantityInput.value = currentValue + 1;
            }
        }

        function decreaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > parseInt(quantityInput.min)) {
                quantityInput.value = currentValue - 1;
            }
        }

        // Add to cart function
        function addToCart(productId) {
            const quantity = document.getElementById('quantity').value;
            let selectedColor = '';
            
            const colorOption = document.querySelector('input[name="color"]:checked');
            if(colorOption) {
                selectedColor = colorOption.nextElementSibling.textContent;
            }

            $.ajax({
                url: 'backend/cart_action.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'add',
                    id: productId,
                    qty: quantity,
                    color: selectedColor
                },
                success: function(response) {
                    if (response.success) {
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

        // Show notification
        function showNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'toast-notification';
            notification.innerHTML = `
        <i class="fas fa-check-circle me-2"></i>
        ${message}
    `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideIn 0.3s ease-out reverse';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }

        // Review form submission
        document.getElementById('reviewForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const rating = document.querySelector('input[name="rating"]:checked');
            const name = document.getElementById('reviewName').value;
            const email = document.getElementById('reviewEmail').value;
            const title = document.getElementById('reviewTitle').value;
            const reviewText = document.getElementById('reviewText').value;

            if (!rating) {
                alert('Please select a rating');
                return;
            }

            // Here you would send this data to your backend
            console.log('Review submitted:', {
                rating: rating.value,
                name: name,
                email: email,
                title: title,
                review: reviewText
            });

            showNotification('Thank you for your review!');
            this.reset();
        });

        // Initialize first thumbnail as active
        document.addEventListener('DOMContentLoaded', function () {
            const firstThumbnail = document.querySelector('.thumbnail');
            if (firstThumbnail) {
                firstThumbnail.style.borderColor = '#0d6efd';
            }
        });

        // Wishlist toggle
        function toggleWishlist(productId) {
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
                        const btn = document.getElementById('wishlistBtn');
                        const icon = btn.querySelector('i');
                        if (response.action === 'added') {
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                            btn.title = 'Remove from Wishlist';
                            btn.classList.remove('btn-outline-danger');
                            btn.classList.add('btn-danger');
                        } else {
                            icon.classList.remove('fas');
                            icon.classList.add('far');
                            btn.title = 'Add to Wishlist';
                            btn.classList.remove('btn-danger');
                            btn.classList.add('btn-outline-danger');
                        }
                        // Animate
                        btn.style.transform = 'scale(1.3)';
                        setTimeout(() => btn.style.transform = 'scale(1)', 200);
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

        // Set initial filled state
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('wishlistBtn');
            const icon = btn.querySelector('i');
            if (icon.classList.contains('fas')) {
                btn.classList.remove('btn-outline-danger');
                btn.classList.add('btn-danger');
            }
        });

    </script>

    <?php include 'footer.php'; ?>