<?php
session_start();
include '../db.php';

// Redirect if not logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all orders with status names
$query = "
    SELECT o.id, u.name AS username, o.total_amount, s.name AS status, s.color, o.created_at
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN order_status s ON o.status_id = s.id
    ORDER BY o.created_at DESC
";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Orders</title>
<link rel="stylesheet" href="../style.css">
<style>
body {
    background: linear-gradient(135deg, #141e30, #243b55);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #fff;
    margin: 0;
    padding: 0;
}
.container {
    width: 95%;
    margin: 30px auto;
    background: #1e1e2f;
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.5);
}
h2 {
    color: #00ffd5;
    text-align: center;
    margin-bottom: 20px;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    border-radius: 10px;
    overflow: hidden;
}
th, td {
    padding: 12px 15px;
    text-align: center;
}
th {
    background: #007bff;
    color: #fff;
}
tr {
    background: rgba(255,255,255,0.05);
}
tr:hover {
    background: rgba(0,255,213,0.15);
}
.btn {
    padding: 6px 12px;
    border-radius: 8px;
    color: #000;
    text-decoration: none;
    font-weight: bold;
    margin: 2px;
    display: inline-block;
}
.badge {
    padding: 6px 10px;
    border-radius: 6px;
    color: #fff;
    font-weight: bold;
}
.badge.warning { background: #ffc107; color:#000; }
.badge.info { background: #17a2b8; }
.badge.success { background: #28a745; }
.back-link {
    display: block;
    margin: 20px auto;
    color: #00ffd5;
    text-align: center;
}
</style>
</head>
<body>

<div class="container">
    <h2>📦 Manage Orders</h2>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Placed On</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td>#<?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td>$<?php echo number_format($row['total_amount'], 2); ?></td>
                <td>
                    <span class="badge <?php echo strtolower($row['color']); ?>">
                        <?php echo htmlspecialchars($row['status']); ?>
                    </span>
                </td>
                <td><?php echo date('d M Y, H:i', strtotime($row['created_at'])); ?></td>
                <td>
                    <a href="order_details.php?id=<?php echo $row['id']; ?>" class="btn" style="background:#00ffd5;">View</a>
                    <a href="update_status.php?id=<?php echo $row['id']; ?>&status_id=1" class="btn" style="background:#ffc107;">Pending</a>
                    <a href="update_status.php?id=<?php echo $row['id']; ?>&status_id=2" class="btn" style="background:#17a2b8;color:#fff;">Dispatch</a>
                    <a href="update_status.php?id=<?php echo $row['id']; ?>&status_id=3" class="btn" style="background:#28a745;color:#fff;">Delivered</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<a href="home.php" class="back-link">⬅ Back to Dashboard</a>

</body>
</html>