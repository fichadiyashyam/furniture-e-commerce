<?php

include 'header.php';

include 'sidebar.php';
include '../config/db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_team'])) {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $position = mysqli_real_escape_string($connection, $_POST['position']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $status = mysqli_real_escape_string($connection, $_POST['status']);

    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
        $filename = $_FILES['image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (in_array(strtolower($ext), $allowed)) {
            $new_filename = uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . $new_filename)) {
                $image = $new_filename;
            }
        }
    }

    $query = "INSERT INTO team (name, position, description, image, status) VALUES ('$name', '$position', '$description', '$image', '$status')";

    if (mysqli_query($connection, $query)) {
        echo "<script>window.location.href='team.php?msg=" . urlencode("Team member added successfully") . "';</script>";
    } else {
        $error = "Error adding member: " . mysqli_error($connection);
    }
}
?>

    <div class="container-fluid mt-5">
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="h3 mb-0 text-gray-800">Add Team Member</h1>
            </div>
            <div class="col-auto">
                <a href="team.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Team
                </a>
            </div>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
        endif; ?>

        <div class="row">
            <div class="col-md-8  mx-auto">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Edit Details</h6>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)) {
                            echo "<div class='alert alert-danger'>$error</div>";
                        } ?>
                        <?php if (isset($success)) {
                            echo "<div class='alert alert-success'>$success</div>";
                        } ?>
                        <form action="" method="POST" enctype="multipart/form-data">

                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Name</label>
                                    <input type="text" class="form-control" name="name" required
                                        placeholder="Enter full name">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Position</label>
                                    <input type="text" class="form-control" name="position" required
                                        placeholder="e.g. CEO, Founder">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Description</label>
                                    <textarea class="form-control" name="description" rows="4" required
                                        placeholder="Brief biography..."></textarea>
                                </div>
                            </div>
                            <div class="col-md-4 border-start">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Profile Image</label>
                                    <input type="file" class="form-control" name="image">
                                    <small class="text-muted">Recommended: Square image (800x800px).</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="active" selected>Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="submit" name="add_team" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save"></i> Save Member
                                    </button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <?php include 'footer.php'; ?>