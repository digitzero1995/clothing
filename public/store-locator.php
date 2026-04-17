<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Locator - TrendAura</title>
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
        <a href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'login.php'; ?>"><i class="fa fa-user"></i></a>
        <a href="cart.php"><i class="fa fa-shopping-cart"></i></a>
    </div>
</nav>

<!-- MAIN CONTENT -->
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold text-center mb-4">Find Our Stores</h1>
    <p class="text-center text-gray-600 mb-8">Visit your nearest TrendAura store</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Location 1 -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-2xl font-bold mb-4 text-teal-600"><i class="fa fa-map-marker mr-2"></i>Mumbai Store</h3>
            <div class="space-y-2 text-gray-700">
                <p><strong>Address:</strong> 123 Fashion Street, Bandra, Mumbai 400050</p>
                <p><strong>Phone:</strong> <a href="tel:+912265123456" class="text-teal-600 hover:underline">+91 22-6512-3456</a></p>
                <p><strong>Hours:</strong> Mon-Sun: 10 AM - 9 PM</p>
                <p><strong>Email:</strong> <a href="mailto:mumbai@trendaura.com" class="text-teal-600 hover:underline">mumbai@trendaura.com</a></p>
            </div>
            <button class="mt-4 w-full bg-teal-500 text-white py-2 rounded hover:bg-teal-600">Get Directions</button>
        </div>

        <!-- Location 2 -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-2xl font-bold mb-4 text-teal-600"><i class="fa fa-map-marker mr-2"></i>Delhi Store</h3>
            <div class="space-y-2 text-gray-700">
                <p><strong>Address:</strong> 456 Style Avenue, Connaught Place, New Delhi 110001</p>
                <p><strong>Phone:</strong> <a href="tel:+911145678901" class="text-teal-600 hover:underline">+91 11-4567-8901</a></p>
                <p><strong>Hours:</strong> Mon-Sun: 10 AM - 8:30 PM</p>
                <p><strong>Email:</strong> <a href="mailto:delhi@trendaura.com" class="text-teal-600 hover:underline">delhi@trendaura.com</a></p>
            </div>
            <button class="mt-4 w-full bg-teal-500 text-white py-2 rounded hover:bg-teal-600">Get Directions</button>
        </div>

        <!-- Location 3 -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-2xl font-bold mb-4 text-teal-600"><i class="fa fa-map-marker mr-2"></i>Bangalore Store</h3>
            <div class="space-y-2 text-gray-700">
                <p><strong>Address:</strong> 789 Trend Plaza, MG Road, Bangalore 560001</p>
                <p><strong>Phone:</strong> <a href="tel:+918060123456" class="text-teal-600 hover:underline">+91 80-6012-3456</a></p>
                <p><strong>Hours:</strong> Mon-Sun: 10 AM - 9 PM</p>
                <p><strong>Email:</strong> <a href="mailto:bangalore@trendaura.com" class="text-teal-600 hover:underline">bangalore@trendaura.com</a></p>
            </div>
            <button class="mt-4 w-full bg-teal-500 text-white py-2 rounded hover:bg-teal-600">Get Directions</button>
        </div>

        <!-- Location 4 -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-2xl font-bold mb-4 text-teal-600"><i class="fa fa-map-marker mr-2"></i>Hyderabad Store</h3>
            <div class="space-y-2 text-gray-700">
                <p><strong>Address:</strong> 321 Fashion Hub, Jubilee Hills, Hyderabad 500033</p>
                <p><strong>Phone:</strong> <a href="tel:+914023456789" class="text-teal-600 hover:underline">+91 40-2345-6789</a></p>
                <p><strong>Hours:</strong> Mon-Sun: 10:30 AM - 9 PM</p>
                <p><strong>Email:</strong> <a href="mailto:hyderabad@trendaura.com" class="text-teal-600 hover:underline">hyderabad@trendaura.com</a></p>
            </div>
            <button class="mt-4 w-full bg-teal-500 text-white py-2 rounded hover:bg-teal-600">Get Directions</button>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer class="bg-black text-white text-center py-4 mt-12">
    © 2026 TrendAura - Your Trusted Fashion Partner
</footer>

</body>
</html>
