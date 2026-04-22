<?php
session_start();
session_unset();
session_destroy();
session_start(); // Start a new session to store the flash message
$_SESSION['login_success'] = "You have been successfully logged out.";
header('Location: login.php');
exit;
