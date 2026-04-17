<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gift Cards - TrendAura</title>
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
    <h1 class="text-4xl font-bold text-center mb-8">Gift Cards</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
            <i class="fa fa-gift text-teal-500 text-4xl mb-4"></i>
            <h3 class="text-2xl font-bold mb-3">₹500 Gift Card</h3>
            <p class="text-gray-600 mb-4">Perfect for trying our collection</p>
            <button class="w-full bg-teal-500 text-white py-2 rounded hover:bg-teal-600">Buy Now</button>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
            <i class="fa fa-gift text-teal-500 text-4xl mb-4"></i>
            <h3 class="text-2xl font-bold mb-3">₹1,000 Gift Card</h3>
            <p class="text-gray-600 mb-4">Great for fashion enthusiasts</p>
            <button class="w-full bg-teal-500 text-white py-2 rounded hover:bg-teal-600">Buy Now</button>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
            <i class="fa fa-gift text-teal-500 text-4xl mb-4"></i>
            <h3 class="text-2xl font-bold mb-3">₹5,000 Gift Card</h3>
            <p class="text-gray-600 mb-4">Premium gift for special occasions</p>
            <button class="w-full bg-teal-500 text-white py-2 rounded hover:bg-teal-600">Buy Now</button>
        </div>
    </div>

    <div class="bg-gradient-to-r from-teal-500 to-blue-600 text-white rounded-lg p-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Custom Gift Cards</h2>
        <p class="mb-6 text-lg">Create a custom amount gift card for your special someone</p>
        <button class="bg-white text-teal-600 px-8 py-3 rounded-lg font-bold hover:bg-gray-100">Create Custom Gift Card</button>
    </div>
</div>

<!-- FOOTER -->
<footer class="bg-black text-white text-center py-4 mt-12">
    © 2026 TrendAura - Your Trusted Fashion Partner
</footer>

</body>
</html>
