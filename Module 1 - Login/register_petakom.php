<?php
session_start();
$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("Connection failed: " . mysqli_connect_error());

// ✅ Upload message (optional)
$upload_msg = '';
if (isset($_SESSION['upload_msg'])) {
    $upload_msg = $_SESSION['upload_msg'];
    unset($_SESSION['upload_msg']);
}

// ✅ Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    die("Error: student not logged in.");
}
$student_id = mysqli_real_escape_string($link, $_SESSION['student_id']);

// ✅ Get student & membership info
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
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .card {
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            padding: 40px 30px;
            width: 400px;
        }
        h2 {
            text-align: center;
            margin-bottom: 24px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 12px 0;
        }
        strong {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            color: #333;
        }
        input[type="file"],
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        input[type="submit"] {
            background-color: #b9f117;
            border: none;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #a2d40e;
        }
        .status-message {
            margin-top: 10px;
            font-weight: bold;
        }
        .back-link {
            display: block;
            margin-top: 24px;
            text-align: center;
            font-size: 14px;
            color: #555;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        img {
            display: block;
            margin-top: 12px;
            max-width: 100%;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div class="card">
    <h2>Student Info</h2>
    <table>
        <tr>
            <td><strong>Full Name</strong><?php echo htmlspecialchars($fullname); ?></td>
        </tr>
        <tr>
            <td><strong>Student Card</strong>

            <?php if (!empty($student_card) || in_array($status, ['Approved', 'Rejected', 'Pending'])): ?>
                <?php if (!empty($student_card)): ?>
                    <p>✅ Uploaded Image:</p>
                    <img src="../uploads/<?php echo htmlspecialchars($student_card); ?>">
                <?php endif; ?>

                <?php if ($status === 'Approved'): ?>
                    <p class="status-message" style="color:green;">🎉 Congrats, your membership is approved by admin.</p>
                <?php elseif ($status === 'Rejected'): ?>
                    <p class="status-message" style="color:red;">❌ Sorry, your membership is not approved by admin.</p>
                <?php elseif ($status === 'Pending'): ?>
                    <p class="status-message" style="color:orange;"><strong>Status:</strong> Application Submitted (Pending Admin Approval)</p>
                <?php endif; ?>

            <?php else: ?>
                <form action="../Module 1 - Login/upload_card.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $student_id; ?>">
                    <input type="file" name="fileToUpload" accept="image/*" required>
                    <input type="submit" value="Upload Student Card">
                </form>

                <?php if ($upload_msg): ?>
                    <p class="status-message" style="color:<?= strpos($upload_msg, '✅') !== false ? 'green' : 'red' ?>;">
                        <?= $upload_msg ?>
                    </p>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (!empty($student_card) && $status === ''): ?>
                <form action="../Module 1 - Login/delete_card.php" method="POST" onsubmit="return confirm('Delete uploaded student card?');">
                    <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                    <input type="submit" value="🗑 Delete Image">
                </form>
                <form action="../Module 1 - Login/apply_membership.php" method="POST" onsubmit="return confirm('Send to administrator for approval?');">
                    <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                    <input type="submit" value="✅ Apply for Membership">
                </form>
            <?php endif; ?>

            </td>
        </tr>
    </table>
    <a class="back-link" href="../Module 1 - Login/student_page.php">Back to Student Page</a>
</div>
</body>
</html>
