<?php
include 'header.php';
include '../config/db_config.php';
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0">Blog Posts</h2>
        <a href="add_blog.php" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Post
        </a>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo htmlspecialchars($_GET['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php
endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo htmlspecialchars($_GET['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php
endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Image</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $query = "SELECT * FROM blogs ORDER BY created_at DESC";
                    $result = mysqli_query($connection, $query);
                    if ($result && mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)):
                            $statusClass = $row['status'] == 'published' ? 'status-active' : 'status-inactive';
                            $statusText = $row['status'] == 'published' ? 'Published' : 'Draft';
                    ?>
                        <tr>
                            <td>
                                <?php if (!empty($row['image'])): ?>
                                    <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center border" style="width: 50px; height: 50px; border-radius: 5px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold"><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['author']); ?></td>
                            <td><?php echo !empty($row['published_date']) ? date('M d, Y', strtotime($row['published_date'])) : '—'; ?></td>
                            <td>
                                <span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="edit_blog.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="delete_blog.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this post?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php
                        endwhile;
                    else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No blog posts found. <a href="add_blog.php">Add your first post!</a></td>
                        </tr>
                    <?php
                    endif; ?>
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
