<?php
session_start();
$link = mysqli_connect("localhost", "root", "", "login") or die("Connection failed: " . mysqli_connect_error());

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $student_id = mysqli_real_escape_string($link, $_POST['id']);

    // Get current filename
    $query = "SELECT student_card_front FROM users WHERE id = '$student_id'";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row && !empty($row['student_card_front'])) {
        $file = 'uploads/' . $row['student_card_front'];

        // Delete physical file
        if (file_exists($file)) {
            unlink($file);
        }

        // Remove filename from DB
        $update = "UPDATE users SET student_card_front = NULL WHERE id = '$student_id'";
        if (mysqli_query($link, $update)) {
            $_SESSION['upload_status'] = "✅ Image deleted successfully.";
        } else {
            $_SESSION['upload_status'] = "❌ Failed to update database.";
        }
    } else {
        $_SESSION['upload_status'] = "❌ No image found to delete.";
    }

    // Redirect back to registration page
    echo '<form id="redirectForm" action="register_petakom.php" method="POST">
            <input type="hidden" name="id" value="' . $student_id . '">
          </form>
          <script>document.getElementById("redirectForm").submit();</script>';
    exit();
} else {
    echo "❌ Invalid request.";
}
?>
