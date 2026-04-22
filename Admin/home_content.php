<?php

include 'header.php';

include '../config/db_config.php';
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Home Page Content</h2>
        <a href="add_home_content.php" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Add New Section
        </a>
    </div>

    <!-- Alert Messages -->
    <?php
if (isset($_GET['msg'])) {
    $msg = htmlspecialchars($_GET['msg']);
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                $msg
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
}
if (isset($_GET['error'])) {
    $error = htmlspecialchars($_GET['error']);
    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                $error
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
}
?>

    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Section Name</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Link</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$query = "SELECT * FROM home_content ORDER BY id DESC";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
?>
                                <tr>
                                    <td>
                                        <?php if (!empty($row['image'])) { ?>
                                            <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" alt="Section Image" style="width: 80px; height: 50px; object-fit: cover; border-radius: 5px;">
                                        <?php
        }
        else { ?>
                                            <span class="text-muted">No image</span>
                                        <?php
        }?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['section_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td>
                                        <?php
        $desc = htmlspecialchars($row['description']);
        echo strlen($desc) > 50 ? substr($desc, 0, 50) . '...' : $desc;
?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['link']); ?></td>
                                    <td>
                                       

                                        <div class="btn-group" role="group">
                                            <a href="edit_home_content.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="delete_home_content.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this section?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
    }
}
else {
    echo "<tr><td colspan='6' class='text-center'>No content sections found.</td></tr>";
}
?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php

mysqli_close($connection);
include 'footer.php';

?>
