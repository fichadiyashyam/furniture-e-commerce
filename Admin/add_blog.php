<?php
include 'header.php';
include '../config/db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_blog'])) {
    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $author = mysqli_real_escape_string($connection, $_POST['author']);
    $excerpt = mysqli_real_escape_string($connection, $_POST['excerpt']);
    $content = mysqli_real_escape_string($connection, $_POST['content']);
    $published_date = mysqli_real_escape_string($connection, $_POST['published_date']);
    $status = mysqli_real_escape_string($connection, $_POST['status']);

    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ["jpg", "jpeg", "png", "gif", "webp"];
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($file_ext, $allowed)) {
            if (!is_dir('../images/')) mkdir('../images/', 0777, true);
            $new_name = "blog_" . time() . "_" . rand(100,999) . "." . $file_ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], "../images/" . $new_name)) {
                $image = $new_name;
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Invalid image format.";
        }
    }

    if (!isset($error)) {
        $q = "INSERT INTO blogs (title, excerpt, content, image, author, published_date, status)
              VALUES ('$title', '$excerpt', '$content', '$image', '$author', '$published_date', '$status')";
        if (mysqli_query($connection, $q)) {
            echo "<script>window.location.href='blogs.php?msg=" . urlencode("Blog post added successfully") . "';</script>";
            exit();
        } else {
            $error = "Failed to add post: " . mysqli_error($connection);
        }
    }
}
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0">Add Blog Post</h2>
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
                            <input type="text" class="form-control" name="title" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Author</label>
                                <input type="text" class="form-control" name="author" value="Admin">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Published Date</label>
                                <input type="date" class="form-control" name="published_date" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Excerpt / Short Description</label>
                            <textarea class="form-control" name="excerpt" rows="2" placeholder="Short summary shown on the blog listing..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Full Content</label>
                            <textarea class="form-control" name="content" rows="6"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Featured Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" name="add_blog" class="btn btn-primary btn-lg">Add Blog Post</button>
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
