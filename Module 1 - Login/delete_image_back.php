<?php
$link = mysqli_connect("localhost", "root", "", "login") or die("Connection failed: " . mysqli_connect_error());

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $student_id = mysqli_real_escape_string($link, $_POST['id']);

    $query = "SELECT student_card_back FROM users WHERE id = '$student_id'";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row && !empty($row['student_card_back'])) {
        $file = 'uploads/' . $row['student_card_back'];
        if (file_exists($file)) {
            unlink($file);
        }

        $update = "UPDATE users SET student_card_back = NULL WHERE id = '$student_id'";
        mysqli_query($link, $update);
    }

    echo '<form id="redirectForm" action="register_petakom.php" method="POST">
            <input type="hidden" name="id" value="' . $student_id . '">
          </form>
          <script>document.getElementById("redirectForm").submit();</script>';
    exit();
} else {
    echo "❌ Invalid request.";
}
?>
