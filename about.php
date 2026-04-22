<?php 
$page = 'about';
include 'config/db_config.php';
include 'header.php'; 

$about_banner = "SELECT * FROM banners WHERE title='About Us'";
$about_banner_result = mysqli_query($connection, $about_banner);
$about_banner_data = [];
if ($about_banner_result) {
    while ($row = mysqli_fetch_assoc($about_banner_result)) {
        $about_banner_data = $row;
    }
}

$about_data = [];
$query = "SELECT * FROM about_content";
$result = mysqli_query($connection, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $about_data[$row['section_name']] = $row;
    }
}

?>


		<!-- Start Hero Section -->
			<div class="hero">
				<div class="container">
					<div class="row justify-content-between">
						<div class="col-lg-5">
							<div class="intro-excerpt">
								<h1><?php echo $about_banner_data['title'] ?></h1>
								<p class="mb-4"><?php echo $about_banner_data['description'] ?></p>
								<p><a href="shop.php" class="btn btn-secondary me-2">Shop Now</a><a href="shop.php" class="btn btn-white-outline">Explore</a></p>
							</div>
						</div>
						<div class="col-lg-7">
							<div class="hero-img-wrap">
                                <?php $hero_img = isset($about_data['hero']['image']) ? htmlspecialchars($about_data['hero']['image']) : 'couch.png'; ?>
								<img src="images/<?php echo $hero_img; ?>" class="img-fluid">
							</div>
						</div>
					</div>
				</div>
			</div>
		<!-- End Hero Section -->

		

		<!-- Start Why Choose Us Section -->
		<div class="why-choose-section">
			<div class="container">
				<div class="row justify-content-between align-items-center">
					<div class="col-lg-6">
						<h2 class="section-title"><?php echo isset($about_data['why_choose_us']['title']) ? htmlspecialchars($about_data['why_choose_us']['title']) : 'Why Choose Us'; ?></h2>
						<p><?php echo isset($about_data['why_choose_us']['description']) ? nl2br(htmlspecialchars($about_data['why_choose_us']['description'])) : 'Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique.'; ?></p>

						<div class="row my-5">
							<div class="col-6 col-md-6">
								<div class="feature">
									<div class="icon">
                                        <?php $icon1 = isset($about_data['feature_1']['image']) ? htmlspecialchars($about_data['feature_1']['image']) : 'truck.svg'; ?>
										<img src="images/<?php echo $icon1; ?>" alt="Image" class="imf-fluid">
									</div>
									<h3><?php echo isset($about_data['feature_1']['title']) ? htmlspecialchars($about_data['feature_1']['title']) : 'Fast & Free Shipping'; ?></h3>
									<p><?php echo isset($about_data['feature_1']['description']) ? nl2br(htmlspecialchars($about_data['feature_1']['description'])) : 'Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.'; ?></p>
								</div>
							</div>

							<div class="col-6 col-md-6">
								<div class="feature">
									<div class="icon">
                                        <?php $icon2 = isset($about_data['feature_2']['image']) ? htmlspecialchars($about_data['feature_2']['image']) : 'bag.svg'; ?>
										<img src="images/<?php echo $icon2; ?>" alt="Image" class="imf-fluid">
									</div>
									<h3><?php echo isset($about_data['feature_2']['title']) ? htmlspecialchars($about_data['feature_2']['title']) : 'Easy to Shop'; ?></h3>
									<p><?php echo isset($about_data['feature_2']['description']) ? nl2br(htmlspecialchars($about_data['feature_2']['description'])) : 'Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.'; ?></p>
								</div>
							</div>

							<div class="col-6 col-md-6">
								<div class="feature">
									<div class="icon">
                                        <?php $icon3 = isset($about_data['feature_3']['image']) ? htmlspecialchars($about_data['feature_3']['image']) : 'support.svg'; ?>
										<img src="images/<?php echo $icon3; ?>" alt="Image" class="imf-fluid">
									</div>
									<h3><?php echo isset($about_data['feature_3']['title']) ? htmlspecialchars($about_data['feature_3']['title']) : '24/7 Support'; ?></h3>
									<p><?php echo isset($about_data['feature_3']['description']) ? nl2br(htmlspecialchars($about_data['feature_3']['description'])) : 'Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.'; ?></p>
								</div>
							</div>

							<div class="col-6 col-md-6">
								<div class="feature">
									<div class="icon">
                                        <?php $icon4 = isset($about_data['feature_4']['image']) ? htmlspecialchars($about_data['feature_4']['image']) : 'return.svg'; ?>
										<img src="images/<?php echo $icon4; ?>" alt="Image" class="imf-fluid">
									</div>
									<h3><?php echo isset($about_data['feature_4']['title']) ? htmlspecialchars($about_data['feature_4']['title']) : 'Hassle Free Returns'; ?></h3>
									<p><?php echo isset($about_data['feature_4']['description']) ? nl2br(htmlspecialchars($about_data['feature_4']['description'])) : 'Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.'; ?></p>
								</div>
							</div>

						</div>
					</div>

					<div class="col-lg-5">
						<div class="img-wrap">
                            <?php $why_img = isset($about_data['why_choose_us']['image']) ? htmlspecialchars($about_data['why_choose_us']['image']) : 'why-choose-us-img.jpg'; ?>
							<img src="images/<?php echo $why_img; ?>" alt="Image" class="img-fluid">
						</div>
					</div>

				</div>
			</div>
		</div>
		
		<div class="untree_co-section">
			<div class="container">

				<div class="row mb-5">
					<div class="col-lg-5 mx-auto text-center">
						<h2 class="section-title">Our Team</h2>
					</div>
				</div>

				<div class="row">

                    <?php
                    $team_query = "SELECT * FROM team WHERE status = 'active' ORDER BY id ASC";
                    $team_result = mysqli_query($connection, $team_query);

                    if ($team_result && mysqli_num_rows($team_result) > 0) {
                        while ($member = mysqli_fetch_assoc($team_result)) {
                            ?>
                            <div class="col-12 col-md-6 col-lg-3 mb-5 mb-md-0">
                                <img src="images/<?php echo htmlspecialchars($member['image']); ?>" class="img-fluid mb-5" style="border-radius: 8px;">
                                <h3 class=""><a href="#"><span class=""><?php echo htmlspecialchars($member['name']); ?></span></a></h3>
                                <span class="d-block position mb-4"><?php echo htmlspecialchars($member['position']); ?></span>
                                <p><?php echo nl2br(htmlspecialchars($member['description'])); ?></p>
                                <p class="mb-0"><a href="#" class="more dark">Learn More <span class="icon-arrow_forward"></span></a></p>
                            </div> 
                            <?php
                        }
                    } else {
                        ?>
                        <div class="col-12 text-center">
                            <p class="text-muted">No team members available.</p>
                        </div>
                        <?php
                    }
                    ?>

				</div>
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
		<!-- End Testimonial Slider -->

<?php include 'footer.php'; ?>
