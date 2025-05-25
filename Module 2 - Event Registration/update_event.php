<?php
// update_event.php

$conn = new mysqli("localhost", "root", "", "mypetakom");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = intval($_POST['event_id']);
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];

    $stmt = $conn->prepare("UPDATE event SET title = ?, description = ?, event_date = ? WHERE event_id = ?");
    $stmt->bind_param("sssi", $title, $description, $event_date, $event_id);

    if ($stmt->execute()) {
        echo "<script>alert('Event updated successfully.'); window.location.href='QRCodeEventPage.php';</script>";
    } else {
        echo "Error updating event: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
