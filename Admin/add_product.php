<?php 
include 'header.php'; 
include '../config/db_config.php';

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
    function uploadImage($fileKey) {
        if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] == 0) {
            $allowed = array("jpg", "jpeg", "png", "gif");
            $file_name = $_FILES[$fileKey]['name'];
            $file_tmp = $_FILES[$fileKey]['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (in_array($file_ext, $allowed)) {
                if (!is_dir('../images/')) { mkdir('../images/', 0777, true); }
                $new_file_name = $fileKey . "_" . time() . "_" . rand(100,999) . "." . $file_ext;
                if (move_uploaded_file($file_tmp, "../images/" . $new_file_name)) {
                    return $new_file_name;
                }
            }
        }
        return '';
    }

    $image = uploadImage('image');
    if (empty($image)) { $error = "Product main image is required."; }
    
    $gallery_1 = uploadImage('gallery_image_1');
    $gallery_2 = uploadImage('gallery_image_2');
    $gallery_3 = uploadImage('gallery_image_3');

    if (!isset($error)) {
        $insert_query = "INSERT INTO products (product_name, price, original_price, stock, category, short_description, description, image, gallery_image_1, gallery_image_2, gallery_image_3, sku, material, dimensions, weight, color_options, status) 
                         VALUES ('$product_name', $price, $original_price, $stock, '$category', '$short_description', '$description', '$image', '$gallery_1', '$gallery_2', '$gallery_3', '$sku', '$material', '$dimensions', '$weight', '$color_options', '$status')";
        if (mysqli_query($connection, $insert_query)) {
            echo "<script>window.location.href='products.php?msg=" . urlencode("Product added successfully") . "';</script>";
            exit();
        } else {
            $error = "Failed to add product. " . mysqli_error($connection);
        }
    }
}
?>

<style>
    /* Custom error message */
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
        <h2 class="h3 mb-0 text-gray-800">Add New Product</h2>
        <a href="products.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Products
        </a>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>

                    <form id="addProductForm" action="" method="POST" enctype="multipart/form-data">

                        <!-- Product Name -->
                        <div class="mb-3 position-relative">
                            <label class="form-label">Product Name</label>
                            <input type="text" class="form-control" name="product_name" placeholder="Enter product name"
                                data-validation="required min max alphabetic" data-min="3" data-max="50">
                            <div id="product_name_error"></div>
                        </div>

                        <!-- Price -->
                        <div class="row mb-3">
                            <div class="col-md-4 position-relative">
                                <label class="form-label">Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="price" placeholder="0.00"
                                        step="0.01" data-validation="required number">
                                </div>
                            </div>
                            <div class="col-md-4 position-relative">
                                <label class="form-label">Original Price (Optional)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="original_price" placeholder="0.00"
                                        step="0.01">
                                </div>
                            </div>
                            <div class="col-md-4 position-relative">
                                <label class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="stock" placeholder="0"
                                    data-validation="required number">
                            </div>
                        </div>

                        <!-- Category & SKU -->
                        <div class="row mb-3">
                            <div class="col-md-6 position-relative">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select" name="category" data-validation="required select">
                                    <option value="">Choose category...</option>
                                    <option value="chairs">Chairs</option>
                                    <option value="tables">Tables</option>
                                    <option value="sofas">Sofas</option>
                                    <option value="beds">Beds</option>
                                </select>
                            </div>
                            <div class="col-md-6 position-relative">
                                <label class="form-label">SKU (Optional)</label>
                                <input type="text" class="form-control" name="sku" placeholder="e.g. CHR-VLV-001">
                            </div>
                        </div>

                        <!-- Product Specs -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 position-relative">
                                <label class="form-label">Material (Optional)</label>
                                <input type="text" class="form-control" name="material" placeholder="e.g. Velvet Fabric, Solid Wood">
                            </div>
                            <div class="col-md-6 mb-3 position-relative">
                                <label class="form-label">Dimensions (Optional)</label>
                                <input type="text" class="form-control" name="dimensions" placeholder="e.g. 70 cm x 65 cm x 85 cm">
                            </div>
                            <div class="col-md-6 mb-3 position-relative">
                                <label class="form-label">Weight (Optional)</label>
                                <input type="text" class="form-control" name="weight" placeholder="e.g. 12 kg">
                            </div>
                            <div class="col-md-6 mb-3 position-relative">
                                <label class="form-label">Color Options (Optional)</label>
                                <input type="text" class="form-control" name="color_options" placeholder="e.g. Navy Blue,Grey,Pink">
                                <small class="text-muted">Comma separated</small>
                            </div>
                        </div>

                        <!-- Short Description -->
                        <div class="mb-3 position-relative">
                            <label class="form-label">Short Description (Optional)</label>
                            <textarea class="form-control" name="short_description" rows="2"
                                placeholder="Enter a brief summary for the product detail top section"></textarea>
                        </div>

                        <!-- Full Description -->
                        <div class="mb-3 position-relative">
                            <label class="form-label">Full Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" rows="4"
                                placeholder="Enter full product specifications" data-validation="required min max" data-min="10"
                                data-max="2000"></textarea>
                        </div>

                        <!-- Images -->
                        <div class="mb-3 position-relative">
                            <label class="form-label">Main Image <span class="text-danger">*</span></label>
                            <input class="form-control" type="file" name="image" accept="image/*" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Gallery Image 1</label>
                                <input class="form-control" type="file" name="gallery_image_1" accept="image/*">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Gallery Image 2</label>
                                <input class="form-control" type="file" name="gallery_image_2" accept="image/*">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Gallery Image 3</label>
                                <input class="form-control" type="file" name="gallery_image_3" accept="image/*">
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label class="form-label d-block">Status</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" value="active" checked
                                    data-validation="required">
                                <label class="form-check-label">Active</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" value="inactive">
                                <label class="form-check-label">Inactive</label>
                            </div>
                            <div id="status_error"></div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Save Product
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

        $("#addProductForm").validate({

            rules: {
                product_name: {
                    required: true,
                    minlength: 3
                },
                price: {
                    required: true,
                    number: true,
                    min: 1
                },
                stock: {
                    required: true,
                    digits: true,
                    min: 0
                },
                category: {
                    required: true
                },
                description: {
                    required: true,
                    minlength: 10
                },
                image: {
                    required: true,
                    extension: "jpg|jpeg|png"
                }
            },

            messages: {
                product_name: {
                    required: "Product name is required",
                    minlength: "At least 3 characters required"
                },
                price: {
                    required: "Price is required",
                    number: "Enter a valid price",
                    min: "Price must be greater than 0"
                },
                stock: {
                    required: "Stock quantity is required",
                    digits: "Only whole numbers allowed",
                    min: "Stock cannot be negative"
                },
                category: {
                    required: "Please select a category"
                },
                description: {
                    required: "Description is required",
                    minlength: "Minimum 10 characters required"
                },
                image: {
                    required: "Product image is required",
                    extension: "Only JPG, JPEG, PNG files allowed"
                }
            },

            errorElement: "span",
            errorClass: "error-message",

            highlight: function (element) {
                $(element)
                    .removeClass("is-valid")
                    .addClass("is-invalid")
                    .next(".valid-feedback-icon").remove();
            },

            unhighlight: function (element) {
                $(element)
                    .removeClass("is-invalid")
                    .addClass("is-valid");


            },

            errorPlacement: function (error, element) {
                error.insertAfter(element);
            },

            submitHandler: function (form) {
                // Remove alert to just submit directly, or leave it and submit.
                form.submit();
            }
        });

    });
</script>

<?php include 'footer.php'; ?>