<?php include 'header.php'; ?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Users</h2>
        <a href="add_user.php" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Add New User
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>Avatar</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include '../config/db_config.php';
                        $query = "SELECT * FROM users ORDER BY id DESC";
                        $result = mysqli_query($connection, $query);
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $statusClass = $row['is_verified'] == 1 ? 'status-active' : 'status-inactive';
                                $statusText = $row['is_verified'] == 1 ? 'Verified' : 'Unverified';
                                $img = !empty($row['profile_photo']) ? '../images/' . $row['profile_photo'] : '../images/person_1.jpg'; // fallback
                                ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo htmlspecialchars($img); ?>" alt="User" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
                                    </td>
                                    <td class="fw-bold"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><span class="badge bg-secondary"><?php echo htmlspecialchars(ucfirst($row['role'])); ?></span></td>
                                    <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
                                    <td>
                                        <a href="#" class="action-icon"><i class="fas fa-eye"></i></a>
                                        <a href="#" class="action-icon"><i class="fas fa-trash"></i></a>
                                        <a href="#" class="action-icon"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No users found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
