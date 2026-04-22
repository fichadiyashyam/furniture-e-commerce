<?php 
include 'header.php'; 
include '../config/db_config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>window.location.href='blog_content.php';</script>";
    exit();
}

$id = mysqli_real_escape_string($connection, $_GET['id']);
$query = "SELECT * FROM blog_content WHERE id = '$id'";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<script>window.location.href='blog_content.php';</script>";
    exit();
}

$content = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['section_name'])) {
    $section_name = mysqli_real_escape_string($connection, $_POST['section_name']);
    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $link = mysqli_real_escape_string($connection, $_POST['link']);
    
    // Check if another section has the same identifier
    $check_query = "SELECT id FROM blog_content WHERE section_name = '$section_name' AND id != '$id'";
    $check_result = mysqli_query($connection, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        $error = "Another section with this Identifier already exists.";
    } else {
        $image = $content['image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = array("jpg", "jpeg", "png", "gif", "svg");
            $file_name = $_FILES['image']['name'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (in_array($file_ext, $allowed)) {
                $new_file_name = "blog_content_" . time() . "." . $file_ext;
                if (move_uploaded_file($file_tmp, "../images/" . $new_file_name)) {
                    // Delete old image if it exists
                    if (!empty($image) && file_exists("../images/" . $image)) {
                        unlink("../images/" . $image);
                    }
                    $image = $new_file_name;
                } else {
                    $error = "Failed to upload image.";
                }
            } else {
                $error = "Invalid format. Only JPG, JPEG, PNG, GIF, SVG are allowed.";
            }
        }

        if (!isset($error)) {
            $update_query = "UPDATE blog_content SET 
                            section_name = '$section_name', 
                            title = '$title', 
                            description = '$description', 
                            image = '$image', 
                            link = '$link' 
                            WHERE id = '$id'";
            
            if (mysqli_query($connection, $update_query)) {
                echo "<script>window.location.href='blog_content.php?msg=" . urlencode("Content updated successfully") . "';</script>";
                exit();
            } else {
                $error = "Failed to update content. " . mysqli_error($connection);
            }
        }
    }
}
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Edit Blog Page Content Section</h2>
        <a href="blog_content.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Listing
        </a>
    </div>

    <!-- CENTERED FORM -->
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Content Details</h6>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Section Identifier <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="section_name" required 
                                value="<?php echo htmlspecialchars($content['section_name']); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Title / Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" required 
                                value="<?php echo htmlspecialchars($content['title']); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Subtitle / Position / Link (Optional)</label>
                            <input type="text" class="form-control" name="link" 
                                value="<?php echo htmlspecialchars($content['link']); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Description / Quote (Optional)</label>
                            <textarea class="form-control" name="description" rows="5"><?php echo htmlspecialchars($content['description']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Current Image</label>
                            <?php if (!empty($content['image'])) { ?>
                                <div class="mb-2">
                                    <img src="../images/<?php echo htmlspecialchars($content['image']); ?>" alt="Current" style="max-height: 100px;">
                                </div>
                            <?php } ?>
                            <input class="form-control" type="file" name="image" accept="image/*">
                            <small class="text-muted">Leave empty to keep the current image.</small>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i> Update Content
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
