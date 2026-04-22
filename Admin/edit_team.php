<?php

include 'header.php';

include 'sidebar.php';
include '../config/db_config.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = mysqli_real_escape_string($connection, $_GET['id']);
    $query = "SELECT * FROM team WHERE id = '$id'";
    $result = mysqli_query($connection, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $member = mysqli_fetch_assoc($result);
    }
    else {
        echo "<script>window.location.href='team.php?error=" . urlencode("Member not found.") . "';</script>";
    }
}
else {
    echo "<script>window.location.href='team.php';</script>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_team'])) {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $position = mysqli_real_escape_string($connection, $_POST['position']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $status = mysqli_real_escape_string($connection, $_POST['status']);

    $image = $member['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
        $filename = $_FILES['image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (in_array(strtolower($ext), $allowed)) {
            $new_filename = uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . $new_filename)) {

                if (!empty($image) && file_exists("../images/" . $image)) {
                    unlink("../images/" . $image);
                }
                $image = $new_filename;
            }
        }
    }

    $update_query = "UPDATE team SET name='$name', position='$position', description='$description', image='$image', status='$status' WHERE id='$id'";

    if (mysqli_query($connection, $update_query)) {
        echo "<script>window.location.href='team.php?msg=" . urlencode("Team member updated successfully") . "';</script>";
    }
    else {
        $error = "Error updating member: " . mysqli_error($connection);
    }
}
?>


    <div class="container-fluid mt-5">
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="h3 mb-0 text-gray-800">Edit Team Member</h1>
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
                          <?php if (isset($error)) {echo "<div class='alert alert-danger'>$error</div>";}?>
                          <?php if (isset($success)) {echo "<div class='alert alert-success'>$success</div>";}?>
                         <form action="" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Name</label>
                                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($member['name']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Position</label>
                                <input type="text" class="form-control" name="position" value="<?php echo htmlspecialchars($member['position']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <textarea class="form-control" name="description" rows="5" required><?php echo htmlspecialchars($member['description']); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Update Profile Image</label>
                                <input type="file" class="form-control" name="image">
                                <small class="text-muted text-center d-block mt-1">Leave empty to keep current photo.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <select class="form-select" name="status">
                                    <option value="active" <?php echo $member['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo $member['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" name="edit_team" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Update Member
                                </button>
                            </div>    
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
