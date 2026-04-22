<!-- FontAwesome CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<div class="sidebar">
    <div class="sidebar-header">
        <h3>Furni Admin</h3>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="products.php"
                class="<?php echo basename($_SERVER['PHP_SELF']) == 'products.php' || basename($_SERVER['PHP_SELF']) == 'add_product.php' ? 'active' : ''; ?>">
                <i class="fas fa-box"></i> Products
            </a>
        </li>
        <li>
            <a href="orders.php"
                class="<?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' || basename($_SERVER['PHP_SELF']) == 'add_order.php' ? 'active' : ''; ?>">
                <i class="fas fa-shopping-cart"></i> Orders
            </a>
        </li>
        <li>
            <a href="users.php"
                class="<?php echo basename($_SERVER['PHP_SELF']) == 'users.php' || basename($_SERVER['PHP_SELF']) == 'add_user.php' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> Users
            </a>
        </li>
        <li>
            <a href="review%26rating.php"
                class="<?php echo basename($_SERVER['PHP_SELF']) == 'review%26rating.php' || basename($_SERVER['PHP_SELF']) == 'add_review.php' ? 'active' : ''; ?>">
                <i class="fas fa-star"></i> Review & rating
            </a>
        </li>
        <li>
            <a href="cart.php"
                class="<?php echo basename($_SERVER['PHP_SELF']) == 'cart.php' || basename($_SERVER['PHP_SELF']) == 'add_cart.php' ? 'active' : ''; ?>">
                <i class="fas fa-shopping-cart"></i> Cart
            </a>
        </li>
        <li>
            <a href="home_content.php"
                class="<?php echo basename($_SERVER['PHP_SELF']) == 'home_content.php' || basename($_SERVER['PHP_SELF']) == 'add_home_content.php' || basename($_SERVER['PHP_SELF']) == 'edit_home_content.php' ? 'active' : ''; ?>">
                <i class="fas fa-file-alt"></i> Home Content
            </a>
        </li>
        <li>
            <a href="about_content.php"
                class="<?php echo basename($_SERVER['PHP_SELF']) == 'about_content.php' || basename($_SERVER['PHP_SELF']) == 'add_about_content.php' || basename($_SERVER['PHP_SELF']) == 'edit_about_content.php' ? 'active' : ''; ?>">
                <i class="fas fa-address-card"></i> About Content
            </a>
        </li>
        <li>
            <a href="blogs.php"
                class="<?php echo basename($_SERVER['PHP_SELF']) == 'blogs.php' || basename($_SERVER['PHP_SELF']) == 'add_blog.php' || basename($_SERVER['PHP_SELF']) == 'edit_blog.php' ? 'active' : ''; ?>">
                <i class="fas fa-blog"></i> Blog Posts
            </a>
        </li>
        <li>
            <a href="blog_content.php"
                class="<?php echo basename($_SERVER['PHP_SELF']) == 'blog_content.php' || basename($_SERVER['PHP_SELF']) == 'add_blog_content.php' || basename($_SERVER['PHP_SELF']) == 'edit_blog_content.php' ? 'active' : ''; ?>">
                <i class="fas fa-file-invoice"></i> Blog Content
            </a>
        </li>
        <li>
            <a href="services_content.php"
                class="<?php echo basename($_SERVER['PHP_SELF']) == 'services_content.php' || basename($_SERVER['PHP_SELF']) == 'add_services_content.php' || basename($_SERVER['PHP_SELF']) == 'edit_services_content.php' ? 'active' : ''; ?>">
                <i class="fas fa-concierge-bell"></i> Services Content
            </a>
        </li>
        <li>
            <a href="testimonials.php"
                class="<?php echo basename($_SERVER['PHP_SELF']) == 'testimonials.php' || basename($_SERVER['PHP_SELF']) == 'add_testimonial.php' || basename($_SERVER['PHP_SELF']) == 'edit_testimonial.php' ? 'active' : ''; ?>">
                <i class="fas fa-comments"></i> Testimonials
            </a>
        </li>
        <li>
            <a href="team.php"
                class="<?php echo basename($_SERVER['PHP_SELF']) == 'team.php' || basename($_SERVER['PHP_SELF']) == 'add_team.php' || basename($_SERVER['PHP_SELF']) == 'edit_team.php' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> Our Team
            </a>
        </li>
        <li>
            <a href="../index.php">
                <i class="fas fa-sign-out-alt"></i> Back to Shop
            </a>
        </li>
    </ul>
</div>