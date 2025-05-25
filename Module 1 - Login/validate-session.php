<?php
session_start();

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0"); 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");

// Check if logged in
if (!isset($_SESSION['Login']) || $_SESSION['Login'] !== "YES") {
    session_unset();
    session_destroy();
    header("Location: ../Module 1 - Login/login.php");
    exit();
} 
?>
