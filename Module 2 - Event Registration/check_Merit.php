<?php
session_start();

$conn = new mysqli("localhost", "root", "", "mypetakom");

if ($conn->connect_error) {
    die("Sambungan ke DB gagal: " . $conn->connect_error);
}
if (isset($_POST['submit'])) {
    $student_id = $_POST['student_id'];
    $event_id = $_POST['event_id'];
    $role = $_POST['role'];
    $meritscore_id = $_POST['meritscore_id'];
    $semester = $_POST['semester'];
    $admin_id = "NULL"; // approval happens later

    if (!empty($student_id) && !empty($event_id) && !empty($role) && !empty($meritscore_id) && !empty($semester)) {

        $sql = "INSERT INTO merit (student_id, event_id, role, meritscore_id, semester, approved_by_admin_id) 
                VALUES ('$student_id', '$event_id', '$role', '$meritscore_id', '$semester', $admin_id)";

        if (mysqli_query($conn, $sql)) {
            header("Location: MeritApplicationForm.php?message=success");
            exit();
        } else {
            header("Location: MeritApplicationForm.php?message=error");
            exit();
        }
    } else {
        header("Location: MeritApplicationForm.php?message=empty_fields");
        exit();
    }
} else {
    header("Location: MeritApplicationForm.php?message=invalid_access");
    exit();
}
?>
