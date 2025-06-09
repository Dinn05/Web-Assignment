<?php
session_start();
$conn = new mysqli("localhost", "root", "", "mypetakom");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['committee_id'])) {
    $committee_ids = $_POST['committee_id'];
    $student_ids = $_POST['student_id'];
    $positions = $_POST['position'];

    for ($i = 0; $i < count($committee_ids); $i++) {
        $stmt = $conn->prepare("UPDATE committee SET student_id = ?, position = ? WHERE committee_id = ?");
        $stmt->bind_param("isi", $student_ids[$i], $positions[$i], $committee_ids[$i]);
        $stmt->execute();
        $stmt->close();
    }

    $_SESSION['success_message'] = "✅ Committee updated successfully.";
    header("Location: QRCodeEventPage.php");
    exit();
} else {
    $_SESSION['error_message'] = "❌ No data received.";
    header("Location: QRCodeEventPage.php");
    exit();
}
