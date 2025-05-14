<?php
header('Content-Type: application/json');
require_once '../../config/db.php';

$eventId = $_GET['event_id'] ?? null;
$dateFrom = $_GET['date_from'] ?? null;
$dateTo = $_GET['date_to'] ?? null;

// Build query conditions (same as get_stats.php)
// ...

// Get event attendance data
$query = "SELECT 
            e.event_id,
            e.event_name,
            COUNT(ar.record_id) AS attendance_count,
            COUNT(ar.record_id) * 100.0 / 
              (SELECT COUNT(*) FROM users WHERE role = 'student') AS attendance_rate
          FROM events e
          LEFT JOIN attendance_slots s ON e.event_id = s.event_id
          LEFT JOIN attendance_records ar ON s.slot_id = ar.slot_id
          $whereClause
          GROUP BY e.event_id, e.event_name
          ORDER BY e.event_date DESC";
$stmt = $conn->prepare($query);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$events = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get participation data
$totalStudents = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetch_row()[0];
$attendedStudents = $conn->query("SELECT COUNT(DISTINCT student_id) FROM attendance_records")->fetch_row()[0];

echo json_encode({
    'events' => $events,
    'participation' => {
        'attended' => $attendedStudents,
        'not_attended' => $totalStudents - $attendedStudents
    }
});
?>