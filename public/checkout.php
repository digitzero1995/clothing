<?php
@session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fixed database connection with inline setup
$db_file = dirname(__DIR__) . '/clothing.db';
$conn = null;
try {
    $conn = new PDO('sqlite:' . $db_file);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    $conn = null;
}

$errorMsg = '';
$successMsg = '';
$order_id = null;
$cart_total = 0;
$cart_items = [];

$address = '';
$contact = '';
$payment_method = '';

// Calculate cart total and items
if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && !empty($_SESSION['cart']) && $conn) {
    foreach ($_SESSION['cart'] as $id => $quantity) {
        try {
            $stmt = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
            $stmt->execute([$id]);
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $subtotal = $row['price'] * $quantity;
                $cart_total += $subtotal;
                $cart_items[] = [
                    'name' => $row['name'],
                    'quantity' => $quantity,
                    'price' => $row['price'],
                    'subtotal' => $subtotal
                ];
            }
        } catch (Exception $e) {
            // Continue on error
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $conn) {
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $contact = isset($_POST['contact']) ? trim($_POST['contact']) : '';
    $payment_method = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : '';

    if (empty($address) || empty($contact) || empty($payment_method)) {
        $errorMsg = "Please fill in all required fields.";
    } elseif (empty($_SESSION['cart'])) {
        $errorMsg = "Your cart is empty.";
    } else {
        try {
            $user_id = $_SESSION['user_id'];
            $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, payment_method, status, shipping_name, shipping_phone, shipping_address) VALUES (?, ?, ?, 'Pending', ?, ?, ?)");
            $stmt->execute([
                $user_id, 
                $cart_total, 
                $payment_method, 
                $_SESSION['username'] ?? 'User', 
                $contact, 
                $address
            ]);
            
            $order_id = $conn->lastInsertId();
            unset($_SESSION['cart']);
            $successMsg = "Thank you! Your order #" . str_pad($order_id, 5, '0', STR_PAD_LEFT) . " has been placed successfully.";
        } catch (Exception $e) {
            $errorMsg = "Error placing order. Please try again.";
        }
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
<body class="bg-gray-50">

<!-- TOP BAR -->
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
        <a href="index.php" title="Home"><i class="fa fa-home text-gray-700 text-xl"></i></a>
        <a href="wishlist.php" title="Wishlist"><i class="fa fa-heart text-red-500 text-xl"></i></a>
        <a href="cart.php" title="Shopping Cart"><i class="fa fa-shopping-cart text-gray-700 text-xl"></i></a>
    </div>
</nav>

<!-- MAIN CHECKOUT SECTION -->
<div class="container mx-auto px-4 py-8">
    <?php if (!$conn): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-lg">
            <h2 class="text-2xl font-bold text-red-800 mb-2">Database Error</h2>
            <p class="text-red-700">Unable to connect to database. Please try again later.</p>
        </div>
    <?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    
    <!-- CHECKOUT FORM -->
    <div class="md:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Checkout</h2>

            <?php if ($successMsg): ?>
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4">
                    <h3 class="font-bold text-green-800 mb-2">✓ Order Placed Successfully!</h3>
                    <p class="text-green-700"><?php echo htmlspecialchars($successMsg); ?></p>
                </div>
                <div class="text-center mt-8">
                    <p class="text-gray-600 mb-4">Redirecting to order details in 3 seconds...</p>
                    <a href="order_success.php?id=<?php echo $order_id; ?>" class="inline-block bg-teal-500 text-white px-6 py-2 rounded hover:bg-teal-600">
                        View Order Details
                    </a>
                </div>
                <script>
                    setTimeout(function() {
                        window.location.href = "order_success.php?id=<?php echo $order_id; ?>";
                    }, 3000);
                </script>
            <?php else: ?>
                <?php if ($errorMsg): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                        <p class="text-red-800 font-semibold"><?php echo htmlspecialchars($errorMsg); ?></p>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Full Name</label>
                        <input type="text" value="<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>" disabled class="w-full px-4 py-2 border border-gray-300 rounded bg-gray-100" />
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Email</label>
                        <input type="email" value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>" disabled class="w-full px-4 py-2 border border-gray-300 rounded bg-gray-100" />
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Shipping Address *</label>
                        <textarea name="address" placeholder="Enter your complete address" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500"><?php echo htmlspecialchars($address); ?></textarea>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Phone Number *</label>
                        <input type="tel" name="contact" placeholder="Enter your phone number" value="<?php echo htmlspecialchars($contact); ?>" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500" />
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Payment Method *</label>
                        <select name="payment_method" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option value="">-- Select Payment Method --</option>
                            <option value="cod" <?php if($payment_method == 'cod') echo 'selected'; ?>>Cash on Delivery (COD)</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-teal-500 text-white font-bold py-3 rounded hover:bg-teal-600 transition">
                        <i class="fa fa-check-circle mr-2"></i> Place Order
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- ORDER SUMMARY -->
    <div class="md:col-span-1">
        <div class="bg-white rounded-lg shadow p-6 sticky top-4">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Order Summary</h3>
            
            <?php if (!empty($cart_items)): ?>
                <div class="space-y-3 mb-4 pb-4 border-b">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="flex justify-between text-gray-700">
                            <span><?php echo htmlspecialchars($item['name']); ?> <span class="text-gray-500">x<?php echo $item['quantity']; ?></span></span>
                            <span class="font-semibold">₹<?php echo number_format($item['subtotal'], 2); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="space-y-2">
                    <div class="flex justify-between text-gray-700">
                        <span>Subtotal:</span>
                        <span>₹<?php echo number_format($cart_total, 2); ?></span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Shipping:</span>
                        <span class="text-green-600">FREE</span>
                    </div>
                    <div class="border-t pt-2 flex justify-between text-lg font-bold text-teal-600">
                        <span>Total:</span>
                        <span>₹<?php echo number_format($cart_total, 2); ?></span>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-8">Your cart is empty</p>
                <a href="category.php?cat=all" class="block text-center bg-teal-500 text-white py-2 rounded hover:bg-teal-600">
                    Continue Shopping
                </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- FOOTER -->
<footer class="bg-black text-white text-center py-4 mt-12">
    © 2026 TrendAura - Your Trusted Fashion Partner
</footer>

</body>
</html>