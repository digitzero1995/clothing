<?php
session_start();
include '../config/db_sqlite.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=wishlist.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$success_msg = '';
$error_msg = '';

// Handle Add or Remove actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $action = $_GET['action'];

    try {
        if ($action === 'add') {
            // Check if already in wishlist
            $stmt = $conn->prepare("SELECT id FROM wishlist_items WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$user_id, $product_id]);
            $exists = $stmt->fetch();

            if (!$exists) {
                // Insert into wishlist
                $insert = $conn->prepare("INSERT INTO wishlist_items (user_id, product_id) VALUES (?, ?)");
                $insert->execute([$user_id, $product_id]);
                $success_msg = "✅ Added to wishlist!";
            } else {
                $error_msg = "Already in your wishlist";
            }
        } elseif ($action === 'remove') {
            // Remove from wishlist
            $delete = $conn->prepare("DELETE FROM wishlist_items WHERE user_id = ? AND product_id = ?");
            $delete->execute([$user_id, $product_id]);
            $success_msg = "✅ Removed from wishlist";
        }
        
        // Redirect after 1 second
        header("refresh:1;url=wishlist.php");
    } catch (Exception $e) {
        $error_msg = "Error: " . htmlspecialchars($e->getMessage());
    }
}

// Fetch wishlist items for this user
try {
    $stmt = $conn->prepare("
        SELECT p.id, p.name, p.price, p.image, p.description, p.category_id
        FROM products p
        INNER JOIN wishlist_items w ON p.id = w.product_id
        WHERE w.user_id = ?
        ORDER BY w.id DESC
    ");
    $stmt->execute([$user_id]);
    $wishlist_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $wishlist_items = [];
    $error_msg = "Failed to load wishlist";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist - TrendAura</title>
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
    
    <div class="space-x-6 hidden md:flex">
        <a href="category.php?cat=all" class="hover:text-teal-500">SHOP ALL</a>
        <a href="category.php?cat=women" class="hover:text-teal-500">WOMEN</a>
        <a href="category.php?cat=men" class="hover:text-teal-500">MEN</a>
        <a href="category.php?cat=kids" class="hover:text-teal-500">KIDS</a>
        <a href="category.php?cat=home" class="hover:text-teal-500">HOME & LIVING</a>
        <a href="category.php?cat=brands" class="hover:text-teal-500">BRANDS</a>
        <a href="category.php?cat=sale" class="hover:text-teal-500 text-red-500">SALE</a>
    </div>

    <div class="flex items-center space-x-4">
        <form method="GET" action="search.php" class="flex items-center bg-gray-100 rounded px-3 py-1">
            <input type="text" name="q" placeholder="Search..." class="bg-gray-100 border-none outline-none w-32">
            <button type="submit" class="text-teal-500 ml-2"><i class="fa fa-search"></i></button>
        </form>
        <i class="fa fa-heart text-red-500"></i>
        <a href="login.php"><i class="fa fa-user"></i></a>
        <a href="cart.php"><i class="fa fa-cart-shopping"></i></a>
    </div>
</nav>

<!-- WISHLIST CONTENT -->
<div class="container mx-auto p-8">
    <h1 class="text-4xl font-bold mb-4">❤️ My Wishlist</h1>

    <?php if ($success_msg): ?>
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-4">
            <?php echo $success_msg; ?>
        </div>
    <?php endif; ?>

    <?php if ($error_msg): ?>
        <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded mb-4">
            <?php echo $error_msg; ?>
        </div>
    <?php endif; ?>

    <?php if (count($wishlist_items) > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <?php foreach ($wishlist_items as $item): ?>
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
                    <img src="assets/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-full h-48 object-cover rounded-t-lg">
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2"><?php echo htmlspecialchars($item['name']); ?></h3>
                        <p class="text-gray-600 text-sm mb-3"><?php echo htmlspecialchars(substr($item['description'], 0, 40)); ?>...</p>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-teal-500 font-bold text-xl">₹<?php echo number_format($item['price'], 2); ?></span>
                        </div>
                        <div class="flex gap-2">
                            <a href="product_details.php?id=<?php echo $item['id']; ?>" class="flex-1 bg-teal-500 text-white px-3 py-2 rounded text-sm hover:bg-teal-600 text-center">View</a>
                            <a href="wishlist.php?action=remove&id=<?php echo $item['id']; ?>" class="flex-1 bg-red-500 text-white px-3 py-2 rounded text-sm hover:bg-red-600 text-center">
                                <i class="fa fa-trash"></i> Remove
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-6 py-8 rounded text-center">
            <p class="text-xl mb-4">😢 Your wishlist is empty</p>
            <p class="mb-4">Start adding your favorite items to your wishlist!</p>
            <a href="index.php" class="bg-teal-500 text-white px-6 py-2 rounded hover:bg-teal-600 inline-block">← Continue Shopping</a>
        </div>
    <?php endif; ?>
</div>

<!-- FOOTER -->
<footer class="bg-black text-white text-center p-4 mt-8">
    © 2026 TrendAura
</footer>

</body>
</html>