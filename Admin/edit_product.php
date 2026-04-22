<?php 
include 'header.php'; 
include '../config/db_config.php';

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = intval($_GET['id']);

// Fetch existing data
$query = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) == 0) {
    header("Location: products.php");
    exit();
}
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_name'])) {
    $product_name = mysqli_real_escape_string($connection, $_POST['product_name']);
    $price = floatval($_POST['price']);
    $original_price = isset($_POST['original_price']) && $_POST['original_price'] !== '' ? floatval($_POST['original_price']) : 'NULL';
    $stock = intval($_POST['stock']);
    $category = mysqli_real_escape_string($connection, $_POST['category']);
    $short_description = mysqli_real_escape_string($connection, $_POST['short_description']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $sku = mysqli_real_escape_string($connection, $_POST['sku']);
    $material = mysqli_real_escape_string($connection, $_POST['material']);
    $dimensions = mysqli_real_escape_string($connection, $_POST['dimensions']);
    $weight = mysqli_real_escape_string($connection, $_POST['weight']);
    $color_options = mysqli_real_escape_string($connection, $_POST['color_options']);
    $status = mysqli_real_escape_string($connection, $_POST['status']);
    
    // Helper function for image upload
    function uploadImage($fileKey, $oldImage) {
        if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] == 0) {
            $allowed = array("jpg", "jpeg", "png", "gif");
            $file_name = $_FILES[$fileKey]['name'];
            $file_tmp = $_FILES[$fileKey]['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (in_array($file_ext, $allowed)) {
                if (!is_dir('../images/')) { mkdir('../images/', 0777, true); }
                $new_file_name = $fileKey . "_" . time() . "_" . rand(100,999) . "." . $file_ext;
                if (move_uploaded_file($file_tmp, "../images/" . $new_file_name)) {
                    if (!empty($oldImage) && file_exists("../images/" . $oldImage)) {
                        unlink("../images/" . $oldImage);
                    }
                    return $new_file_name;
                }
            }
        }
        return $oldImage; // Keep existing image by default
    }

    $image = uploadImage('image', $row['image']);
    $gallery_1 = uploadImage('gallery_image_1', $row['gallery_image_1']);
    $gallery_2 = uploadImage('gallery_image_2', $row['gallery_image_2']);
    $gallery_3 = uploadImage('gallery_image_3', $row['gallery_image_3']);

    if (!isset($error)) {
        $update_query = "UPDATE products SET 
                            product_name = '$product_name', 
                            price = $price, 
                            original_price = $original_price,
                            stock = $stock, 
                            category = '$category', 
                            short_description = '$short_description', 
                            description = '$description', 
                            image = '$image', 
                            gallery_image_1 = '$gallery_1',
                            gallery_image_2 = '$gallery_2',
                            gallery_image_3 = '$gallery_3',
                            sku = '$sku',
                            material = '$material',
                            dimensions = '$dimensions',
                            weight = '$weight',
                            color_options = '$color_options',
                            status = '$status' 
                         WHERE id = $id";
                         
        if (mysqli_query($connection, $update_query)) {
            echo "<script>window.location.href='products.php?msg=" . urlencode("Product updated successfully") . "';</script>";
            exit();
        } else {
            $error = "Failed to update product. " . mysqli_error($connection);
        }
    }
}
?>

<style>
    .error-message {
        display: block;
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        font-weight: 500;
    }
