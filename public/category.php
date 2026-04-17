<?php 
session_start();
include '../config/db_sqlite.php';

// Get category from URL
$cat = isset($_GET['cat']) ? htmlspecialchars($_GET['cat']) : 'all';

// Map categories to category IDs
$category_map = [
    'women' => 2,
    'men' => 1,
    'kids' => 3,
    'footwear' => 6,
    'home' => 4,
    'brands' => 5,
    'sale' => 'all'
];

$cat_id = isset($category_map[$cat]) ? $category_map[$cat] : 'all';

// Fetch products based on category
$products = [];
try {
    if ($cat_id === 'all' || $cat === 'all' || $cat === 'sale') {
        $stmt = $conn->query("SELECT id, name, price, image, description FROM products ORDER BY name ASC");
    } else {
        $stmt = $conn->prepare("SELECT id, name, price, image, description FROM products WHERE category_id = ? ORDER BY name ASC");
        $stmt->execute([(int)$cat_id]);
    }
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $products = [];
}

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($cat); ?> - TrendAura</title>
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
        <a href="wishlist.php"><i class="fa fa-heart text-red-500"></i></a>
        <a href="login.php"><i class="fa fa-user"></i></a>
        <a href="cart.php"><i class="fa fa-cart-shopping"></i></a>
    </div>
</nav>

<!-- CATEGORY HEADER -->
<div class="bg-white shadow p-8 mb-6">
    <h1 class="text-4xl font-bold text-center text-gray-800">
        <?php echo strtoupper($cat); ?> Collection
    </h1>
    <p class="text-center text-gray-600 mt-2"><?php echo count($products); ?> products available</p>
</div>

<!-- PRODUCTS GRID -->
<div class="container mx-auto p-8">
    <?php if (!empty($products)): ?>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <?php foreach($products as $product): ?>
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition border">
                    <img src="assets/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-48 object-cover rounded-t-lg">
                    
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="text-gray-600 text-sm mb-3"><?php echo htmlspecialchars(substr($product['description'] ?? 'Premium item', 0, 50)); ?>...</p>
                        
                        <div class="flex justify-between items-end mb-3">
                            <span class="text-teal-500 font-bold text-xl">₹<?php echo number_format($product['price'], 2); ?></span>
                            <div class="flex gap-2">
                                <?php if ($is_logged_in): ?>
                                <a href="wishlist.php?action=add&id=<?php echo $product['id']; ?>" class="text-red-500 hover:text-red-600 text-xl transition" title="Add to Wishlist">
                                    <i class="fa fa-heart"></i>
                                </a>
                                <?php else: ?>
                                <a href="login.php?redirect=category.php?cat=<?php echo $cat; ?>" class="text-gray-400 hover:text-red-500 text-xl transition" title="Login to add to wishlist">
                                    <i class="fa fa-heart"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="flex gap-2">
                            <a href="product_details.php?id=<?php echo $product['id']; ?>" class="flex-1 bg-teal-500 text-white px-3 py-2 rounded text-sm hover:bg-teal-600 text-center">View</a>
                            <a href="cart.php?action=add&id=<?php echo $product['id']; ?>&qty=1" class="flex-1 bg-blue-500 text-white px-3 py-2 rounded text-sm hover:bg-blue-600 text-center">
                                <i class="fa fa-cart-plus"></i> Add
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-6 py-8 rounded text-center">
            <p class="text-xl mb-4">😢 No products found in this category</p>
            <a href="index.php" class="text-teal-600 hover:underline font-bold">← Back to Home</a>
        </div>
    <?php endif; ?>
</div>

<!-- FOOTER -->
<footer class="bg-black text-white text-center p-4 mt-8">
    © 2026 TrendAura
</footer>

</body>
</html>