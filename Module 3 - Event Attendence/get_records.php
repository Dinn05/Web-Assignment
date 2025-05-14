<?php
header('Content-Type: application/json');
require_once '../../config/db.php';

// Similar filtering as previous endpoints
// ...

$query = "SELECT 
            e.event_name,
            ar.student_id,
            ar.check_in_time,
            ar.latitude,
            ar.longitude,
            ar.is_verified
          FROM attendance_records ar
          JOIN attendance_slots s ON ar.slot_id = s.slot_id
          JOIN events e ON s.event_id = e.event_id
          $whereClause
          ORDER BY ar.check_in_time DESC
          LIMIT 100";
$stmt = $conn->prepare($query);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$records = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode($records);
?>