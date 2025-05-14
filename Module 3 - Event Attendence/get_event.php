<?php
header('Content-Type: application/json');
require_once '../../config/db.php';

$eventId = $_GET['event_id'] ?? 0;

$stmt = $conn->prepare("
    SELECT 
        event_name, 
        DATE_FORMAT(event_date, '%W, %e %M %Y') AS event_date,
        TIME_FORMAT(start_time, '%h:%i %p') AS event_time,
        event_location
    FROM events 
    WHERE event_id = ?
");
$stmt->bind_param("i", $eventId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $event = $result->fetch_assoc();
    echo json_encode([
        'success' => true,
        ...$event
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Event not found'
    ]);
}
?>