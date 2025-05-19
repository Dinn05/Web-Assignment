<?php
session_start();
$link = mysqli_connect("localhost", "root", "", "login") or die("Connection failed: " . mysqli_connect_error());

// ✅ Show upload messages
$upload_msg_front = '';
$upload_msg_back = '';



if (isset($_SESSION['upload_msg_front'])) {
    $upload_msg_front = $_SESSION['upload_msg_front'];
    unset($_SESSION['upload_msg_front']);
}

if (isset($_SESSION['upload_msg_back'])) {
    $upload_msg_back = $_SESSION['upload_msg_back'];
    unset($_SESSION['upload_msg_back']);
}

// ✅ Get student ID
if (!isset($_POST['user_id'])) {
    die("Error: student_id not provided.");
}
$student_id = mysqli_real_escape_string($link, $_POST['user_id']);

// ✅ Fetch user data
$query = "SELECT * FROM users WHERE user_id = '$student_id'";
$result = mysqli_query($link, $query) or die("Query failed: " . mysqli_error($link));

if ($row = mysqli_fetch_assoc($result)) {
    $fullname = $row['fullname'];
    $student_card_front = $row['student_card_front'];
    $student_card_back = $row['student_card_back'];
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

        <!-- Front Image Upload -->
        <tr>
            <td><strong>Student Card (Front)</strong></td>
            <td>
                <form action="uploadfront.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $student_id; ?>">
                    <input type="file" name="fileFrontToUpload" accept="image/*" required><br><br>
                    <input type="submit" value="Upload Front Image">
                </form>

                <?php if ($upload_msg_front): ?>
                    <p style="color:<?= strpos($upload_msg_front, '✅') !== false ? 'green' : 'red' ?>; font-weight: bold;">
                        <?= $upload_msg_front ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($student_card_front)): ?>
                    <p>✅ Already uploaded</p>
                    <img src="uploads/<?php echo htmlspecialchars($student_card_front); ?>" width="200"><br><br>
                    <form action="delete_image.php" method="POST" onsubmit="return confirm('Delete front image?');">
                        <input type="hidden" name="id" value="<?php echo $student_id; ?>">
                        <input type="submit" value="🗑 Delete Front Image">
                    </form>
                <?php endif; ?>
            </td>
        </tr>

        <!-- Back Image Upload -->
        <tr>
            <td><strong>Student Card (Back)</strong></td>
            <td>
                <form action="uploadback.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $student_id; ?>">
                    <input type="file" name="fileBackToUpload" accept="image/*" required><br><br>
                    <input type="submit" value="Upload Back Image">
                </form>

                <?php if ($upload_msg_back): ?>
                    <p style="color:<?= strpos($upload_msg_back, '✅') !== false ? 'green' : 'red' ?>; font-weight: bold;">
                        <?= $upload_msg_back ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($student_card_back)): ?>
                    <p>✅ Already uploaded</p>
                    <img src="uploads/<?php echo htmlspecialchars($student_card_back); ?>" width="200"><br><br>
                    <form action="delete_image_back.php" method="POST" onsubmit="return confirm('Delete back image?');">
                        <input type="hidden" name="id" value="<?php echo $student_id; ?>">
                        <input type="submit" value="🗑 Delete Back Image">
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <p><a href="student_page.php">← Back to Student Page</a></p>
</body>
</html>
