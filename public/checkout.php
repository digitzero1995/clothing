<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include '../config/db_sqlite.php';

$errorMsg = '';
$successMsg = '';
$order_id = null;

$address = '';
$contact = '';
$payment_method = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $contact = isset($_POST['contact']) ? trim($_POST['contact']) : '';
    $payment_method = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : '';

    if ($address && $contact && $payment_method && !empty($_SESSION['cart'])) {
        $user_id = $_SESSION['user_id'];

        try {
            // Calculate total amount
            $total_amount = 0;
            foreach ($_SESSION['cart'] as $id => $quantity) {
                $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
                $stmt->execute([$id]);
                if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $total_amount += $row['price'] * $quantity;
                }
            }

            // Insert into orders table
            $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, payment_method, status, shipping_name, shipping_phone, shipping_address) VALUES (?, ?, ?, 'Pending', ?, ?, ?)");
            $stmt->execute([$user_id, $total_amount, $payment_method, $_SESSION['username'], $contact, $address]);
            
            // Get the last inserted order ID
            $order_id = $conn->lastInsertId();

            // Clear cart
            unset($_SESSION['cart']);
            $successMsg = "Thank you for your order! Your order #" . $order_id . " has been placed with Cash on Delivery.";
        } catch (Exception $e) {
            $errorMsg = "Error placing order: " . $e->getMessage();
        }
    } else {
        $errorMsg = "Please fill in all fields, select payment method, and ensure your cart is not empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - TrendAura</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-100">

<?php include '../includes/header.php'; ?>

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
        <p>Redirecting to order details...</p>
        <script>
            setTimeout(function() {
                window.location.href = "order_success.php?id=<?php echo $order_id; ?>";
            }, 2000);
        </script>
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

</body>
</html>
<?php include '../includes/foooter.php'; ?>