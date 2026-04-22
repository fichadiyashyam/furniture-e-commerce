<?php 
$page = 'home';
include 'config/db_config.php';
$content_query = "SELECT * FROM home_content";
$content_result = mysqli_query($connection, $content_query);
$home_data = [];
if ($content_result && mysqli_num_rows($content_result) > 0) {
    while ($row = mysqli_fetch_assoc($content_result)) {
        $home_data[$row['section_name']] = $row;
    }
}

$home_banner = "SELECT * FROM banners WHERE title='Modern Interior Design Studio'";
$home_banner_result = mysqli_query($connection, $home_banner);
$home_banner_data = [];
if ($home_banner_result) {
    while ($row = mysqli_fetch_assoc($home_banner_result)) {
        $home_banner_data = $row;
    }
}


include 'header.php'; 
?>

		<!-- Start Hero Section -->
			<div class="hero">
				<div class="container">
					<div class="row justify-content-between">
						<div class="col-lg-5">
							<div class="intro-excerpt">
								<h1><?php echo $home_banner_data['title'] ?></h1>
								<p class="mb-4"><?php echo $home_banner_data['description'] ?></p>
									<p><a href="shop.php" class="btn btn-secondary me-2">Shop Now</a><a href="shop.php" class="btn btn-white-outline">Explore</a></p>
							</div>
						</div>
						<div class="col-lg-7">
							<div class="hero-img-wrap">
								<img src="images/<?php echo $home_banner_data['banner_image'] ?>" class="img-fluid">
							</div>
						</div>
					</div>
				</div>
			</div>
		<!-- End Hero Section -->

		<!-- Start Product Section -->
		<div class="product-section">
			<div class="container">
				<div class="row">

					
					<div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
						<h2 class="mb-4 section-title"><?php echo $home_data['product_intro']['title'] ?></h2>
						<p class="mb-4"><?php echo $home_data['product_intro']['description'] ?></p>
						<p><a href="<?php echo $home_data['product_intro']['link'] ?>" class="btn">Explore</a></p>
					</div> 
				

					<?php
					$prod_query = "SELECT * FROM products WHERE status = 'active' ORDER BY id DESC LIMIT 3";
					$prod_result = mysqli_query($connection, $prod_query);
					if ($prod_result && mysqli_num_rows($prod_result) > 0) {
						while ($prod = mysqli_fetch_assoc($prod_result)) {
							?>
							<div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
								<a class="product-item" href="product_detail.php?id=<?php echo $prod['id']; ?>">
									<img src="images/<?php echo htmlspecialchars($prod['image']); ?>" class="img-fluid product-thumbnail" style="height: 250px; object-fit: contain;">
									<h3 class="product-title"><?php echo htmlspecialchars($prod['product_name']); ?></h3>
									<strong class="product-price">$<?php echo number_format($prod['price'], 2); ?></strong>

									<span class="icon-cross">
										<img src="images/cross.svg" class="img-fluid">
									</span>
								</a>
							</div>
							<?php
						}
					}
					?>

				</div>
			</div>
		</div>
		<!-- End Product Section -->

		<!-- Start Why Choose Us Section -->
		<div class="why-choose-section">
			<div class="container">
				<div class="row justify-content-between">
					<div class="col-lg-6">
						<h2 class="section-title"><?php echo isset($home_data['why_choose_us']['title']) ? htmlspecialchars($home_data['why_choose_us']['title']) : 'Why Choose Us'; ?></h2>
						<p><?php echo isset($home_data['why_choose_us']['description']) ? htmlspecialchars($home_data['why_choose_us']['description']) : 'Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique.'; ?></p>

						<div class="row my-5">
                            <?php
                            $feature_query = "SELECT * FROM services_content WHERE section_name = 'feature' AND status = 'active' ORDER BY id ASC LIMIT 4";
                            $feature_result = mysqli_query($connection, $feature_query);

                            if ($feature_result && mysqli_num_rows($feature_result) > 0) {
                                while ($feature = mysqli_fetch_assoc($feature_result)) {
                                    ?>
                                    <div class="col-6 col-md-6">
                                        <div class="feature">
                                            <div class="icon">
                                                <img src="images/<?php echo htmlspecialchars($feature['image']); ?>" alt="Image" class="imf-fluid">
                                            </div>
                                            <h3><?php echo htmlspecialchars($feature['title']); ?></h3>
                                            <p><?php echo nl2br(htmlspecialchars($feature['description'])); ?></p>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="col-12">
                                    <p class="text-muted">No features available.</p>
                                </div>
                                <?php
                            }
                            ?>

						</div>
					</div>

					<div class="col-lg-5">
						<div class="img-wrap">
							<?php if(isset($home_data['why_choose_us']['image']) && !empty($home_data['why_choose_us']['image'])): ?>
								<img src="images/<?php echo htmlspecialchars($home_data['why_choose_us']['image']); ?>" alt="Image" class="img-fluid">
							<?php else: ?>
								<img src="images/why-choose-us-img.jpg" alt="Image" class="img-fluid">
							<?php endif; ?>
						</div>
					</div>

				</div>
			</div>
		</div>
		<!-- End Why Choose Us Section -->

		<!-- Start We Help Section -->
		<div class="we-help-section">
			<div class="container">
				<div class="row justify-content-between">
					<div class="col-lg-7 mb-5 mb-lg-0">
						<div class="imgs-grid">
                            <?php $we_img1 = $home_data['we_help_img_1']['image']; ?>
                            <?php $we_img2 = $home_data['we_help_img_2']['image']; ?>
                            <?php $we_img3 = $home_data['we_help_img_3']['image']; ?>
							<div class="grid grid-1"><img src="images/<?php echo $we_img1; ?>" alt="Untree.co"></div>
							<div class="grid grid-2"><img src="images/<?php echo $we_img2; ?>" alt="Untree.co"></div>
							<div class="grid grid-3"><img src="images/<?php echo $we_img3; ?>" alt="Untree.co"></div>
						</div>
					</div>
					<div class="col-lg-5 ps-lg-5">
						<h2 class="section-title mb-4"><?php echo isset($home_data['we_help']['title']) ? htmlspecialchars($home_data['we_help']['title']) : 'We Help You Make Modern Interior Design'; ?></h2>
						<p><?php echo isset($home_data['we_help']['description']) ? htmlspecialchars($home_data['we_help']['description']) : 'Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant morbi tristique senectus et netus et malesuada'; ?></p>

						<ul class="list-unstyled custom-list my-4">
							<li><?php echo $home_data['we_help_list_1']['title']; ?></li>
							<li><?php echo $home_data['we_help_list_2']['title']; ?></li>
							<li><?php echo $home_data['we_help_list_3']['title']; ?></li>
							<li><?php echo $home_data['we_help_list_4']['title']; ?></li>
						</ul>
						<p><a href="<?php echo $home_data['we_help']['link']; ?>" class="btn">Explore</a></p>
					</div>
				</div>
			</div>
		</div>
		<!-- End We Help Section -->

		<!-- Start Popular Product -->
		<div class="popular-product">
			<div class="container">
				<div class="row">

					<?php
					$pop_query = "SELECT * FROM products WHERE status = 'active' ORDER BY id ASC LIMIT 3";
					$pop_result = mysqli_query($connection, $pop_query);
					if ($pop_result && mysqli_num_rows($pop_result) > 0) {
						while ($pop = mysqli_fetch_assoc($pop_result)) {
							?>
							<div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
								<div class="product-item-sm d-flex">
									<div class="thumbnail">
										<img src="images/<?php echo htmlspecialchars($pop['image']); ?>" alt="Image" class="img-fluid" style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px;">
									</div>
									<div class="pt-3">
										<h3><?php echo htmlspecialchars($pop['product_name']); ?></h3>
										<p><?php echo htmlspecialchars(substr($pop['description'], 0, 50)) . '...'; ?></p>
										<p><a href="product_detail.php?id=<?php echo $pop['id']; ?>">View Details</a></p>
									</div>
								</div>
							</div>
							<?php
						}
					}
					?>

				</div>
			</div>
		</div>
		<div class="blog-section">
			<div class="container">
				<div class="row mb-5">
					<div class="col-md-6">
						<h2 class="section-title">Recent Blog</h2>
					</div>
					<div class="col-md-6 text-start text-md-end">
						<a href="blog.php" class="more">View All Posts</a>
					</div>
				</div>

				<div class="row">

                    <?php
                    $blog_query = "SELECT * FROM blogs WHERE status = 'published' ORDER BY published_date DESC, created_at DESC LIMIT 3";
                    $blog_result = mysqli_query($connection, $blog_query);

                    if ($blog_result && mysqli_num_rows($blog_result) > 0) {
                        while ($blog = mysqli_fetch_assoc($blog_result)) {
                            ?>
                            <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
                                <div class="post-entry">
                                    <a href="blog.php" class="post-thumbnail">
                                        <?php if (!empty($blog['image'])): ?>
                                            <img src="images/<?php echo htmlspecialchars($blog['image']); ?>" alt="Image" class="img-fluid" style="border-radius: 8px;">
                                        <?php else: ?>
                                            <img src="images/post-1.jpg" alt="Image" class="img-fluid" style="border-radius: 8px;">
                                        <?php endif; ?>
                                    </a>
                                    <div class="post-content-entry">
                                        <h3><a href="blog.php"><?php echo htmlspecialchars($blog['title']); ?></a></h3>
                                        <div class="meta">
                                            <span>by <a href="#"><?php echo htmlspecialchars($blog['author']); ?></a></span> 
                                            <span>on <a href="#"><?php echo date('M d, Y', strtotime($blog['published_date'])); ?></a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="col-12 text-center">
                            <p class="text-muted">No recent blog posts found.</p>
                        </div>
                        <?php
                    }
                    ?>

				</div>
			</div>
		</div>

			<div class="testimonial-section before-footer-section">
			<div class="container">
				<div class="row">
					<div class="col-lg-7 mx-auto text-center">
						<h2 class="section-title">Testimonials</h2>
					</div>
				</div>

				<div class="row justify-content-center">
					<div class="col-lg-12">
						<div class="testimonial-slider-wrap text-center">

							<div id="testimonial-nav">
								<span class="prev" data-controls="prev"><span class="fa fa-chevron-left"></span></span>
								<span class="next" data-controls="next"><span class="fa fa-chevron-right"></span></span>
							</div>

							<div class="testimonial-slider">
								
                                <?php
                                $testi_query = "SELECT * FROM testimonial ORDER BY id DESC";
                                $testi_result = mysqli_query($connection, $testi_query);

                                if ($testi_result && mysqli_num_rows($testi_result) > 0) {
                                    while ($testi = mysqli_fetch_assoc($testi_result)) {
                                        ?>
                                        <div class="item">
                                            <div class="row justify-content-center">
                                                <div class="col-lg-8 mx-auto">
                                                    <div class="testimonial-block text-center">
                                                        <blockquote class="mb-5">
                                                            <p>&ldquo;<?php echo nl2br(htmlspecialchars($testi['quote'])); ?>&rdquo;</p>
                                                        </blockquote>
                                                        <div class="author-info">
                                                            <div class="author-pic">
                                                                <?php if (!empty($testi['image'])): ?>
                                                                    <img src="images/<?php echo htmlspecialchars($testi['image']); ?>" alt="<?php echo htmlspecialchars($testi['name']); ?>" class="img-fluid">
                                                                <?php else: ?>
                                                                    <img src="images/person-1.png" alt="User" class="img-fluid">
                                                                <?php endif; ?>
                                                            </div>
                                                            <h3 class="font-weight-bold"><?php echo htmlspecialchars($testi['name']); ?></h3>
                                                            <span class="position d-block mb-3"><?php echo htmlspecialchars($testi['position']); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <div class="item text-center">
                                        <p class="text-muted">No testimonials available.</p>
                                    </div>
                                    <?php
                                }
                                ?>

							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Blog Section -->	

<?php include 'footer.php'; ?>
