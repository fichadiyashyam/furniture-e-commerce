<?php 
include 'header.php'; 
include '../config/db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $position = mysqli_real_escape_string($connection, $_POST['position']);
    $quote = mysqli_real_escape_string($connection, $_POST['quote']);

    
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = array("jpg", "jpeg", "png", "gif");
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed)) {
            $new_file_name = "testi_" . time() . "." . $file_ext;
            if (move_uploaded_file($file_tmp, "../images/" . $new_file_name)) {
                $image = $new_file_name;
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Invalid format. Only JPG, JPEG, PNG, GIF are allowed.";
        }
    }

    if (!isset($error)) {
        $insert_query = "INSERT INTO testimonial (name, position, quote, image, status) 
                         VALUES ('$name', '$position', '$quote', '$image', '$status')";
        if (mysqli_query($connection, $insert_query)) {
            echo "<script>window.location.href='testimonials.php?msg=" . urlencode("Testimonial added successfully") . "';</script>";
            exit();
        } else {
            $error = "Failed to add testimonial. " . mysqli_error($connection);
        }
    }
}
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Add Testimonial</h2>
        <a href="testimonials.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Listing
        </a>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Testimonial Details</h6>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">User Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required 
                                placeholder="e.g. Maria Jones">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Position <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="position" required 
                                placeholder="e.g. CEO, Co-Founder, XYZ Inc.">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Quote / Content <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="quote" rows="5" required 
                                placeholder="Enter the testimonial here..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">User Photo (Optional)</label>
                            <input class="form-control" type="file" name="image" accept="image/*">
                            <small class="text-muted">Upload a clear square photo of the person.</small>
                        </div>

                      

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i> Save Testimonial
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
mysqli_close($connection);
include 'footer.php'; 
?>
