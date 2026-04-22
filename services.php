<?php

$page = 'services';
include 'header.php';
include 'config/db_config.php';

$services_banner = "SELECT * FROM banners WHERE title='Services'";
$services_banner_result = mysqli_query($connection, $services_banner);
$services_banner_data = [];
if ($services_banner_result) {
	while ($row = mysqli_fetch_assoc($services_banner_result)) {
		$services_banner_data = $row;
	}
}

?>


		<!-- Start Hero Section -->
			<div class="hero">
				<div class="container">
					<div class="row justify-content-between">
						<div class="col-lg-5">
							<div class="intro-excerpt">
								<h1><?php echo $services_banner_data['title'] ?></h1>
								<p class="mb-4"><?php echo $services_banner_data['description'] ?></p>
								<p><a href="shop.php" class="btn btn-secondary me-2">Shop Now</a><a href="shop.php" class="btn btn-white-outline">Explore</a></p>
							</div>
						</div>
						<div class="col-lg-7">
							<div class="hero-img-wrap">
								<img src="images/<?php echo $services_banner_data['banner_image'] ?>" class="img-fluid">
							</div>
						</div>
					</div>
				</div>
			</div>
		<!-- End Hero Section -->

		

		<!-- Start Why Choose Us Section -->
		<div class="why-choose-section">
			<div class="container">
				
				<div class="row my-5">
					<?php
					$features_query = "SELECT * FROM services_content WHERE section_name = 'feature' AND status = 'active' ORDER BY id ASC";
					$features_result = mysqli_query($connection, $features_query);

					if ($features_result && mysqli_num_rows($features_result) > 0) {
						while ($feature = mysqli_fetch_assoc($features_result)) {
							?>
							<div class="col-6 col-md-6 col-lg-3 mb-4">
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
						<div class="col-12 text-center">
							<p class="text-muted">No features available at the moment.</p>
						</div>
						<?php
					}
					?>
				</div>
			
			</div>
		</div>
		<!-- End Why Choose Us Section -->

		<!-- Start Product Section -->
		<div class="product-section pt-0">
			<div class="container">
				<div class="row">

					<!-- Start Column 1 -->
					<div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
						<h2 class="mb-4 section-title">Crafted with excellent material.</h2>
						<p class="mb-4">Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. </p>
						<p><a href="#" class="btn">Explore</a></p>
					</div> 
					<!-- End Column 1 -->

					<!-- Start Column 2 -->
					<div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
						<a class="product-item" href="#">
							<img src="images/product-1.png" class="img-fluid product-thumbnail">
							<h3 class="product-title">Nordic Chair</h3>
							<strong class="product-price">$50.00</strong>

							<span class="icon-cross">
								<img src="images/cross.svg" class="img-fluid">
							</span>
						</a>
					</div> 
					<!-- End Column 2 -->

					<!-- Start Column 3 -->
					<div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
						<a class="product-item" href="#">
							<img src="images/product-2.png" class="img-fluid product-thumbnail">
							<h3 class="product-title">Kruzo Aero Chair</h3>
							<strong class="product-price">$78.00</strong>

							<span class="icon-cross">
								<img src="images/cross.svg" class="img-fluid">
							</span>
						</a>
					</div>
					<!-- End Column 3 -->

					<!-- Start Column 4 -->
					<div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
						<a class="product-item" href="#">
							<img src="images/product-3.png" class="img-fluid product-thumbnail">
							<h3 class="product-title">Ergonomic Chair</h3>
							<strong class="product-price">$43.00</strong>

							<span class="icon-cross">
								<img src="images/cross.svg" class="img-fluid">
							</span>
						</a>
					</div>
					<!-- End Column 4 -->

				</div>
			</div>
		</div>
		<!-- End Product Section -->

		

		<!-- Start Testimonial Slider -->
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
		<!-- End Testimonial Slider -->

<?php include 'footer.php'; ?>
