<?php 
include 'header.php'; 
require_once '../config/db_config.php'; 
?>

<div class="container-fluid p-4">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">System Carts Overview</h2>
    </div>

    <!-- Cart Table -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table custom-table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Owner</th>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $grand_total = 0;
                        $query = "SELECT c.id AS cart_id, c.session_id, u.first_name, u.last_name, ci.qty, ci.color, ci.id as item_id, p.product_name, p.image, p.price FROM carts c LEFT JOIN users u ON c.user_id = u.id JOIN cart_items ci ON c.id = ci.cart_id JOIN products p ON ci.product_id = p.id ORDER BY ci.created_at DESC";
                        
                        // create tables if not exists to avoid errors on fresh start
                        $check_table = mysqli_query($connection, "SHOW TABLES LIKE 'carts'");
                        if($check_table && mysqli_num_rows($check_table) > 0) {
                            $result = mysqli_query($connection, $query);

                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $owner = !empty($row['first_name']) ? htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) : 'Guest (' . substr($row['session_id'], 0, 8) . '...)';
                                    $total = $row['price'] * $row['qty'];
                                    $grand_total += $total;
                            ?>
                            <tr>
                                <td><span class="badge bg-secondary"><?php echo $owner; ?></span></td>
                                <td>
                                    <img src="../images/<?php echo htmlspecialchars($row['image']); ?>"
                                        style="width:50px;height:50px;border-radius:8px;object-fit:cover;">
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['product_name']); ?></strong>
                                    <?php echo !empty($row['color']) ? '<br><small class="text-muted">Color: ' . htmlspecialchars($row['color']) . '</small>' : ''; ?>
                                </td>
                                <td>$<?php echo number_format($row['price'], 2); ?></td>
                                <td><?php echo $row['qty']; ?></td>
                                <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                                <td><span class="badge bg-success px-2 py-1 rounded">Active</span></td>
                                <td>
                                    <a href="delete_cart_item.php?id=<?php echo $row['item_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove this item from the cart?');"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center py-4'>No items to display.</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center py-4'>Cart tables not initialized yet. Visit the shop and add something to cart.</td></tr>";
                        }
                        ?>
                    </tbody>

                    <!-- Cart Summary -->
                    <tfoot>
                        <tr class="table-light">
                            <th colspan="5" class="text-end">Value across all active carts:</th>
                            <th colspan="3" class="fs-5 text-primary">$<?php echo number_format($grand_total, 2); ?></th>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
    </div>

</div>

<?php include 'footer.php'; ?>
