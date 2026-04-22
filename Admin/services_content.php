<?php 
include 'header.php'; 
include '../config/db_config.php';

// Fetch all services content
$query = "SELECT * FROM services_content WHERE section_name = 'feature' ORDER BY id ASC";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($connection));
}
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Manage Services (Why Choose Us)</h2>
        <a href="add_services_content.php" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 me-2"></i> Add New Feature
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_GET['msg']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Icon</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) { 
                                $statusClass = $row['status'] == 'active' ? 'status-active' : 'status-inactive';
                                $statusText = $row['status'] == 'active' ? 'Active' : 'Inactive';
                                ?>
                                <tr>
                                    <td class="text-center">
                                        <?php if (!empty($row['image'])) { ?>
                                            <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" alt="Icon" style="width: 40px; height: 40px; object-fit: contain;">
                                        <?php } else { ?>
                                            <span class="text-muted">No Icon</span>
                                        <?php } ?>
                                    </td>
                                    <td class="fw-bold"><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td class="text-muted small">
                                        <?php 
                                        $desc = strip_tags($row['description']);
                                        echo strlen($desc) > 80 ? htmlspecialchars(substr($desc, 0, 80)) . '...' : htmlspecialchars($desc); 
                                        ?>
                                    </td>
                                    <td>
                                        <span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="edit_services_content.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="delete_services_content.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this feature?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                            } 
                        } else { ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No features found.</td>
                            </tr>
                        <?php } ?>
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
