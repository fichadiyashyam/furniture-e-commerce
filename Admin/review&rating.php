<?php include 'header.php'; ?>

<div class="container-fluid p-4">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Review & Rating</h2>
        <a href="add_review.php" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Add New Review
        </a>
    </div>

    <!-- Table -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table custom-table align-middle">
                    <thead>
                        <tr>
                            <th>Avatar</th>
                            <th>Name</th>
                            <th>Rating</th>
                            <th>Review</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <!-- Review 1 -->
                        <tr>
                            <td>
                                <img src="../images/person_1.jpg" alt="User"
                                    style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                            </td>
                            <td>Maria Jones</td>
                            <td>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="far fa-star text-muted"></i>
                            </td>
                            <td>Excellent quality and fast delivery. Highly recommended.</td>
                            <td>
                                <span class="status-badge status-active">Approved</span>
                            </td>
                            <td>
                                <a href="#" class="action-icon"><i class="fas fa-eye"></i></a>
                                <a href="#" class="action-icon"><i class="fas fa-edit"></i></a>
                                <a href="#" class="action-icon text-danger"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>

                        <!-- Review 2 -->
                        <tr>
                            <td>
                                <img src="../images/person_2.jpg" alt="User"
                                    style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                            </td>
                            <td>John Smith</td>
                            <td>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="far fa-star text-muted"></i>
                                <i class="far fa-star text-muted"></i>
                            </td>
                            <td>Product is good, but packaging could be improved.</td>
                            <td>
                                <span class="status-badge status-active">Approved</span>
                            </td>
                            <td>
                                <a href="#" class="action-icon"><i class="fas fa-eye"></i></a>
                                <a href="#" class="action-icon"><i class="fas fa-edit"></i></a>
                                <a href="#" class="action-icon text-danger"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>

                        <!-- Review 3 -->
                        <tr>
                            <td>
                                <img src="../images/person_3.jpg" alt="User"
                                    style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                            </td>
                            <td>Robert Doe</td>
                            <td>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="far fa-star text-muted"></i>
                                <i class="far fa-star text-muted"></i>
                                <i class="far fa-star text-muted"></i>
                            </td>
                            <td>Average experience. Expected better quality.</td>
                            <td>
                                <span class="status-badge status-inactive">Pending</span>
                            </td>
                            <td>
                                <a href="#" class="action-icon"><i class="fas fa-eye"></i></a>
                                <a href="#" class="action-icon"><i class="fas fa-edit"></i></a>
                                <a href="#" class="action-icon text-danger"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>

                        <!-- Review 4 -->
                        <tr>
                            <td>
                                <img src="../images/person_4.jpg" alt="User"
                                    style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                            </td>
                            <td>Emily Clark</td>
                            <td>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </td>
                            <td>Absolutely loved it! Will buy again.</td>
                            <td>
                                <span class="status-badge status-active">Approved</span>
                            </td>
                            <td>
                               <div class="btn-group" role="group">
                                            <a href="edit_blog_content.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="delete_blog_content.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this section?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?php include 'footer.php'; ?>