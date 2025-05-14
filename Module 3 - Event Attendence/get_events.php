<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$sql = "SELECT event_id, event_name FROM events";
$result = mysqli_query($conn, $sql);

$events = [];
while ($row = mysqli_fetch_assoc($result)) {
    $events[] = $row;
}

echo json_encode($events);
?>
