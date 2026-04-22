<?php
include 'config/db_config.php';

$sql = "CREATE TABLE IF NOT EXISTS team (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    position VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($connection, $sql)) {
    echo "Table 'team' created successfully<br>";
} else {
    echo "Error creating table: " . mysqli_error($connection) . "<br>";
}

mysqli_close($connection);
?>
