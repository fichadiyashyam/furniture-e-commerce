<?php
require_once 'c:\laragon\www\furni-1.0.0\config\db_config.php';
$result = mysqli_query($connection, "SELECT email, role FROM users");
while($row = mysqli_fetch_assoc($result)){
    echo "Email: " . $row['email'] . " | Role: '" . $row['role'] . "'\n";
}
