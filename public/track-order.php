<?php 
session_start();
include dirname(__DIR__) . '/clothing.db' ? 'var_dump' : '';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize database
$db_file = dirname(__DIR__) . '/clothing.db';
$conn = null;
try {
    $conn = new PDO('sqlite:' . $db_file);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    $conn = null;
}

$orders = [];
if ($conn) {
    try {
        $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$_SESSION['user_id']]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        // No orders
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order - TrendAura</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-50">

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
    <div class="space-x-6 hidden md:flex">
        <a href="category.php?cat=all" class="hover:text-teal-500">SHOP ALL</a>
        <a href="category.php?cat=women" class="hover:text-teal-500">WOMEN</a>
        <a href="category.php?cat=men" class="hover:text-teal-500">MEN</a>
        <a href="category.php?cat=kids" class="hover:text-teal-500">KIDS</a>
    </div>
    <div class="flex items-center space-x-4">
        <form method="GET" action="search.php" class="flex items-center bg-gray-100 rounded px-3 py-1">
            <input type="text" name="q" placeholder="Search..." class="bg-gray-100 border-none outline-none w-32">
            <button type="submit" class="text-teal-500 ml-2"><i class="fa fa-search"></i></button>
        </form>
        <a href="wishlist.php"><i class="fa fa-heart text-red-500"></i></a>
        <a href="profile.php"><i class="fa fa-user"></i></a>
        <a href="cart.php"><i class="fa fa-shopping-cart"></i></a>
    </div>
</nav>

<!-- MAIN CONTENT -->
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold text-center mb-8">Track Your Orders</h1>

    <?php if (empty($orders)): ?>
        <div class="bg-blue-50 border-l-4 border-blue-500 p-8 rounded text-center">
            <i class="fa fa-box text-blue-500 text-4xl mb-4 block"></i>
            <p class="text-lg text-gray-700 mb-4">You haven't placed any orders yet.</p>
            <a href="category.php?cat=all" class="inline-block bg-teal-500 text-white px-6 py-2 rounded hover:bg-teal-600">
                Start Shopping
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($orders as $order): ?>
                <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Order ID</p>
                            <p class="text-lg font-bold">#<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Amount</p>
                            <p class="text-lg font-bold">₹<?php echo number_format($order['total_price'], 2); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Payment</p>
                            <p class="text-lg font-bold uppercase"><?php echo htmlspecialchars($order['payment_method']); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <p class="text-lg font-bold">
                                <span class="px-3 py-1 rounds text-sm font-semibold
                                    <?php 
                                    if ($order['status'] === 'Pending') echo 'bg-yellow-100 text-yellow-800';
                                    elseif ($order['status'] === 'Processing') echo 'bg-blue-100 text-blue-800';
                                    elseif ($order['status'] === 'Completed') echo 'bg-green-100 text-green-800';
                                    elseif ($order['status'] === 'Cancelled') echo 'bg-red-100 text-red-800';
                                    ?>">
                                    <?php echo htmlspecialchars($order['status']); ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-sm text-gray-600 mb-2">Shipping Address:</p>
                        <p class="text-gray-800"><?php echo htmlspecialchars($order['shipping_address']); ?></p>
                        <p class="text-sm text-gray-500 mt-2">Order placed on: <?php echo date('d M, Y H:i', strtotime($order['created_at'])); ?></p>
                    </div>

                    <a href="order_success.php?id=<?php echo $order['id']; ?>" class="inline-block mt-4 text-teal-600 hover:text-teal-700 font-semibold">
                        View Details <i class="fa fa-arrow-right ml-2"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- FOOTER -->
<footer class="bg-black text-white text-center py-4 mt-12">
    © 2026 TrendAura - Your Trusted Fashion Partner
</footer>

</body>
</html>
