<?php
session_start();
$conn = new mysqli("localhost", "root", "", "mypetakom");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'] ?? 'Guest';

if (!isset($_GET['event_id'])) {
    die("No event ID provided.");
}

$event_id = intval($_GET['event_id']);

// Get committee members assigned to this event
$stmt = $conn->prepare("SELECT committee_id, student_id, position FROM committee WHERE event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No committee members found for this event.");
}

$committees = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Committee</title>
    <link rel="stylesheet" href="Style/QR.css">
    <style>
        .container {
            padding: 50px;
            max-width: 700px;
            margin: auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; }
        form input {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
        }
        form button {
            padding: 10px 20px;
            background-color: green;
            color: white;
            border: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .member-box {
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Committee Members</h2>

    <form action="update_committe.php" method="post">
        <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">

        <?php foreach ($committees as $index => $committe): ?>
            <div class="member-box">
                <input type="hidden" name="committee_id[]" value="<?php echo $committe['committee_id']; ?>">

                <label>Student ID:</label>
                <input type="text" name="student_id[]" value="<?php echo htmlspecialchars($committe['student_id']); ?>" required>

                <label>Position:</label>
                <input type="text" name="position[]" value="<?php echo htmlspecialchars($committe['position']); ?>" required>
            </div>
        <?php endforeach; ?>

        <button type="submit">Update Committee</button>
    </form>
</div>

</body>
</html>
