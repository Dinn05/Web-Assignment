<?php
session_start();
$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("Connection failed");

// ✅ Ensure student_id is sent via POST
if (!isset($_POST['student_id'])) {
    $_SESSION['upload_msg'] = "❌ Invalid request.";
    header("Location: ../Module 1 - Login/register_petakom.php");
    exit();
}

$student_id = mysqli_real_escape_string($link, $_POST['student_id']);

// ✅ Fetch current student_card filename from database
$query = "SELECT student_card FROM membership WHERE student_id = '$student_id'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_assoc($result);

if ($row && !empty($row['student_card'])) {
    $filename = $row['student_card'];
    $filepath = realpath(__DIR__ . "/../uploads/" . $filename);

    // ✅ Delete file if it exists
    if ($filepath && file_exists($filepath)) {
        if (unlink($filepath)) {
            $_SESSION['upload_msg'] = "✅ Image deleted successfully.";
        } else {
            $_SESSION['upload_msg'] = "❌ Failed to delete image file.";
        }
    } else {
        $_SESSION['upload_msg'] = "⚠️ File not found on server.";
    }

    // ✅ Clear student_card in database
    $update = mysqli_query($link, "UPDATE membership SET student_card = NULL WHERE student_id = '$student_id'");
    if (!$update) {
        $_SESSION['upload_msg'] .= " ❌ Database update failed.";
    }

} else {
    $_SESSION['upload_msg'] = "⚠️ No image found to delete.";
}

// ✅ Redirect back to registration page
header("Location: ../Module 1 - Login/register_petakom.php");
exit();
?>
