<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../config/db_sqlite.php';

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$order = null;
$error = '';

if ($order_id > 0) {
    try {
        $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
        $stmt->execute([$order_id, $_SESSION['user_id']]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $error = "Error fetching order: " . $e->getMessage();
    }
}

if (!$order) {
    $error = "Order not found or access denied.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - TrendAura</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-100">

<!-- TOP BAR WITH LINKS -->
<div class="bg-black text-white text-sm px-6 py-2 flex justify-between items-center">
    <div class="space-x-6 flex">
        <a href="gift-cards.php" class="hover:text-yellow-400">GREENCARD</a>
        <a href="gift-cards.php" class="hover:text-yellow-400">GIFT CARD</a>
        <a href="store-locator.php" class="hover:text-yellow-400">STORE LOCATOR</a>
        <a href="track-order.php" class="hover:text-yellow-400">TRACK ORDER</a>
        <a href="contact.php" class="hover:text-yellow-400">CONTACT</a>
    </div>
    <div>
        <a href="store-mode.php" class="hover:text-yellow-400">STORE MODE</a>
    </div>
</div>

<!-- NAVBAR -->
<nav class="bg-white shadow-md px-6 py-4 flex justify-between items-center">
    <a href="index.php" class="text-3xl font-bold text-teal-500">TrendAura</a>
    <div class="flex items-center space-x-4">
        <a href="index.php"><i class="fa fa-home text-teal-500"></i></a>
        <a href="wishlist.php"><i class="fa fa-heart text-red-500"></i></a>
        <a href="profile.php"><i class="fa fa-user"></i></a>
        <a href="auth/logout.php"><i class="fa fa-sign-out"></i></a>
    </div>
</nav>

<!-- MAIN CONTENT -->
<div class="min-h-screen flex items-center justify-center px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-600px w-full">
        
        <?php if ($error): ?>
            <div class="text-center">
                <div class="text-red-500 text-5xl mb-4"><i class="fa fa-times-circle"></i></div>
                <h1 class="text-3xl font-bold text-gray-800 mb-4">Order Error</h1>
                <p class="text-gray-600 mb-6"><?php echo htmlspecialchars($error); ?></p>
                <a href="index.php" class="bg-teal-500 text-white px-6 py-2 rounded hover:bg-teal-600">← Back to Store</a>
            </div>
        <?php else: ?>
            <div class="text-center">
                <div class="text-green-500 text-5xl mb-4"><i class="fa fa-check-circle"></i></div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Order Placed Successfully! ✓</h1>
                <p class="text-gray-600 mb-6">Thank you for your purchase!</p>
                
                <div class="bg-gray-50 p-6 rounded-lg mb-6 text-left">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Order Details</h2>
                    
                    <div class="space-y-3 border-b pb-4 mb-4">
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-700">Order ID:</span>
                            <span class="text-gray-600">#<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-700">Amount:</span>
                            <span class="text-gray-600">₹<?php echo number_format($order['total_price'], 2); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-700">Payment Method:</span>
                            <span class="text-gray-600"><?php echo ucfirst($order['payment_method']); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-700">Status:</span>
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded text-sm"><?php echo $order['status']; ?></span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <h3 class="font-semibold text-gray-700 mb-2">Shipping Details</h3>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($order['shipping_name']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['shipping_phone']); ?></p>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($order['shipping_address']); ?></p>
                    </div>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 text-left">
                    <p class="text-gray-700"><strong>Next Steps:</strong></p>
                    <ul class="list-disc list-inside mt-2 text-gray-600">
                        <li>Our team will process your order within 24 hours</li>
                        <li>You will receive a confirmation email shortly</li>
                        <li>Payment will be collected on delivery</li>
                        <li>Track your order from your account</li>
                    </ul>
                </div>

                <div class="flex gap-4">
                    <a href="index.php" class="flex-1 bg-teal-500 text-white px-6 py-2 rounded hover:bg-teal-600 font-semibold">← Continue Shopping</a>
                    <a href="my_oder.php" class="flex-1 bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 font-semibold">View My Orders</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- FOOTER -->
<footer class="bg-black text-white text-center p-4 mt-12">
    © 2026 TrendAura - Your Trusted Fashion Partner
</footer>

</body>
</html>
