<?php 
include 'header.php'; 
require_once '../config/db_config.php';
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Orders Management</h2>
    </div>

    <!-- Ensure success/error messages are shown -->
    <?php if (isset($_SESSION['admin_success_msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?php 
            echo htmlspecialchars($_SESSION['admin_success_msg']); 
            unset($_SESSION['admin_success_msg']);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['admin_error_msg'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?php 
            echo htmlspecialchars($_SESSION['admin_error_msg']); 
            unset($_SESSION['admin_error_msg']);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="table-responsive bg-white shadow-sm rounded">
                <table class="table custom-table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Email Address</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT o.*, u.first_name, u.last_name, u.email 
                                  FROM orders o 
                                  JOIN users u ON o.user_id = u.id 
                                  ORDER BY o.created_at DESC";
                        
                        // Fallback check if tables not setup
                        $check_table = mysqli_query($connection, "SHOW TABLES LIKE 'orders'");
                        if($check_table && mysqli_num_rows($check_table) > 0) {
                            $result = mysqli_query($connection, $query);

                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $customer = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
                                    $status = $row['status']; // pending, accepted, rejected
                                    
                                    $badge = 'bg-warning text-dark';
                                    if ($status == 'accepted') $badge = 'bg-success text-white';
                                    if ($status == 'rejected') $badge = 'bg-danger text-white';
                        ?>
                        <tr>
                            <td><strong>#ORD-<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></strong></td>
                            <td><?php echo $customer; ?></td>
                            <td><a href="mailto:<?php echo htmlspecialchars($row['email']); ?>"><?php echo htmlspecialchars($row['email']); ?></a></td>
                            <td><?php echo date('M d, Y g:i A', strtotime($row['created_at'])); ?></td>
                            <td>$<?php echo number_format($row['total_amount'], 2); ?></td>
                            <td><span class="badge <?php echo $badge; ?> px-2 py-1"><?php echo ucfirst($status); ?></span></td>
                            <td>
                                <!-- Action Buttons -->
                                <?php if ($status == 'pending'): ?>
                                    <form action="update_order_status.php" method="POST" class="d-inline">
                                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="action" value="accept">
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Accept Order">
                                            <i class="fas fa-check"></i> Accept
                                        </button>
                                    </form>
                                    
                                    <form action="update_order_status.php" method="POST" class="d-inline ms-1">
                                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Reject Order">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted"><i class="fas fa-lock"></i> Finalized</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center py-4'>No orders found.</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center py-4'>Order tables not initialized.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
