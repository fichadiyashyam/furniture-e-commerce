<?php

include 'header.php';

include '../config/db_config.php';
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Products</h2>
        <a href="add_product.php" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Add New Product
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <?php
if (isset($_GET['msg'])) {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                        " . htmlspecialchars($_GET['msg']) . "
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                      </div>";
}
if (isset($_GET['error'])) {
    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        " . htmlspecialchars($_GET['error']) . "
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                      </div>";
}
?>
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$query = "SELECT * FROM products ORDER BY id DESC";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $statusClass = $row['status'] == 'active' ? 'status-active' : 'status-inactive';
        $statusText = $row['status'] == 'active' ? 'Active' : 'Inactive';
?>
                                <tr>
                                    <td>
                                        <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" alt="Product" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                    </td>
                                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                    <td><?php echo htmlspecialchars(ucfirst($row['category'])); ?></td>
                                    <td>$<?php echo number_format($row['price'], 2); ?></td>
                                    <td><?php echo intval($row['stock']); ?></td>
                                    <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
                                    <td>
                                       

                                        <div class="btn-group" role="group">
                                            <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="delete_blog_content.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this section?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
    }
}
else {
    echo "<tr><td colspan='7' class='text-center'>No products found.</td></tr>";
}
?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
