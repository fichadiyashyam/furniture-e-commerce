<?php
include 'header.php';
include '../config/db_config.php';

if (!isset($_GET['id'])) {
    header("Location: home_content.php");
    exit();
}

$id = intval($_GET['id']);

// Fetch existing data
$query = "SELECT * FROM home_content WHERE id = $id";
$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) == 0) {
    header("Location: home_content.php");
    exit();
}
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_content'])) {
    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $link = mysqli_real_escape_string($connection, $_POST['link']);
    
    $image = $row['image']; // Keep existing image by default
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = array("jpg", "jpeg", "png", "gif");
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed)) {
            if (!is_dir('../images/')) {
                mkdir('../images/', 0777, true);
            }
            $new_file_name = "section_" . time() . "." . $file_ext;
            if (move_uploaded_file($file_tmp, "../images/" . $new_file_name)) {
                // Delete old image
                if (!empty($image) && file_exists("../images/" . $image)) {
                    unlink("../images/" . $image);
                }
                $image = $new_file_name;
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Invalid image format. Only JPG, JPEG, PNG, GIF are allowed.";
        }
    }

    if (!isset($error)) {
        $update_query = "UPDATE home_content SET 
                            title = '$title', 
                            description = '$description', 
                            image = '$image', 
                            link = '$link' 
                         WHERE id = $id";
                         
        if (mysqli_query($connection, $update_query)) {
            echo "<script>window.location.href='home_content.php?msg=" . urlencode("Content updated successfully") . "';</script>";
            exit();
        } else {
            $error = "Failed to update content. " . mysqli_error($connection);
        }
    }
}
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Edit Home Content: <?php echo htmlspecialchars($row['section_name']); ?></h2>
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
                            <label class="form-label">Section Name</label>
                            <input type="text" class="form-control" name="section_name" value="<?php echo htmlspecialchars($row['section_name']); ?>" disabled>
                            <small class="text-muted">Section name cannot be changed.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" rows="5" required><?php echo htmlspecialchars($row['description']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Current Image</label><br>
                            <?php if (!empty($row['image'])) { ?>
                                <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" style="max-width: 200px; max-height: 150px; border-radius: 5px; mb-2"><br><br>
                            <?php } else { ?>
                                <span class="text-muted d-block mb-2">No image uploaded.</span>
                            <?php } ?>
                            <label class="form-label">Upload New Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Link/URL (Optional)</label>
                            <input type="text" class="form-control" name="link" value="<?php echo htmlspecialchars($row['link']); ?>" placeholder="e.g., shop.php">
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" name="edit_content" class="btn btn-primary btn-lg">Update Content</button>
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
