<?php
session_start();
$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("Connection failed: " . mysqli_connect_error());

if (!isset($_POST['id']) || !isset($_FILES['fileToUpload'])) {
    $_SESSION['upload_msg'] = "❌ Invalid upload request.";
    header("Location: register_petakom.php");
    exit();
}

$student_id = mysqli_real_escape_string($link, $_POST['id']);
$target_dir = "../uploads/";
$filename = basename($_FILES["fileToUpload"]["name"]);
$target_file = $target_dir . time() . "_" . $filename;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// ✅ Validate image
$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
if ($check === false) {
    $_SESSION['upload_msg'] = "❌ File is not an image.";
    header("Location: ../Module 1 - Login/register_petakom.php");
    exit();
}

// ✅ Move uploaded file
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    $stored_name = basename($target_file);

    // ✅ Check if student already has a membership record
    $check_query = "SELECT * FROM membership WHERE student_id = '$student_id'";
    $check_result = mysqli_query($link, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // ✅ Update existing record
        $update = "UPDATE membership SET student_card = '$stored_name' WHERE student_id = '$student_id'";
    } else {
        // ✅ Insert new record
        $update = "INSERT INTO membership (student_id, student_card) VALUES ('$student_id', '$stored_name')";
    }

    if (mysqli_query($link, $update)) {
        $_SESSION['upload_msg'] = "✅ Successful upload to administrator.";
    } else {
        $_SESSION['upload_msg'] = "❌ Database operation failed.";
    }
} else {
    $_SESSION['upload_msg'] = "❌ Error uploading file.";
}

header("Location: register_petakom.php");
exit();
?>
