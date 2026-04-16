<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle GET actions (add, remove, decrease)
$action = $_GET['action'] ?? '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($action === 'add' && $id > 0) {
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    header("Location: cart.php");
    exit();
}

if ($action === 'decrease' && $id > 0 && isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]--;
    if ($_SESSION['cart'][$id] <= 0) {
        unset($_SESSION['cart'][$id]);
    }
    header("Location: cart.php");
    exit();
}

if ($action === 'remove' && $id > 0) {
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}

// Function to get product details
function getProductFromCart($productId, $demo_mode, $DEMO_PRODUCTS) {
    if ($demo_mode) {
        foreach ($DEMO_PRODUCTS as $p) {
            if ($p['id'] == $productId) {
                return $p;
            }
        }
    }
    return null;
}

?>

<style>
    body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
    .container { max-width: 1200px; margin: 0 auto; }
    .cart-header { text-align: center; margin: 30px 0; }
    .cart-table { width: 100%; background: white; border-collapse: collapse; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin: 20px 0; }
    .cart-table thead { background: #1abc9c; color: white; }
    .cart-table th { padding: 15px; text-align: left; }
    .cart-table td { padding: 15px; border-bottom: 1px solid #eee; }
    .product-image { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; }
    .quantity-buttons { display: flex; gap: 5px; align-items: center; }
    .quantity-btn { padding: 5px 10px; border: 1px solid #ddd; background: white; cursor: pointer; border-radius: 4px; }
    .btn-remove { background: #d9534f; color: white; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; }
    .cart-summary { background: white; padding: 20px; border-radius: 8px; text-align: right; margin-top: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    .summary-item { display: flex; justify-content: space-between; margin: 10px 0; font-size: 16px; }
    .total { display: flex; justify-content: space-between; margin-top: 15px; font-size: 20px; font-weight: bold; color: #1abc9c; border-top: 1px solid #eee; padding-top: 15px; }
    .cart-actions { display: flex; gap: 10px; justify-content: center; margin-top: 30px; }
    .btn { padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; text-decoration: none; display: inline-block; }
    .btn-checkout { background: #1abc9c; color: white; }
    .btn-continue { background: #666; color: white; }
    .empty-cart { text-align: center; padding: 60px 20px; color: #999; }
</style>

<div class="container">
    <div class="cart-header">
        <h1>🛒 Your Shopping Cart</h1>
    </div>

    <?php if (!empty($_SESSION['cart'])): ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $grand_total = 0;
                foreach ($_SESSION['cart'] as $pid => $qty):
                    $product = getProductFromCart($pid, $demo_mode, $DEMO_PRODUCTS);
                    if (!$product) continue;
                    $subtotal = $product['price'] * $qty;
                    $grand_total += $subtotal;
                ?>
                    <tr>
                        <td><img src="assets/<?php echo htmlspecialchars($product['image']); ?>" class="product-image"></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td>₹<?php echo number_format($product['price'], 2); ?></td>
                        <td>
                            <a href="cart.php?action=decrease&id=<?php echo $pid; ?>" class="quantity-btn">−</a>
                            <span><?php echo $qty; ?></span>
                            <a href="cart.php?action=add&id=<?php echo $pid; ?>" class="quantity-btn">+</a>
                        </td>
                        <td>₹<?php echo number_format($subtotal, 2); ?></td>
                        <td><a href="cart.php?action=remove&id=<?php echo $pid; ?>" class="btn-remove">Remove</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-summary">
            <div class="summary-item"><span>Subtotal:</span> <span>₹<?php echo number_format($grand_total, 2); ?></span></div>
            <div class="summary-item"><span>Shipping:</span> <span>FREE</span></div>
            <div class="total"><span>Total:</span> <span>₹<?php echo number_format($grand_total, 2); ?></span></div>
        </div>

        <div class="cart-actions">
            <a href="checkout.php" class="btn btn-checkout">Proceed to Checkout</a>
            <a href="category.php?cat=all" class="btn btn-continue">Continue Shopping</a>
        </div>

    <?php else: ?>
        <div class="empty-cart">
            <p style="font-size: 18px;">Your cart is empty!</p>
            <a href="category.php?cat=all" class="btn btn-continue" style="margin-top: 20px;">Start Shopping</a>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/foooter.php'; ?>