</style>
<script src="js/validation.js"></script>
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Edit Product: <?php echo htmlspecialchars($row['product_name']); ?></h2>
        <a href="products.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Products
        </a>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>

                    <form id="editProductForm" action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3 position-relative">
                            <label class="form-label">Product Name</label>
                            <input type="text" class="form-control" name="product_name" value="<?php echo htmlspecialchars($row['product_name']); ?>"
                                data-validation="required min max alphabetic" data-min="3" data-max="50">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 position-relative">
                                <label class="form-label">Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="price" value="<?php echo $row['price']; ?>"
                                        step="0.01" data-validation="required number">
                                </div>
                            </div>

                            <div class="col-md-4 position-relative">
                                <label class="form-label">Original Price (Optional)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="original_price" value="<?php echo $row['original_price']; ?>"
                                        step="0.01">
                                </div>
                            </div>

                            <div class="col-md-4 position-relative">
                                <label class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="stock" value="<?php echo $row['stock']; ?>"
                                    data-validation="required number">
                            </div>
                        </div>

                        <!-- Category & SKU -->
                        <div class="row mb-3">
                            <div class="col-md-6 position-relative">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select" name="category" data-validation="required select">
                                    <option value="">Choose category...</option>
                                    <option value="chairs" <?php echo ($row['category'] == 'chairs') ? 'selected' : ''; ?>>Chairs</option>
                                    <option value="tables" <?php echo ($row['category'] == 'tables') ? 'selected' : ''; ?>>Tables</option>
                                    <option value="sofas" <?php echo ($row['category'] == 'sofas') ? 'selected' : ''; ?>>Sofas</option>
                                    <option value="beds" <?php echo ($row['category'] == 'beds') ? 'selected' : ''; ?>>Beds</option>
                                </select>
                            </div>
                            <div class="col-md-6 position-relative">
                                <label class="form-label">SKU (Optional)</label>
                                <input type="text" class="form-control" name="sku" value="<?php echo htmlspecialchars($row['sku']); ?>">
                            </div>
                        </div>

                        <!-- Product Specs -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 position-relative">
                                <label class="form-label">Material (Optional)</label>
                                <input type="text" class="form-control" name="material" value="<?php echo htmlspecialchars($row['material']); ?>">
                            </div>
                            <div class="col-md-6 mb-3 position-relative">
                                <label class="form-label">Dimensions (Optional)</label>
                                <input type="text" class="form-control" name="dimensions" value="<?php echo htmlspecialchars($row['dimensions']); ?>">
                            </div>
                            <div class="col-md-6 mb-3 position-relative">
                                <label class="form-label">Weight (Optional)</label>
                                <input type="text" class="form-control" name="weight" value="<?php echo htmlspecialchars($row['weight']); ?>">
                            </div>
                            <div class="col-md-6 mb-3 position-relative">
                                <label class="form-label">Color Options (Optional)</label>
                                <input type="text" class="form-control" name="color_options" value="<?php echo htmlspecialchars($row['color_options']); ?>">
                                <small class="text-muted">Comma separated</small>
                            </div>
                        </div>

                        <!-- Short Description -->
                        <div class="mb-3 position-relative">
                            <label class="form-label">Short Description (Optional)</label>
                            <textarea class="form-control" name="short_description" rows="2"><?php echo htmlspecialchars($row['short_description']); ?></textarea>
                        </div>

                        <!-- Full Description -->
                        <div class="mb-3 position-relative">
                            <label class="form-label">Full Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" rows="4"
                                data-validation="required min max" data-min="10"
                                data-max="2000"><?php echo htmlspecialchars($row['description']); ?></textarea>
                        </div>

                        <!-- Images -->
                        <div class="mb-3 position-relative">
                            <label class="form-label">Main Image</label><br>
                            <?php if (!empty($row['image'])) { ?>
                                <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" style="max-width: 150px; border-radius: 5px; margin-bottom: 10px;"><br>
                            <?php } ?>
                            <input class="form-control" type="file" name="image" accept="image/*">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Gallery Image 1</label><br>
                                <?php if (!empty($row['gallery_image_1'])) { ?>
                                    <img src="../images/<?php echo htmlspecialchars($row['gallery_image_1']); ?>" style="max-width: 100px; border-radius: 5px; margin-bottom: 5px;"><br>
                                <?php } ?>
                                <input class="form-control" type="file" name="gallery_image_1" accept="image/*">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Gallery Image 2</label><br>
                                <?php if (!empty($row['gallery_image_2'])) { ?>
                                    <img src="../images/<?php echo htmlspecialchars($row['gallery_image_2']); ?>" style="max-width: 100px; border-radius: 5px; margin-bottom: 5px;"><br>
                                <?php } ?>
                                <input class="form-control" type="file" name="gallery_image_2" accept="image/*">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Gallery Image 3</label><br>
                                <?php if (!empty($row['gallery_image_3'])) { ?>
                                    <img src="../images/<?php echo htmlspecialchars($row['gallery_image_3']); ?>" style="max-width: 100px; border-radius: 5px; margin-bottom: 5px;"><br>
                                <?php } ?>
                                <input class="form-control" type="file" name="gallery_image_3" accept="image/*">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Status</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" value="active" <?php echo ($row['status'] == 'active') ? 'checked' : ''; ?>>
                                <label class="form-check-label">Active</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" value="inactive" <?php echo ($row['status'] == 'inactive') ? 'checked' : ''; ?>>
                                <label class="form-check-label">Inactive</label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<script>
    $(document).ready(function () {
        $("#editProductForm").validate({
            rules: {
                product_name: { required: true, minlength: 3 },
                price: { required: true, number: true, min: 1 },
                stock: { required: true, digits: true, min: 0 },
                category: { required: true },
                description: { required: true, minlength: 10 },
                image: { extension: "jpg|jpeg|png" }
            },
            messages: {
                product_name: { required: "Product name is required", minlength: "At least 3 characters required" },
                price: { required: "Price is required", number: "Enter a valid price", min: "Price must be greater than 0" },
                stock: { required: "Stock quantity is required", digits: "Only whole numbers allowed", min: "Stock cannot be negative" },
                category: { required: "Please select a category" },
                description: { required: "Description is required", minlength: "Minimum 10 characters required" },
                image: { extension: "Only JPG, JPEG, PNG files allowed" }
            },
            errorElement: "span",
            errorClass: "error-message",
            highlight: function (element) {
                $(element).removeClass("is-valid").addClass("is-invalid").next(".valid-feedback-icon").remove();
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element);
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    });
</script>

<?php 
mysqli_close($connection);
include 'footer.php'; 
?>
