<?php
session_start();

if (!isset($_SESSION['Login']) || $_SESSION['Login'] !== "YES" || $_SESSION['role'] !== 'administrator') {
    echo "<h1>Access Denied</h1><p>You must <a href='../Module 1 - Login/login.php'>login</a> as an administrator.</p>";
    exit();
}

$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("Connection failed: " . mysqli_connect_error());
$result = mysqli_query($link, "SELECT * FROM staff INNER JOIN login ON staff.login_id = login.login_id WHERE login.role = 'event_advisor'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Event Advisor Registered</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background: linear-gradient(to right, #e0eafc, #cfdef3);
        padding: 40px;
        font-family: 'Segoe UI', sans-serif;
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #333;
        font-weight: bold;
    }

    .btn-top {
        display: flex;
        justify-content: space-between;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .btn {
        border-radius: 25px;
        font-weight: 500;
        padding: 10px 20px;
    }

    .table-container {
        background: #ffffff;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    table th { 
        background-color: #cfe2ff; 
        color: #000; 
        font-weight: bold; 
        text-align: center; 
        white-space: nowrap; 
    }


    table td {
        text-align: center;
        vertical-align: middle;
    }

    img.profile-pic {
        width: 25%;
        height: 25%;
        object-fit: cover;
        border-radius: 5px;
        border: 3px solid #007bff;
        margin: auto;
    }

    .btn-sm {
        font-size: 14px;
        padding: 6px 12px;
        border-radius: 20px;
    }

    .btn-warning {
        background-color: #ffc107;
        color: #000;
        border: none;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
        border: none;
    }

    @media (max-width: 768px) {
        .btn-top {
            flex-direction: column;
            align-items: center;
        }
    }
</style>


</head>
<body>
    <h2>Registered Event Advisors</h2>
    <div class="btn-top">
        <a href="../Module 1 - Login/admin_page.php" class="btn btn-secondary">Return to Dashboard</a>
        <a href="../Module 1 - Login/admin_add_advisor.php" class="btn btn-success">+ Add New Advisor</a>
    </div>

    <div class="table-container">
    <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-primary">
            <tr>
                <th>Profile Picture</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td>
                        <?php if (!empty($row['profile_picture'])): ?>
                            <img src="<?= $row['profile_picture'] ?>" alt="Profile" class="profile-pic">
                        <?php else: ?>
                            <span>No Image</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['fullname']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone_num']) ?></td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="../Module 1 - Login/admin_edit_advisor.php?staff_id=<?= $row['staff_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="../Module 1 - Login/admin_delete_advisor.php?staff_id=<?= $row['staff_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this advisor?');">Delete</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>


</body>
</html>
