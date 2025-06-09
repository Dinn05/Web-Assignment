<?php
session_start();

$conn = new mysqli("localhost", "root", "", "mypetakom");

if ($conn->connect_error) {
    die("Sambungan ke DB gagal: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    // Ambil data dari borang
    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $event_date = $_POST['event_date'];
    //$status = $_POST['status'];
    $event_advisor_id=$_POST['event_advisor_id'];

    // Fail surat kelulusan
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) mkdir($upload_dir);

    $file_name = $_FILES['approval_letter']['name'];
    $file_tmp = $_FILES['approval_letter']['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed = ['pdf', 'jpg', 'jpeg', 'png'];

    if (!in_array($file_ext, $allowed)) {
        die("Jenis fail tidak dibenarkan.");
    }

    $new_file_name = uniqid() . "_" . $file_name;
    $file_path = $upload_dir . $new_file_name;

    if (!move_uploaded_file($file_tmp, $file_path)) {
        die("Gagal muat naik fail.");
    }

    // Masukkan ke DB
    $stmt = $conn->prepare("INSERT INTO EVENT (title, description, location, event_date, status, approval_letter, event_advisor_id) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $title, $description, $location, $event_date, $status, $file_path, $event_advisor_id);

    if ($stmt->execute()) {
    $_SESSION['success_message'] = "Aktiviti berjaya didaftarkan!";
    header("Location: EventRegistrationForm.php");
    exit(); // WAJIB hentikan skrip selepas redirect
} else {
    echo "<p>Ralat semasa mendaftar: " . $stmt->error . "</p>";
}


    $stmt->close();
    $conn->close();
}
?>
