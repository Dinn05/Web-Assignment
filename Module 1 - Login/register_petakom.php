<?php
session_start();
$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("Connection failed: " . mysqli_connect_error());

// ✅ Show upload messages
$upload_msg = '';
if (isset($_SESSION['upload_msg'])) {
    $upload_msg = $_SESSION['upload_msg'];
    unset($_SESSION['upload_msg']);
}

// ✅ Ensure user is logged in
if (!isset($_SESSION['student_id'])) {
    die("Error: student not logged in.");
}
$student_id = mysqli_real_escape_string($link, $_SESSION['student_id']);

// ✅ Fetch student info, card, and status from joined tables
$query = "SELECT s.name, m.student_card, m.status 
          FROM student s 
          LEFT JOIN membership m ON s.student_id = m.student_id 
          WHERE s.student_id = '$student_id'";
$result = mysqli_query($link, $query) or die("Query failed: " . mysqli_error($link));

if ($row = mysqli_fetch_assoc($result)) {
    $fullname = $row['name'];
    $student_card = $row['student_card'] ?? '';
    $status = $row['status'] ?? '';
} else {
    die("No record found for student ID: $student_id");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register PETAKOM</title>
</head>
<body>
    <h2>Student Info</h2>
    <table border="1" cellpadding="10">
        <tr>
            <td><strong>Full Name</strong></td>
            <td><?php echo htmlspecialchars($fullname); ?></td>
        </tr>

        <!-- Student Card Upload -->
        <tr>
            <td><strong>Student Card</strong></td>
            <td>
                <?php if (empty($student_card)): ?>
                    <!-- Upload Form -->
                    <form action="../Module 1 - Login/upload_card.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $student_id; ?>">
                        <input type="file" name="fileToUpload" accept="image/*" required><br><br>
                        <input type="submit" value="Upload Student Card">
                    </form>
                <?php endif; ?>

                <?php if ($upload_msg): ?>
                    <p style="color:<?= strpos($upload_msg, '✅') !== false ? 'green' : 'red' ?>; font-weight: bold;">
                        <?= $upload_msg ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($student_card)): ?>
                <p>✅ Uploaded Image:</p>
                <img src="../uploads/<?php echo htmlspecialchars($student_card); ?>" width="200"><br><br>

                <?php if ($status !== 'Pending'): ?>
                <!-- Delete Button -->
                <form action="../Module 1 - Login/delete_card.php" method="POST" onsubmit="return confirm('Delete uploaded student card?');">
                    <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                    <input type="submit" value="🗑 Delete Image">
                </form><br>

                <!-- Apply to Admin -->
                <form action="../Module 1 - Login/apply_membership.php" method="POST" onsubmit="return confirm('Send to admin for approval?');">
                    <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                    <input type="submit" value="✅ Apply for Membership">
                </form>
                <?php else: ?>
                <p><strong>Status:</strong> Application Submitted (Pending Admin Approval)</p>
                <?php endif; ?>
            <?php endif; ?>
            </td>
        </tr>
    </table>

    <p><a href="../Module 1 - Login/student_page.php">Back to Student Page</a></p>
</body>
</html>
