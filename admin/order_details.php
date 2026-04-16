<?php
session_start();
include '../db.php'; // correct relative path from /admin

// Only admins can access
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Get order id safely
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($order_id <= 0) {
    die("Invalid Order ID");
}

/* -------------------------
   Handle Delete Request
--------------------------*/
if (isset($_POST['delete_order'])) {
    // Delete order_items first
    $delItems = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
    $delItems->bind_param("i", $order_id);
    $delItems->execute();

    // Delete order
    $delOrder = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $delOrder->bind_param("i", $order_id);
    $delOrder->execute();

    header("Location: orders.php?msg=Order+Deleted");
    exit();
}

/* -------------------------
   Fetch order + user info
--------------------------*/
$sqlOrder = "
    SELECT 
        o.id            AS order_id,
        o.address,
        o.contact,
        o.created_at,
        u.name          AS customer_name,
        u.email,
        u.phone
    FROM orders o
    JOIN users u ON u.id = o.user_id
    WHERE o.id = ?
";
$stmtOrder = $conn->prepare($sqlOrder);
if (!$stmtOrder) {
    die("SQL error (order): " . $conn->error);
}
$stmtOrder->bind_param('i', $order_id);
$stmtOrder->execute();
$orderRes = $stmtOrder->get_result();
$order = $orderRes->fetch_assoc();

if (!$order) {
    die("Order not found.");
}

/* -------------------------
   Fetch order items
--------------------------*/
$sqlItems = "
    SELECT 
        p.name,
        p.price,
        p.image,
        oi.quantity
    FROM order_items oi
    JOIN products p ON p.id = oi.product_id
    WHERE oi.order_id = ?
";
$stmtItems = $conn->prepare($sqlItems);
if (!$stmtItems) {
    die("SQL error (items): " . $conn->error);
}
$stmtItems->bind_param('i', $order_id);
$stmtItems->execute();
$itemsResult = $stmtItems->get_result();

