<?php
include 'header.php';
include '../config/db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_content'])) {
    $section_name = mysqli_real_escape_string($connection, $_POST['section_name']);
    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $link = mysqli_real_escape_string($connection, $_POST['link']);
    
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = array("jpg", "jpeg", "png", "gif");
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed)) {
            // Check if images directory exists
            if (!is_dir('../images/')) {
                mkdir('../images/', 0777, true);
            }
            $new_file_name = "section_" . time() . "." . $file_ext;
            if (move_uploaded_file($file_tmp, "../images/" . $new_file_name)) {
                $image = $new_file_name;
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Invalid image format. Only JPG, JPEG, PNG, GIF are allowed.";
        }
    }

    if (!isset($error)) {
        // Validation for unique section_name
        $check_query = "SELECT id FROM home_content WHERE section_name = '$section_name'";
        $check_result = mysqli_query($connection, $check_query);
        if (mysqli_num_rows($check_result) > 0) {
            $error = "A section with this name already exists. Please edit the existing one or choose a different name.";
        } else {
            $insert_query = "INSERT INTO home_content (section_name, title, description, image, link) 
                             VALUES ('$section_name', '$title', '$description', '$image', '$link')";
            if (mysqli_query($connection, $insert_query)) {
                echo "<script>window.location.href='home_content.php?msg=" . urlencode("Content added successfully") . "';</script>";
                exit();
            } else {
                $error = "Failed to add content. " . mysqli_error($connection);
            }
        }
    }
}
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Add Home Content</h2>
        <a href="home_content.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow mb-4 border-0">
                <div class="card-body p-4">
                    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Section Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="section_name" placeholder="e.g., hero_section" required>
                            <small class="text-muted">Use a unique identifier without spaces (e.g., hero_section, why_choose_us, we_help).</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" rows="5" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Link/URL (Optional)</label>
                            <input type="text" class="form-control" name="link" placeholder="e.g., shop.php">
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" name="add_content" class="btn btn-primary btn-lg">Add Content</button>
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
