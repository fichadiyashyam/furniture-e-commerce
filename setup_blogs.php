<?php
include 'config/db_config.php';

$sql = "CREATE TABLE IF NOT EXISTS blogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255),
    excerpt TEXT,
    content TEXT,
    image VARCHAR(255),
    author VARCHAR(100) DEFAULT 'Admin',
    published_date DATE,
    status ENUM('published', 'draft') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($connection, $sql)) {
    echo "Table 'blogs' created (or already exists).";
} else {
    echo "Error: " . mysqli_error($connection);
}

mysqli_close($connection);
?>
