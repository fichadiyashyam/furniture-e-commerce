<?php 
$page = 'blog';
include 'config/db_config.php';
include 'header.php'; 

$bloog_banner = "SELECT * FROM banners WHERE title='Blog'";
$bloog_banner_result = mysqli_query($connection, $bloog_banner);
$bloog_banner_data = [];
if ($bloog_banner_result) {
    while ($row = mysqli_fetch_assoc($bloog_banner_result)) {
        $bloog_banner_data = $row;
    }
}
$blog_query = "SELECT * FROM blogs WHERE status = 'published' ORDER BY published_date DESC, created_at DESC";
$blog_result = mysqli_query($connection, $blog_query);

$blog_content_query = "SELECT * FROM blog_content";
$blog_content_result = mysqli_query($connection, $blog_content_query);
$blog_data = [];
if ($blog_content_result) {
    while ($row = mysqli_fetch_assoc($blog_content_result)) {
        $blog_data[$row['section_name']] = $row;
    }
}
?>

		<!-- Start Hero Section -->
			<div class="hero">
				<div class="container">
					<div class="row justify-content-between">
						<div class="col-lg-5">
							<div class="intro-excerpt">
								<h1><?php echo $bloog_banner_data['title'] ?></h1>
								<p class="mb-4"><?php echo $bloog_banner_data['description'] ?></p>
								<p><a href="shop.php" class="btn btn-secondary me-2">Shop Now</a><a href="shop.php" class="btn btn-white-outline">Explore</a></p>
							</div>
						</div>
						<div class="col-lg-7">
							<div class="hero-img-wrap">
								<img src="images/<?php echo $bloog_banner_data['banner_image'] ?>" class="img-fluid">
							</div>
						</div>
					</div>
				</div>
			</div>
		<!-- End Hero Section -->

		

		<!-- Start Blog Section -->
		<div class="blog-section">
			<div class="container">
				
				<div class="row">

					<?php if ($blog_result && mysqli_num_rows($blog_result) > 0): ?>
						<?php while ($blog = mysqli_fetch_assoc($blog_result)): ?>
					<div class="col-12 col-sm-6 col-md-4 mb-5">
						<div class="post-entry">
							<a href="#" class="post-thumbnail">
								<?php if (!empty($blog['image'])): ?>
									<img src="images/<?php echo htmlspecialchars($blog['image']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" class="img-fluid">
								<?php else: ?>
									<img src="images/post-1.jpg" alt="Blog" class="img-fluid">
								<?php endif; ?>
							</a>
							<div class="post-content-entry">
								<h3><a href="#"><?php echo htmlspecialchars($blog['title']); ?></a></h3>
								<div class="meta">
									<span>by <a href="#"><?php echo htmlspecialchars($blog['author']); ?></a></span>
									<?php if (!empty($blog['published_date'])): ?>
										<span>on <a href="#"><?php echo date('M d, Y', strtotime($blog['published_date'])); ?></a></span>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
						<?php endwhile; ?>
					<?php else: ?>
					<div class="col-12 text-center py-5">
						<p class="text-muted">No blog posts yet. Check back soon!</p>
					</div>
					<?php endif; ?>

				</div>
			</div>
		</div>
		<!-- End Blog Section -->	

		

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