// compute total
$grandTotal = 0;
$items = [];
while ($row = $itemsResult->fetch_assoc()) {
    $row['subtotal'] = (float)$row['price'] * (int)$row['quantity'];
    $grandTotal += $row['subtotal'];
    $items[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Details #<?php echo htmlspecialchars($order['order_id']); ?></title>

<style>
body {
    background: linear-gradient(135deg, #111, #333);
    color: #fff;
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
    overflow-x: hidden;
}
.container {
    width: 90%;
    margin: 40px auto;
    animation: slideUpFade 0.8s ease forwards;
    opacity: 0;
}
.cart-box {
    background: #1e1e1e;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.6);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.cart-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.8);
}
.header-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
    margin-bottom: 20px;
}
.header-block {
    background: #222;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 18px rgba(0,0,0,0.3);
    transform: translateY(30px);
    opacity: 0;
    animation: fadeInUp 0.5s forwards;
}
.header-block:nth-child(1) { animation-delay: 0.2s; }
.header-block:nth-child(2) { animation-delay: 0.4s; }
.header-block h4 { 
    margin: 0 0 10px; 
    color: #00ffd5; 
    text-shadow: 0 0 5px #00ffd5;
}
.header-block p { margin: 4px 0; color: #ddd; }
.table {
    width: 100%;
    background: #222;
    border-radius: 12px;
    overflow: hidden;
    border-collapse: collapse;
    box-shadow: 0 6px 20px rgba(0,0,0,0.4);
    animation: fadeIn 0.8s ease 0.5s forwards;
    opacity: 0;
}
.table th, .table td {
    padding: 14px 16px;
    text-align: center;
    border-bottom: 1px solid #333;
}
.table thead th {
    background: #0077ff;
    color: #fff;
    font-size: 16px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.table tbody tr:hover {
    background: #2a2a2a;
    transform: scale(1.02);
    transition: transform 0.2s ease;
}
.product-img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid #444;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.product-img:hover {
    transform: scale(1.1) rotate(3deg);
    box-shadow: 0 0 15px #0077ff;
}
.btn {
    display: inline-block;
    border-radius: 25px;
    transition: all 0.3s ease, box-shadow 0.3s ease;
    font-weight: bold;
    text-decoration: none;
    padding: 12px 24px;
    cursor: pointer;
}
.btn-primary { 
    background: #0077ff; color: #fff; 
    box-shadow: 0 0 8px #0077ff;
}
.btn-primary:hover { 
    background: #005fcc; 
    transform: scale(1.08);
    box-shadow: 0 0 20px #0077ff;
}
.btn-secondary { 
    background: #888; color: #fff; 
    box-shadow: 0 0 8px #888;
}
.btn-secondary:hover { 
    background: #666; 
    transform: scale(1.08);
    box-shadow: 0 0 18px #888;
}
.btn-danger { 
    background: #e74c3c; color: #fff; 
    box-shadow: 0 0 8px #e74c3c;
}
.btn-danger:hover { 
    background: #c0392b; 
    transform: scale(1.08);
    box-shadow: 0 0 18px #e74c3c;
}
@keyframes fadeIn { to { opacity: 1; } }
@keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
@keyframes slideUpFade { to { opacity: 1; transform: translateY(0); } }
@media (max-width: 768px) {
    .header-row { grid-template-columns: 1fr; }
}
</style>

<script>
function confirmDelete() {
    return confirm("Are you sure you want to delete this order?");
}

window.addEventListener('DOMContentLoaded', () => {
    const rows = document.querySelectorAll('.table tbody tr');
    rows.forEach((row, index) => {
        row.style.opacity = 0;
        row.style.transform = 'translateX(-20px)';
        setTimeout(() => {
            row.style.transition = 'all 0.5s ease';
            row.style.opacity = 1;
            row.style.transform = 'translateX(0)';
        }, 100 * index);
    });
});
</script>
</head>
<body>

<div class="container">
    <div class="cart-box">
        <h2 style="text-align:center;margin-top:0;margin-bottom:16px;">
            🧾 Order #<?php echo htmlspecialchars($order['order_id']); ?>
        </h2>

        <div class="header-row">
            <div class="header-block">
                <h4>Customer</h4>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone'] ?? ''); ?></p>
            </div>
            <div class="header-block">
                <h4>Shipping</h4>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                <p><strong>Contact:</strong> <?php echo htmlspecialchars($order['contact']); ?></p>
                <p><strong>Placed On:</strong>
                    <?php
                        $ts = strtotime($order['created_at']);
                        echo $ts ? date('d M Y, H:i', $ts) : htmlspecialchars($order['created_at']);
                    ?>
                </p>
            </div>
        </div>

        <h3 style="margin:8px 0 12px;">Items</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Price (₹)</th>
                    <th>Quantity</th>
                    <th>Subtotal (₹)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $it): ?>
                        <tr>
                            <td>
                                <?php if (!empty($it['image'])): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($it['image']); ?>" 
                                         alt="Product" class="product-img">
                                <?php else: ?>
                                    <span style="color:#aaa;">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($it['name']); ?></td>
                            <td><?php echo number_format((float)$it['price'], 2); ?></td>
                            <td><?php echo (int)$it['quantity']; ?></td>
                            <td><?php echo number_format((float)$it['subtotal'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4" style="text-align:right;"><strong>Total:</strong></td>
                        <td><strong>₹<?php echo number_format((float)$grandTotal, 2); ?></strong></td>
                    </tr>
                <?php else: ?>
                    <tr><td colspan="5">No items found for this order.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div style="text-align:center; margin-top:18px;">
            <a href="orders.php" class="btn btn-secondary">Back to Orders</a>
            <form method="post" style="display:inline;" onsubmit="return confirmDelete();">
                <button type="submit" name="delete_order" class="btn btn-danger">Delete Order</button>
            </form>
        </div>
    </div>
</div>
<a href="home.php" style="display:block;text-align:center;margin:20px;color:#00ffd5;text-decoration:none;">Back to Dashboard</a>
</body>
</html>