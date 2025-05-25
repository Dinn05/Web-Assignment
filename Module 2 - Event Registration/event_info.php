<?php
include 'check_QR.php';  // your DB connection $conn

if (!isset($_GET['event_id'])) {
    die("No event specified.");
}

$event_id = intval($_GET['event_id']);

$stmt = $conn->prepare("SELECT title, description, event_date FROM event WHERE event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$stmt->bind_result($title, $description, $event_date);

if ($stmt->fetch()) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <title><?php echo htmlspecialchars($title); ?> - Event Details</title>
      <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { margin-bottom: 10px; }
        p { line-height: 1.5; }
      </style>
    </head>
    <body>
      <h1><?php echo htmlspecialchars($title); ?></h1>
      <p><strong>Date:</strong> <?php echo htmlspecialchars($event_date); ?></p>
      <p><?php echo nl2br(htmlspecialchars($description)); ?></p>
    </body>
    </html>
    <?php
} else {
    echo "Event not found.";
}

$stmt->close();
$conn->close();
?>
