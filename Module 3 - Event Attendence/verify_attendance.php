<?php
header('Content-Type: application/json');
require_once '../../config/db.php';

$data = json_decode(file_get_contents('php://input'), true);

// 1. Verify student credentials
$stmt = $conn->prepare("
    SELECT user_id, password 
    FROM users 
    WHERE user_id = ? AND role = 'student'
");
$stmt->bind_param("s", $data['student_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user || !password_verify($data['password'], $user['password'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid student ID or password'
    ]);
    exit;
}

// 2. Verify event location (simplified distance calculation)
$stmt = $conn->prepare("
    SELECT latitude, longitude 
    FROM events 
    WHERE event_id = ?
");
$stmt->bind_param("i", $data['event_id']);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();

if (!$event) {
    echo json_encode([
        'success' => false,
        'message' => 'Event not found'
    ]);
    exit;
}

// Simple distance check (within ~100 meters)
$distance = sqrt(
    pow($event['latitude'] - $data['latitude'], 2) + 
    pow($event['longitude'] - $data['longitude'], 2)
);

if ($distance > 0.0015) { // ~100 meter threshold
    echo json_encode([
        'success' => false,
        'message' => 'You must be at the event location to check in'
    ]);
    exit;
}

// 3. Record attendance
$stmt = $conn->prepare("
    INSERT INTO attendance_records (
        slot_id, 
        student_id, 
        latitude, 
        longitude
    ) VALUES (?, ?, ?, ?)
");
$stmt->bind_param(
    "isdd", 
    $data['slot_id'],
    $data['student_id'],
    $data['latitude'],
    $data['longitude']
);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $conn->error
    ]);
}
?>