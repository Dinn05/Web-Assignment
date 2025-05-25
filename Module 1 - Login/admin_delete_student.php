<?php
session_start();

if (!isset($_SESSION['Login']) || $_SESSION['Login'] !== "YES" || $_SESSION['role'] !== 'administrator') {
    echo "<h1>Access Denied</h1><p>You must <a href='../Module 1 - Login/login.php'>login</a> as an administrator.</p>";
    exit();
}

if (!isset($_GET['student_id'])) {
    die("Student ID missing.");
}

$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("Connection failed: " . mysqli_connect_error());
$student_id = intval($_GET['student_id']);

// Get login_id to delete from login table after deleting from student
$getLoginIdQuery = "SELECT login_id, profile_picture FROM student WHERE student_id = '$student_id'";
$getLoginResult = mysqli_query($link, $getLoginIdQuery);
$loginData = mysqli_fetch_assoc($getLoginResult);
$login_id = $loginData['login_id'] ?? null;
$profile_picture = $loginData['profile_picture'] ?? '';

if (!$login_id) {
    die("Invalid student ID or login record missing.");
}

// Delete profile picture if exists
if (!empty($profile_picture) && file_exists($profile_picture)) {
    unlink($profile_picture);
}

// Delete student record first
mysqli_query($link, "DELETE FROM student WHERE student_id = '$student_id'");

// Then delete from login table
mysqli_query($link, "DELETE FROM login WHERE login_id = '$login_id'");

echo "<script>alert('Student deleted successfully'); window.location.href='../Module 1 - Login/view_student_registered.php';</script>";
exit();
