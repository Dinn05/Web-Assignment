<?php
session_start();

$conn = new mysqli("localhost", "root", "", "mypetakom");

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

$event_result = $conn->query("SELECT event_id, title FROM EVENT");
$student_result = $conn->query("SELECT login_id, student_id FROM STUDENT");

// Proses borang jika dihantar
if (isset($_POST['submit'])) {
    $event_id = $_POST['event_id'];
    $student_id = $_POST['student_id'];
    $role = $_POST['role'];

    $check = $conn->prepare("SELECT * FROM COMMITTEE WHERE event_id = ? AND student_id = ?");
    $check->bind_param("ii", $event_id, $student_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = "❌ Pelajar ini sudah didaftarkan.";
    } else {
        $stmt = $conn->prepare("INSERT INTO COMMITTEE (event_id, student_id, position) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $event_id, $student_id, $role);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "✅ Jawatankuasa berjaya didaftarkan!";
            header("Location: CommitteRegistrationForm.php");
            exit();
        } else {
            $_SESSION['error_message'] = "❌ Ralat: " . $stmt->error;
        }
    }
    
    // Redirect back to form to prevent form resubmission
    header("Location: CommitteRegistrationForm.php");
    exit();
}

// Display messages if they exist
if (isset($_SESSION['success_message'])) {
    $message = "<p style='color:green;'>" . $_SESSION['success_message'] . "</p>";
    unset($_SESSION['success_message']);
} elseif (isset($_SESSION['error_message'])) {
    $message = "<p style='color:red;'>" . $_SESSION['error_message'] . "</p>";
    unset($_SESSION['error_message']);
} else {
    $message = "";
}
?>