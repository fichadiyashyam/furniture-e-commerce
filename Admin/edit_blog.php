<?php
include 'header.php';
include '../config/db_config.php';

if (!isset($_GET['id'])) { header("Location: blogs.php"); exit(); }
$id = intval($_GET['id']);

$result = mysqli_query($connection, "SELECT * FROM blogs WHERE id = $id");
if (mysqli_num_rows($result) == 0) { header("Location: blogs.php"); exit(); }
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_blog'])) {
    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $author = mysqli_real_escape_string($connection, $_POST['author']);
    $excerpt = mysqli_real_escape_string($connection, $_POST['excerpt']);
    $content = mysqli_real_escape_string($connection, $_POST['content']);
    $published_date = mysqli_real_escape_string($connection, $_POST['published_date']);
    $status = mysqli_real_escape_string($connection, $_POST['status']);
    $image = $row['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ["jpg", "jpeg", "png", "gif", "webp"];
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($file_ext, $allowed)) {
            if (!is_dir('../images/')) mkdir('../images/', 0777, true);
            $new_name = "blog_" . time() . "_" . rand(100,999) . "." . $file_ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], "../images/" . $new_name)) {
                // Delete old image
                if (!empty($image) && file_exists("../images/" . $image) && strpos($image, 'post-') === false) {
                    unlink("../images/" . $image);
                }
                $image = $new_name;
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Invalid image format.";
        }
    }

    // Remove image option
    if (isset($_POST['remove_image']) && $_POST['remove_image'] == '1') {
        if (!empty($image) && file_exists("../images/" . $image) && strpos($image, 'post-') === false) {
            unlink("../images/" . $image);
        }
        $image = '';
    }

    if (!isset($error)) {
        $q = "UPDATE blogs SET title='$title', excerpt='$excerpt', content='$content',
              image='$image', author='$author', published_date='$published_date', status='$status'
              WHERE id=$id";
        if (mysqli_query($connection, $q)) {
            echo "<script>window.location.href='blogs.php?msg=" . urlencode("Blog post updated successfully") . "';</script>";
            exit();
        } else {
            $error = "Failed to update: " . mysqli_error($connection);
        }
    }
}
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0">Edit Blog Post: <em><?php echo htmlspecialchars($row['title']); ?></em></h2>
        <a href="blogs.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back to List</a>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    <form action="" method="POST" enctype="multipart/form-data">

                        <div class="mb-3">
                            <label class="form-label">Post Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Author</label>
                                <input type="text" class="form-control" name="author" value="<?php echo htmlspecialchars($row['author']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Published Date</label>
                                <input type="date" class="form-control" name="published_date" value="<?php echo $row['published_date']; ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Excerpt / Short Description</label>
                            <textarea class="form-control" name="excerpt" rows="2"><?php echo htmlspecialchars($row['excerpt']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Full Content</label>
                            <textarea class="form-control" name="content" rows="6"><?php echo htmlspecialchars($row['content']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Featured Image</label><br>
                            <?php if (!empty($row['image'])): ?>
                                <img src="../images/<?php echo htmlspecialchars($row['image']); ?>"
                                     style="max-width:200px;max-height:130px;object-fit:cover;border-radius:8px;margin-bottom:10px;"><br>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="removeImg">
                                    <label class="form-check-label text-danger" for="removeImg">Remove current image</label>
                                </div>
                            <?php else: ?>
                                <p class="text-muted small">No image uploaded.</p>
                            <?php endif; ?>
                            <label class="form-label">Upload New Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="published" <?php echo $row['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
                                <option value="draft" <?php echo $row['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                            </select>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" name="edit_blog" class="btn btn-primary btn-lg">Update Blog Post</button>
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
