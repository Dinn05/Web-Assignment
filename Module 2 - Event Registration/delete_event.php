<?php
$conn = new mysqli("localhost", "root", "", "mypetakom");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']);
    $stmt = $conn->prepare("DELETE FROM event WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        // Redirect with success message
        header("Location: QRCodeEventPage.php?message=deleted");
        exit();
    } else {
        echo "Error deleting event.";
    }
} else {
    echo "No event specified.";
}
?>
