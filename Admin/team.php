<?php 
include 'header.php'; 
include 'sidebar.php';
include '../config/db_config.php';

$query = "SELECT * FROM team ORDER BY id DESC";
$result = mysqli_query($connection, $query);

if (!$result) {
    $error_msg = mysqli_error($connection);
}
?>


    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 mb-0 text-gray-800">Our Team</h2>
            <a href="add_team.php" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Add Team Member
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
                                <th style="width: 80px;">Image</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($row['image'])): ?>
                                            <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" alt="Team Member" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                        <?php else: ?>
                                            <div class="bg-light d-flex align-items-center justify-content-center border" style="width: 50px; height: 50px; border-radius: 5px;">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-bold"><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['position']); ?></td>
                                    <td>
                                        <?php 
                                        $desc = $row['description'];
                                        echo strlen($desc) > 80 ? htmlspecialchars(substr($desc, 0, 80)) . '...' : htmlspecialchars($desc); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $statusClass = $row['status'] == 'active' ? 'status-active' : 'status-inactive';
                                        $statusText = $row['status'] == 'active' ? 'Active' : 'Inactive';
                                        ?>
                                        <span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="edit_team.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="delete_team.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this team member?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No team members found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


<?php include 'footer.php'; ?>
