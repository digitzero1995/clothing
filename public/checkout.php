<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/db.php';

$errorMsg = '';
$successMsg = '';

$address = '';
$contact = '';
$payment_method = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $contact = isset($_POST['contact']) ? trim($_POST['contact']) : '';
    $payment_method = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : '';

    if ($address && $contact && $payment_method && !empty($_SESSION['cart'])) {
        $user_id = $_SESSION['user_id'];

        // Calculate total amount
        $total_amount = 0;
        foreach ($_SESSION['cart'] as $id => $quantity) {
            $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $total_amount += $row['price'] * $quantity;
            }
            $stmt->close();
        }

        // Insert into orders table (payment_method added)
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status, address, contact, payment_method) VALUES (?, ?, 'pending', ?, ?, ?)");
        $stmt->bind_param("idsss", $user_id, $total_amount, $address, $contact, $payment_method);
        $stmt->execute();
        $order_id = $stmt->insert_id;
        $stmt->close();

        // Insert each product into order_items
        $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, subtotal) VALUES (?, ?, ?, ?)");
        foreach ($_SESSION['cart'] as $id => $quantity) {
            $stmt_price = $conn->prepare("SELECT price FROM products WHERE id = ?");
            $stmt_price->bind_param("i", $id);
            $stmt_price->execute();
            $result_price = $stmt_price->get_result();
            $row_price = $result_price->fetch_assoc();
            $price = $row_price['price'];
            $stmt_price->close();

            $subtotal = $price * $quantity;

            $stmt_items->bind_param("iiid", $order_id, $id, $quantity, $subtotal);
            $stmt_items->execute();
        }
        $stmt_items->close();

        // Clear cart
        unset($_SESSION['cart']);
        $successMsg = "Thank you, " . htmlspecialchars($_SESSION['user_name']) . ", your order has been placed with Cash on Delivery!";
    } else {
        $errorMsg = "Please fill in all fields, select payment method, and ensure your cart is not empty.";
    }
}
?>

<?php include 'header.php'; ?>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);
    margin: 0;
    padding: 40px 20px;
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 80vh;
}
.checkout-container {
    background: #fff;
    padding: 40px 50px;
    max-width: 450px;
    border-radius: 15px;
    box-shadow: 0 15px 30px rgba(102, 166, 255, 0.3);
    text-align: center;
    animation: fadeInScale 0.8s ease forwards;
}
.checkout-container h1 {
    font-size: 2rem;
    margin-bottom: 20px;
}
.checkout-container input, 
.checkout-container textarea, 
.checkout-container select {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 8px;
}
.checkout-container button {
    background: #66a6ff;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    margin-top: 20px;
}
.checkout-container button:hover {
    background: #89f7fe;
}
.success-msg {
    color: green;
    font-weight: bold;
    margin-bottom: 15px;
}
.error-msg {
    color: red;
    font-weight: bold;
    margin-bottom: 15px;
}
@keyframes fadeInScale {
    0% { opacity: 0; transform: scale(0.85); }
    100% { opacity: 1; transform: scale(1); }
}
</style>

<div class="checkout-container">
    <h1>Checkout</h1>

    <?php if ($successMsg): ?>
        <p class="success-msg"><?php echo $successMsg; ?></p>
        <a href="index.php">Back to Store</a>
    <?php else: ?>
        <?php if ($errorMsg): ?>
            <p class="error-msg"><?php echo $errorMsg; ?></p>
        <?php endif; ?>
        <form method="post">
            <textarea name="address" placeholder="Enter your address" required><?php echo htmlspecialchars($address); ?></textarea>
            <input type="text" name="contact" placeholder="Enter your contact number" value="<?php echo htmlspecialchars($contact); ?>" required>
            <label for="payment_method" style="display:block; margin-top:15px; font-weight:bold;">Payment Method:</label>
            <select name="payment_method" id="payment_method" required>
                <option value="cod" <?php if(isset($_POST['payment_method']) && $_POST['payment_method']=='cod') echo 'selected'; ?>>Cash on Delivery</option>
            </select>
            <button type="submit">Place Order</button>
        </form>
    <?php endif; ?>
</div>

<?php include '../includes/foooter.php'; ?>