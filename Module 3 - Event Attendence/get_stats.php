<?php
header('Content-Type: application/json');
require_once '../../config/db.php';

$eventId = $_GET['event_id'] ?? null;
$dateFrom = $_GET['date_from'] ?? null;
$dateTo = $_GET['date_to'] ?? null;

// Build query conditions
$conditions = [];
$params = [];
$types = '';

if ($eventId) {
    $conditions[] = "e.event_id = ?";
    $params[] = $eventId;
    $types .= 'i';
}

if ($dateFrom && $dateTo) {
    $conditions[] = "DATE(e.event_date) BETWEEN ? AND ?";
    $params[] = $dateFrom;
    $params[] = $dateTo;
    $types .= 'ss';
}

$whereClause = $conditions ? "WHERE " . implode(" AND ", $conditions) : "";

// Get total events
$query = "SELECT COUNT(DISTINCT e.event_id) AS total_events FROM events e $whereClause";
$stmt = $conn->prepare($query);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$totalEvents = $stmt->get_result()->fetch_assoc()['total_events'];

// Get total attendance
$query = "SELECT COUNT(ar.record_id) AS total_attendance 
          FROM attendance_records ar
          JOIN attendance_slots s ON ar.slot_id = s.slot_id
          JOIN events e ON s.event_id = e.event_id
          $whereClause";
$stmt = $conn->prepare($query);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$totalAttendance = $stmt->get_result()->fetch_assoc()['total_attendance'];

// Get average attendance rate
$query = "SELECT AVG(attendance_rate) AS avg_rate FROM (
            SELECT e.event_id, 
                   COUNT(ar.record_id) * 100.0 / 
                   (SELECT COUNT(*) FROM users WHERE role = 'student') AS attendance_rate
            FROM events e
            LEFT JOIN attendance_slots s ON e.event_id = s.event_id
            LEFT JOIN attendance_records ar ON s.slot_id = ar.slot_id
            $whereClause
            GROUP BY e.event_id
          ) AS rates";
$stmt = $conn->prepare($query);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$avgRate = round($stmt->get_result()->fetch_assoc()['avg_rate'], 1);

echo json_encode([
    'total_events' => $totalEvents,
    'total_attendance' => $totalAttendance,
    'avg_rate' => $avgRate
]);
?>