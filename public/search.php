<?php
session_start();
include '../config/db_sqlite.php';

$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$products = [];

if (!empty($search_query)) {
    try {
        // Search products by name or description
        $stmt = $conn->prepare("
            SELECT id, name, price, image, category_id, description 
            FROM products 
            WHERE name LIKE ? OR description LIKE ?
            ORDER BY name ASC
        ");
        $search_term = '%' . $search_query . '%';
        $stmt->execute([$search_term, $search_term]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $error = "Search failed: " . htmlspecialchars($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - TrendAura</title>
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
    <div class="text-3xl font-bold text-teal-500">TrendAura</div>
    
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
        <form method="GET" class="flex items-center bg-gray-100 rounded px-3 py-1">
            <input type="text" name="q" placeholder="Search..." value="<?php echo htmlspecialchars($search_query); ?>" class="bg-gray-100 border-none outline-none w-32">
            <button type="submit" class="text-teal-500 ml-2"><i class="fa fa-search"></i></button>
        </form>
        <i class="fa fa-heart"></i>
        <a href="login.php"><i class="fa fa-user"></i></a>
        <a href="cart.php"><i class="fa fa-cart-shopping"></i></a>
    </div>
</nav>

<!-- SEARCH RESULTS -->
<div class="p-8">
    <h1 class="text-4xl font-bold mb-2">Search Results</h1>
    <p class="text-gray-600 mb-6">
        <?php 
        if (!empty($search_query)) {
            echo "Results for: <strong>" . htmlspecialchars($search_query) . "</strong> (" . count($products) . " found)";
        } else {
            echo "Enter a search term to find products";
        }
        ?>
    </p>

    <?php if (count($products) > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <?php foreach ($products as $product): ?>
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
                    <img src="assets/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-48 object-cover rounded-t-lg">
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="text-gray-600 text-sm mb-3"><?php echo htmlspecialchars(substr($product['description'], 0, 50)); ?>...</p>
                        <div class="flex justify-between items-center">
                            <span class="text-teal-500 font-bold text-lg">₹<?php echo number_format($product['price'], 2); ?></span>
                            <a href="product_details.php?id=<?php echo $product['id']; ?>" class="bg-teal-500 text-white px-3 py-1 rounded text-sm hover:bg-teal-600">View</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif (!empty($search_query)): ?>
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-6 py-4 rounded">
            <p>😞 No products found matching "<strong><?php echo htmlspecialchars($search_query); ?></strong>"</p>
            <p class="mt-2"><a href="index.php" class="text-teal-500 hover:underline">← Back to Home</a></p>
        </div>
    <?php endif; ?>
</div>

<!-- FOOTER -->
<footer class="bg-black text-white text-center p-4 mt-8">
    © 2026 TrendAura
</footer>

</body>
</html>
