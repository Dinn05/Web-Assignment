<?php
session_start();
$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("Connection failed");

if (
    !isset($_SESSION['Login']) ||
    $_SESSION['Login'] !== "YES" ||
    $_SESSION['role'] !== 'administrator'
) {
    echo "<h1>Access Denied</h1><p>You must <a href='../Module 1 - Login/login.php'>login</a> as an administrator.</p>";
    exit();
}

// Main membership applications
$query = "SELECT m.*, s.name, s.student_matric, s.email, s.program
          FROM membership m
          JOIN student s ON m.student_id = s.student_id
          ORDER BY m.registered_date DESC";
$result = mysqli_query($link, $query);

// Approved members only
$approvedQuery = "SELECT s.name, s.student_matric, s.email, s.program 
                  FROM membership m
                  JOIN student s ON m.student_id = s.student_id
                  WHERE m.status = 'Approved'
                  ORDER BY m.registered_date DESC";
$approvedResult = mysqli_query($link, $approvedQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Applied Membership</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            padding: 20px;
        }
        .badge-pending {
            background-color: #ffc107;
            color: #000;
        }
        .badge-approved {
            background-color: #28a745;
        }
        .badge-rejected {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="mb-3">✅ View Valid Membership</h2>

    <?php if (mysqli_num_rows($approvedResult) > 0): ?>
        <div class="table-responsive mb-5">
            <table class="table table-bordered table-striped">
                <thead class="table-success">
                    <tr>
                        <th>Student Name</th>
                        <th>Matric Number</th>
                        <th>Email</th>
                        <th>Program</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($approvedResult)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['student_matric']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['program']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">No valid memberships approved yet.</div>
    <?php endif; ?>

    <h2 class="mb-4">📝 Membership Applications</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Student Name</th>
                        <th>Matric Number</th>
                        <th>Student Card</th>
                        <th>Status</th>
                        <th>Registered Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['student_matric']) ?></td>
                            <td>
                                <?php if (!empty($row['student_card']) && file_exists("../uploads/" . $row['student_card'])): ?>
                                <img src="../uploads/<?= htmlspecialchars($row['student_card']) ?>" width="100" style="border-radius: 5px;">
                                <?php else: ?>
                                <span class="text-muted">No image</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php
                                $badgeClass = match($row['status']) {
                                    'Approved' => 'badge-approved',
                                    'Rejected' => 'badge-rejected',
                                    default => 'badge-pending'
                                };
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= $row['status'] ?></span>
                            </td>
                            <td><?= $row['registered_date'] ?></td>
                            <td>
                                <?php if ($row['status'] === 'Pending'): ?>
                                    <form method="POST" action="process_membership.php" class="d-flex gap-2">
                                        <input type="hidden" name="membership_id" value="<?= $row['membership_id'] ?>">
                                        <button type="submit" name="action" value="approve" class="btn btn-success btn-sm" onclick="return confirm('Approve student membership?')">Approve</button>
                                        <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm" onclick="return confirm('Not approve student membership?')">Reject</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted">No action</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No student has applied for membership yet.</div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="admin_page.php" class="btn btn-primary">⬅ Back to Dashboard</a>
    </div>
</div>
</body>
</html>
