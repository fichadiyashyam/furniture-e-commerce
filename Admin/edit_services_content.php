<?php 
include 'header.php'; 
include '../config/db_config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>window.location.href='services_content.php';</script>";
    exit();
}

$id = mysqli_real_escape_string($connection, $_GET['id']);
$query = "SELECT * FROM services_content WHERE id = '$id'";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<script>window.location.href='services_content.php';</script>";
    exit();
}

$content = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title'])) {
    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $status = mysqli_real_escape_string($connection, $_POST['status']);
    
    $image = $content['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = array("jpg", "jpeg", "png", "gif", "svg");
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed)) {
            $new_file_name = "service_" . time() . "." . $file_ext;
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
        $update_query = "UPDATE services_content SET 
                        title = '$title', 
                        description = '$description', 
                        image = '$image', 
                        status = '$status' 
                        WHERE id = '$id'";
        
        if (mysqli_query($connection, $update_query)) {
            echo "<script>window.location.href='services_content.php?msg=" . urlencode("Feature updated successfully") . "';</script>";
            exit();
        } else {
            $error = "Failed to update feature. " . mysqli_error($connection);
        }
    }
}
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Edit Service Feature</h2>
        <a href="services_content.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Listing
        </a>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Feature Details</h6>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" required 
                                value="<?php echo htmlspecialchars($content['title']); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" rows="5" required><?php echo htmlspecialchars($content['description']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Current Icon</label>
                            <?php if (!empty($content['image'])) { ?>
                                <div class="mb-2">
                                    <img src="../images/<?php echo htmlspecialchars($content['image']); ?>" alt="Current" style="height: 50px;">
                                </div>
                            <?php } ?>
                            <input class="form-control" type="file" name="image" accept="image/*">
                            <small class="text-muted">Leave empty to keep current icon.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <select class="form-select" name="status">
                                <option value="active" <?php echo $content['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo $content['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i> Update Feature
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
