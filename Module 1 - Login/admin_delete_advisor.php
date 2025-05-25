<?php
session_start();

if (!isset($_SESSION['Login']) || $_SESSION['Login'] !== "YES" || $_SESSION['role'] !== 'administrator') {
    echo "<h1>Access Denied</h1><p>You must <a href='../Module 1 - Login/login.php'>login</a> as an administrator.</p>";
    exit();
}

if (!isset($_GET['staff_id'])) {
    die("Invalid request.");
}

$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("Connection failed: " . mysqli_connect_error());
$staff_id = intval($_GET['staff_id']);

// Get profile picture path before deletion
$query = "SELECT profile_picture, login_id FROM staff WHERE staff_id = '$staff_id'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_assoc($result);
$profile_picture = $row['profile_picture'];
$login_id = $row['login_id'];

// Delete image file if exists
if (!empty($profile_picture) && file_exists($profile_picture)) {
    unlink($profile_picture);
}

// Delete from staff and login
mysqli_query($link, "DELETE FROM staff WHERE staff_id = '$staff_id'");
mysqli_query($link, "DELETE FROM login WHERE login_id = '$login_id'");

echo "<script>alert('Event Advisor deleted successfully'); window.location.href='../Module 1 - Login/view_event_advisor_registered.php';</script>";
exit();

?>
