<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../config/db.php';

$user_id = $_SESSION['user_id'];

// ✅ Fetch user orders with status (fixed column name)
$sql = "SELECT o.id, o.total_amount, o.created_at, s.name AS status_name 
        FROM orders o
        JOIN order_status s ON o.status_id = s.id
        WHERE o.user_id = ?
        ORDER BY o.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>

<?php include 'header.php'; ?>

<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f6f9;
    padding: 20px;
}
.order-history {
    max-width: 900px;
    margin: auto;
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.order-history h1 {
    margin-bottom: 20px;
}
.order-card {
    border: 1px solid #ddd;
    padding: 15px;
    margin-bottom: 25px;
    border-radius: 8px;
    background: #fafafa;
}
.order-card h3 {
    margin: 0 0 10px 0;
}
.status {
    font-weight: bold;
    padding: 5px 10px;
    border-radius: 6px;
}
.status.pending { color: orange; }
.status.dispatched { color: blue; }
.status.delivered { color: green; }
.products-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
.products-table th, .products-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}
.products-table th {
    background: #f0f0f0;
}
</style>

<div class="order-history">
    <h1>My Orders</h1>

    <?php if ($orders->num_rows > 0): ?>
        <?php while ($order = $orders->fetch_assoc()): ?>
            <div class="order-card">
                <h3>Order #<?php echo $order['id']; ?></h3>
                <p><strong>Total:</strong> ₹<?php echo $order['total_amount']; ?></p>
                <p><strong>Date:</strong> <?php echo $order['created_at']; ?></p>
                <p><strong>Status:</strong> 
                    <span class="status <?php echo strtolower($order['status_name']); ?>">
                        <?php echo ucfirst($order['status_name']); ?>
                    </span>
                </p>

                <!-- ✅ Fetch order items -->
                <?php
                $order_id = $order['id'];
                $sql_items = "SELECT oi.product_id, oi.quantity, oi.subtotal, p.name, p.price
                              FROM order_items oi
                              JOIN products p ON oi.product_id = p.id
                              WHERE oi.order_id = ?";
                $stmt_items = $conn->prepare($sql_items);
                $stmt_items->bind_param("i", $order_id);
                $stmt_items->execute();
                $items = $stmt_items->get_result();
                ?>

                <table class="products-table">
                    <tr>
                        <th>Product</th>
                        <th>Price (₹)</th>
                        <th>Quantity</th>
                        <th>Subtotal (₹)</th>
                    </tr>
                    <?php while ($item = $items->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo $item['price']; ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo $item['subtotal']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>You haven’t placed any orders yet.</p>
    <?php endif; ?>
</div>

<?php include '../includes/foooter.php'; ?>