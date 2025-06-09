<?php
session_start();
$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("Connection failed");

if (!isset($_POST['membership_id'], $_POST['action'])) {
    die("Invalid request.");
}

$membership_id = (int)$_POST['membership_id'];
$action = $_POST['action'] === 'approve' ? 'Approved' : 'Rejected';
$admin_id = $_SESSION['admin_id'] ?? null;

$query = "SELECT student_card FROM membership WHERE membership_id = $membership_id";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_assoc($result);
$card_file = $row['student_card'];

if ($card_file && file_exists("../uploads/$card_file")) {
    unlink("../uploads/$card_file");
}

$update = "UPDATE membership SET status='$action', student_card='', approved_by_admin_id='$admin_id' WHERE membership_id=$membership_id";
mysqli_query($link, $update);

header("Location: view_applied_membership.php");
exit();
