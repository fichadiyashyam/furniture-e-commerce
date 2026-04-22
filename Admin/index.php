<?php include 'header.php'; ?>

        <div class="container-fluid p-4">
            <?php $dashboard_success = $_SESSION['dashboard_success'] ?? null; unset($_SESSION['dashboard_success']); ?>
            <?php if ($dashboard_success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($dashboard_success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-3">
                    <div class="dashboard-card">
                        <div class="card-info">
                            <h4>150</h4>
                            <p>Total Orders</p>
                        </div>
                        <div class="card-icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card">
                        <div class="card-info">
                            <h4>53</h4>
                            <p>Products</p>
                        </div>
                        <div class="card-icon">
                            <i class="fas fa-box-open"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card">
                        <div class="card-info">
                            <h4>45</h4>
                            <p>New Customers</p>
                        </div>
                        <div class="card-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card">
                        <div class="card-info">
                            <h4>$12,500</h4>
                            <p>Revenue</p>
                        </div>
                        <div class="card-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#ORD-001</td>
                                    <td>John Doe</td>
                                    <td>Ergonomic Chair</td>
                                    <td>2023-10-01</td>
                                    <td><span class="status-badge status-active">Completed</span></td>
                                    <td>$150.00</td>
                                    <td>
                                        <a href="#" class="action-icon"><i class="fas fa-eye"></i></a>
                                        <a href="#" class="action-icon"><i class="fas fa-trash"></i></a>
                                        <a href="#" class="action-icon"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#ORD-002</td>
                                    <td>Jane Smith</td>
                                    <td>Wooden Table</td>
                                    <td>2023-10-02</td>
                                    <td><span class="status-badge status-inactive">Pending</span></td>
                                    <td>$350.00</td>
                                    <td>
                                        <a href="#" class="action-icon"><i class="fas fa-eye"></i></a>
                                        <a href="#" class="action-icon"><i class="fas fa-trash"></i></a>
                                        <a href="#" class="action-icon"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#ORD-003</td>
                                    <td>Mike Johnson</td>
                                    <td>Modern Sofa</td>
                                    <td>2023-10-03</td>
                                    <td><span class="status-badge status-active">Processing</span></td>
                                    <td>$950.00</td>
                                    <td>
                                        <a href="#" class="action-icon"><i class="fas fa-eye"></i></a>
                                        <a href="#" class="action-icon"><i class="fas fa-trash"></i></a>
                                        <a href="#" class="action-icon"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

<?php include 'footer.php'; ?>
