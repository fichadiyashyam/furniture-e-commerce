<?php

include 'header.php';

include '../config/db_config.php';

// Fetch all about content
$query = "SELECT * FROM about_content ORDER BY id ASC";
$result = mysqli_query($connection, $query);

// Check if query failed
if (!$result) {
    die("Database query failed: " . mysqli_error($connection));
}
?>
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Manage About Us</h2>
        <a href="add_about_content.php" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 me-2"></i> Add New Content
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Image</th>
                            <th>Section Type</th>
                            <th>Title / Name</th>
                            <th>Description / Bio</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0) { ?>
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($row['image'])) { ?>
                                            <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" alt="Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                        <?php
        }
        else { ?>
                                            <div class="bg-light d-flex align-items-center justify-content-center border" style="width: 50px; height: 50px; border-radius: 5px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        <?php
        }?>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($row['section_name']); ?></span>
                                    </td>
                                    <td class="fw-bold"><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td class="text-muted small">
                                        <?php
        $desc = strip_tags($row['description']);
        echo strlen($desc) > 80 ? htmlspecialchars(substr($desc, 0, 80)) . '...' : htmlspecialchars($desc);
?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="edit_about_content.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="delete_about_content.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this section?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php
    }?>
                        <?php
}
else { ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No content sections found.</td>
                            </tr>
                        <?php
}?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</div>
<?php

mysqli_close($connection);
include 'footer.php';

?>
