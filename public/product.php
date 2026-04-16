<?php require_once __DIR__ . '/../includes/header.php'; ?>
<?php
$id = (int)($_GET['id'] ?? 0);


// Handle add to cart / wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
if (!isLoggedIn()) { header('Location: /auth/login.php'); exit; }


if (isset($_POST['add_cart'])) {
$qty = max(1, (int)($_POST['quantity'] ?? 1));
// upsert into cart
$stmt = $mysqli->prepare('INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?,?,?)
ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)');
$uid = $_SESSION['user']['id'];
$stmt->bind_param('iii', $uid, $id, $qty);
$stmt->execute();
$stmt->close();
echo '<div class="alert alert-success">Added to cart.</div>';
}
if (isset($_POST['add_wishlist'])) {
$stmt = $mysqli->prepare('INSERT IGNORE INTO wishlist_items (user_id, product_id) VALUES (?,?)');
$uid = $_SESSION['user']['id'];
$stmt->bind_param('ii', $uid, $id);
$stmt->execute();
$stmt->close();
echo '<div class="alert alert-success">Added to wishlist.</div>';
}
}

$stmt = $mysqli->prepare('SELECT p.id, p.name, p.description, p.price, p.image, c.name AS category FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.id=?');
$stmt->bind_param('i', $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$product) { echo '<div class="alert alert-warning">Product not found.</div>'; require_once __DIR__ . '/../includes/footer.php'; exit; }
?>
<div class="row g-4">
<div class="col-md-6">
<?php if ($product['image']): ?>
<img class="img-fluid rounded" src="uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
<?php endif; ?>
</div>
<div class="col-md-6">
<h3><?= htmlspecialchars($product['name']) ?></h3>
<p class="text-muted">Category: <?= htmlspecialchars($product['category'] ?? 'Uncategorized') ?></p>
<h4>₹<?= number_format($product['price'], 2) ?></h4>
<p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
<form method="post" class="d-flex align-items-center gap-2">
<input type="number" min="1" name="quantity" value="1" class="form-control" style="max-width:120px">
<button class="btn btn-primary" name="add_cart">Add to Cart</button>
<button class="btn btn-outline-secondary" name="add_wishlist">Add to Wishlist</button>
</form>
</div>
</div>
<?php require_once __DIR__ . '/../includes/header.php'; ?>