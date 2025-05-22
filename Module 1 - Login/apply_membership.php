<?php
session_start();
$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("Connection failed");

// ✅ Ensure student_id is provided
if (!isset($_POST['student_id'])) {
    $_SESSION['upload_msg'] = "❌ Invalid request: student ID missing.";
    header("Location: register_petakom.php");
    exit();
}

$student_id = mysqli_real_escape_string($link, $_POST['student_id']);

// ✅ Update membership status
$update_sql = "
    UPDATE membership 
    SET status = 'Pending', 
        registered_date = NOW(), 
        approved_by_admin_id = NULL 
    WHERE student_id = '$student_id'
";

$update_result = mysqli_query($link, $update_sql);

if ($update_result) {
    $_SESSION['upload_msg'] = "✅ Membership application submitted. Awaiting admin approval.";
} else {
    $_SESSION['upload_msg'] = "❌ Failed to submit application: " . mysqli_error($link);
}

header("Location: register_petakom.php");
exit();
?>
