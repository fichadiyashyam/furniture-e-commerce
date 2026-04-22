<?php 
include 'header.php'; 
include '../config/db_config.php';

if (!isset($_GET['id'])) {
    echo "<script>window.location.href='about_content.php';</script>";
    exit();
}

$id = intval($_GET['id']);
$query = "SELECT * FROM about_content WHERE id = $id";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<script>window.location.href='about_content.php?error=" . urlencode("Content section not found.") . "';</script>";
    exit();
}
$content = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $section_name = mysqli_real_escape_string($connection, $_POST['section_name']);
    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $link = mysqli_real_escape_string($connection, $_POST['link']);
    
    // Check if section name belongs to another ID
    $check_query = "SELECT id FROM about_content WHERE section_name = '$section_name' AND id != $id";
    $check_result = mysqli_query($connection, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        $error = "Another section already uses this Identifier. Must be unique.";
    } else {
        $image_update_sql = "";
        
        // Handle deletion of existing map if checkbox checked
        if (isset($_POST['remove_image']) && $_POST['remove_image'] == '1') {
            if (!empty($content['image']) && file_exists("../images/" . $content['image'])) {
                unlink("../images/" . $content['image']);
            }
            $image_update_sql = ", image='' ";
            $content['image'] = ''; // update current array state
        }
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = array("jpg", "jpeg", "png", "gif");
            $file_name = $_FILES['image']['name'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (in_array($file_ext, $allowed)) {
                $new_file_name = "about_" . time() . "." . $file_ext;
                if (move_uploaded_file($file_tmp, "../images/" . $new_file_name)) {
                    // remove old
                    if (!empty($content['image']) && file_exists("../images/" . $content['image'])) {
                        unlink("../images/" . $content['image']);
                    }
                    $image_update_sql = ", image='$new_file_name' ";
                    $content['image'] = $new_file_name;
                } else {
                    $error = "Failed to upload new image.";
                }
            } else {
                $error = "Invalid image format.";
            }
        }

        if (!isset($error)) {
            $update_query = "UPDATE about_content 
                             SET section_name='$section_name', title='$title', description='$description', link='$link' $image_update_sql 
                             WHERE id = $id";
            if (mysqli_query($connection, $update_query)) {
                $success = "Content updated successfully!";
                $content['section_name'] = $section_name;
                $content['title'] = $title;
                $content['description'] = $description;
                $content['link'] = $link;
            } else {
                $error = "Failed to update content. " . mysqli_error($connection);
            }
        }
    }
}
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Edit About Content Section</h2>
        <a href="about_content.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Listing
        </a>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Details</h6>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
                    <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>

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
                            <label class="form-label fw-bold">Description / Quote</label>
                            <textarea class="form-control" name="description" rows="5"><?php echo htmlspecialchars($content['description']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Upload New Image / Photo</label>
                            
                            <?php if (!empty($content['image'])) { ?>
                                <div class="mb-2 p-2 border rounded bg-light text-center">
                                    <p class="mb-2 small text-muted">Current Image:</p>
                                    <img src="../images/<?php echo htmlspecialchars($content['image']); ?>" alt="Current Image" class="img-thumbnail mb-2" style="max-height: 150px;">
                                    <div class="form-check d-flex justify-content-center">
                                        <input class="form-check-input me-2" type="checkbox" name="remove_image" value="1" id="remove_image">
                                        <label class="form-check-label text-danger" for="remove_image">
                                            Remove current image
                                        </label>
                                    </div>
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
