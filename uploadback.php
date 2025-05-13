<?php
session_start();
$link = mysqli_connect("localhost", "root", "", "login") or die("Connection failed: " . mysqli_connect_error());

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileBackToUpload']) && isset($_POST['id'])) {
    $student_id = mysqli_real_escape_string($link, $_POST['id']);
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);

    $file_name = basename($_FILES["fileBackToUpload"]["name"]);
    $target_file = $target_dir . $file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($file_type, $allowed)) {
        $_SESSION['upload_msg_back'] = "❌ Invalid file type.";
    } elseif (move_uploaded_file($_FILES["fileBackToUpload"]["tmp_name"], $target_file)) {
        $query = "UPDATE users SET student_card_back = '$file_name' WHERE id = '$student_id'";
        $_SESSION['upload_msg_back'] = mysqli_query($link, $query) ? "✅ Upload successful!" : "❌ DB update failed.";
    } else {
        $_SESSION['upload_msg_back'] = "❌ File upload failed.";
    }

    echo '<form id="redirectForm" action="register_petakom.php" method="POST">
            <input type="hidden" name="id" value="' . $student_id . '">
          </form>
          <script>document.getElementById("redirectForm").submit();</script>';
    exit();
}
?>
