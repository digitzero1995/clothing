<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Mode - TrendAura</title>
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
    <h1 class="text-4xl font-bold text-center mb-2">Store Display Mode</h1>
    <p class="text-center text-gray-600 mb-12">Welcome to TrendAura Store. Browse our latest collections.</p>

    <!-- Features -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <div class="bg-gradient-to-br from-teal-500 to-blue-600 text-white rounded-lg shadow-lg p-8 hover:shadow-xl transition">
            <i class="fa fa-shopping-bag text-4xl mb-4 block"></i>
            <h3 class="text-2xl font-bold mb-2">Browse Collections</h3>
            <p class="mb-4">Explore our curated selection of premium fashion items</p>
            <a href="category.php?cat=all" class="inline-block bg-white text-teal-600 px-6 py-2 rounded font-bold hover:bg-gray-100">
                Shop Now →
            </a>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-pink-600 text-white rounded-lg shadow-lg p-8 hover:shadow-xl transition">
            <i class="fa fa-gift text-4xl mb-4 block"></i>
            <h3 class="text-2xl font-bold mb-2">Special Offers</h3>
            <p class="mb-4">Enjoy exclusive deals and promotions on selected items</p>
            <a href="category.php?cat=sale" class="inline-block bg-white text-purple-600 px-6 py-2 rounded font-bold hover:bg-gray-100">
                View Deals →
            </a>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-red-600 text-white rounded-lg shadow-lg p-8 hover:shadow-xl transition">
            <i class="fa fa-star text-4xl mb-4 block"></i>
            <h3 class="text-2xl font-bold mb-2">Trending Items</h3>
            <p class="mb-4">Check out what's trending this season</p>
            <a href="search.php?q=dress" class="inline-block bg-white text-orange-600 px-6 py-2 rounded font-bold hover:bg-gray-100">
                Explore →
            </a>
        </div>
    </div>

    <!-- Store Info -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-12">
        <h2 class="text-3xl font-bold text-center mb-6">Welcome to TrendAura Store</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4 text-teal-600"><i class="fa fa-info-circle mr-2"></i>About This Store</h3>
                <p class="text-gray-700 leading-relaxed">
                    TrendAura is your destination for premium fashion and lifestyle products. 
                    Our carefully curated collections feature the latest trends and timeless classics, 
                    perfect for every occasion. With a strong commitment to quality and customer satisfaction, 
                    we bring you the best fashion experience.
                </p>
            </div>

            <div>
                <h3 class="text-xl font-bold mb-4 text-teal-600"><i class="fa fa-headset mr-2"></i>Store Services</h3>
                <ul class="space-y-2 text-gray-700">
                    <li><i class="fa fa-check text-green-500 mr-2"></i>Free delivery on orders above ₹500</li>
                    <li><i class="fa fa-check text-green-500 mr-2"></i>Easy returns & exchanges</li>
                    <li><i class="fa fa-check text-green-500 mr-2"></i>Cash on Delivery available</li>
                    <li><i class="fa fa-check text-green-500 mr-2"></i>Customer support 24/7</li>
                    <li><i class="fa fa-check text-green-500 mr-2"></i>Secure payments</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="bg-gradient-to-r from-teal-500 to-blue-600 text-white rounded-lg p-8 text-center">
        <h2 class="text-2xl font-bold mb-6">Quick Navigation</h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <a href="category.php?cat=women" class="bg-white bg-opacity-20 hover:bg-opacity-30 py-3 rounded font-semibold transition">Women</a>
            <a href="category.php?cat=men" class="bg-white bg-opacity-20 hover:bg-opacity-30 py-3 rounded font-semibold transition">Men</a>
            <a href="category.php?cat=kids" class="bg-white bg-opacity-20 hover:bg-opacity-30 py-3 rounded font-semibold transition">Kids</a>
            <a href="store-locator.php" class="bg-white bg-opacity-20 hover:bg-opacity-30 py-3 rounded font-semibold transition">Stores</a>
            <a href="contact.php" class="bg-white bg-opacity-20 hover:bg-opacity-30 py-3 rounded font-semibold transition">Contact</a>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer class="bg-black text-white text-center py-4 mt-12">
    © 2026 TrendAura - Your Trusted Fashion Partner
</footer>

</body>
</html>
