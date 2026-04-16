<?php
session_start();
include '../db.php';

// Redirect if not logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle deleting coupon
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM coupons WHERE id=$id");
    echo "<script>alert('Coupon deleted successfully'); window.location.href='coupons.php';</script>";
    exit();
}

// Handle adding new coupon
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $discount_type = mysqli_real_escape_string($conn, $_POST['discount_type']);
    $discount_value = floatval($_POST['discount_value']);
    $expires_at = !empty($_POST['expires_at']) ? $_POST['expires_at'] : NULL;

    if (!empty($code) && $discount_value > 0) {
        $stmt = $conn->prepare("INSERT INTO coupons (code, discount_type, discount_value, expires_at) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $code, $discount_type, $discount_value, $expires_at);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Coupon added successfully'); window.location.href='coupons.php';</script>";
    }
}

// Fetch all coupons
$coupons = mysqli_query($conn, "SELECT * FROM coupons ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Coupons</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            color: #fff;
            animation: fadeIn 1.2s ease-in-out;
        }

        h2 {
            text-align: center;
            font-size: 32px;
            color: #00ffcc;
            text-shadow: 0 0 10px #00ffcc, 0 0 20px #00ffcc;
            animation: glow 2s infinite alternate;
        }

        @keyframes glow {
            from { text-shadow: 0 0 10px #00ffcc, 0 0 20px #00ffcc; }
            to { text-shadow: 0 0 20px #00ffcc, 0 0 40px #00ffaa; }
        }

        form {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 0px 15px rgba(0,255,200,0.4);
            max-width: 600px;
            margin: 20px auto;
            backdrop-filter: blur(8px);
            animation: slideDown 0.8s ease-in-out;
        }

        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        form label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #fff;
        }

        form input, form select, form button {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border: none;
            border-radius: 6px;
            transition: all 0.3s;
        }

        form input, form select {
            background: rgba(255,255,255,0.2);
            color: #fff;
        }

        form input:focus, form select:focus {
            outline: none;
            box-shadow: 0 0 10px #00ffcc;
        }

        form button {
            background: #00ffcc;
            color: #000;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
            transition: 0.3s;
        }

        form button:hover {
            background: #00ffaa;
            box-shadow: 0 0 20px #00ffcc;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0px 0px 20px rgba(0,255,200,0.4);
            backdrop-filter: blur(8px);
            animation: fadeIn 1s ease-in-out;
        }

        table th, table td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            color: #fff;
        }

        table th {
            background: #00ffcc;
            color: #000;
        }

        table tr:hover {
            background: rgba(0,255,200,0.2);
            transition: 0.3s;
        }

        .delete-btn {
            background: #ff0033;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 0 10px #ff0033;
            transition: 0.3s;
        }

        .delete-btn:hover {
            background: #ff3366;
            box-shadow: 0 0 20px #ff0033;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            font-size: 18px;
            text-decoration: none;
            color: #00ffcc;
            font-weight: bold;
            transition: 0.3s;
        }

        .back-link:hover {
            color: #00ffaa;
            text-shadow: 0 0 10px #00ffcc;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <h2>✨ Manage Coupons ✨</h2>

    <form method="POST">
        <label>Coupon Code:</label>
        <input type="text" name="code" required>
        
        <label>Discount Type:</label>
        <select name="discount_type" required>
            <option value="percent">Percentage (%)</option>
            <option value="fixed">Fixed Amount</option>
        </select>
        
        <label>Discount Value:</label>
        <input type="number" step="0.01" name="discount_value" required>
        
        <label>Expiry Date:</label>
        <input type="date" name="expires_at">
        
        <button type="submit">➕ Add Coupon</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Code</th>
            <th>Type</th>
            <th>Value</th>
            <th>Expiry Date</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($coupons)) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['code']) ?></td>
                <td><?= $row['discount_type'] ?></td>
                <td><?= $row['discount_value'] ?></td>
                <td><?= $row['expires_at'] ?? 'No Expiry' ?></td>
                <td>
                    <a href="coupons.php?delete=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this coupon?');">❌ Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <a href="home.php" class="back-link">⬅ Back to Dashboard</a>
</body>
</html>