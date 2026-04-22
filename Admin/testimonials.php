<?php 
include 'header.php'; 
include '../config/db_config.php';

// Fetch all testimonials
$query = "SELECT * FROM testimonial ORDER BY id DESC";
$result = mysqli_query($connection, $query);

if (!$result) {
   
    $error_msg = mysqli_error($connection);
}
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Manage Testimonials</h2>
        <a href="add_testimonial.php" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 me-2"></i> Add New Testimonial
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Alert Messages -->
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
                            <th style="width: 80px;">Image</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Quote</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (isset($result) && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) { 
                                ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($row['image'])) { ?>
                                            <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" alt="User" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                        <?php } else { ?>
                                            <div class="bg-light d-flex align-items-center justify-content-center border" style="width: 50px; height: 50px; border-radius: 5px;">
                                                <i class="fas fa-user-circle text-muted"></i>
                                            </div>
                                        <?php } ?>
                                    </td>
                                    <td class="fw-bold"><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['position']); ?></td>
                                    <td class="text-muted small">
                                        <?php 
                                        $quote = strip_tags($row['quote']);
                                        echo strlen($quote) > 100 ? htmlspecialchars(substr($quote, 0, 100)) . '...' : htmlspecialchars($quote); 
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="edit_testimonial.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="delete_testimonial.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this testimonial?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                            } 
                        } else if (isset($result)) { ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No testimonials found.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php 
if (isset($connection)) mysqli_close($connection);
include 'footer.php'; 
?>
