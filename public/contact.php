<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - TrendAura</title>
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
    <h1 class="text-4xl font-bold text-center mb-2">Contact Us</h1>
    <p class="text-center text-gray-600 mb-8">We're here to help. Get in touch with our team.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <!-- Email -->
        <div class="bg-white rounded-lg shadow p-6 text-center hover:shadow-lg transition">
            <i class="fa fa-envelope text-teal-500 text-4xl mb-4 block"></i>
            <h3 class="text-xl font-bold mb-2">Email Us</h3>
            <p class="text-gray-600">Have questions? Send us an email and we'll respond within 24 hours.</p>
            <a href="mailto:support@trendaura.com" class="text-teal-600 hover:text-teal-700 font-bold mt-4 inline-block">
                support@trendaura.com
            </a>
        </div>

        <!-- Phone -->
        <div class="bg-white rounded-lg shadow p-6 text-center hover:shadow-lg transition">
            <i class="fa fa-phone text-teal-500 text-4xl mb-4 block"></i>
            <h3 class="text-xl font-bold mb-2">Call Us</h3>
            <p class="text-gray-600">Our customer service team is ready to assist you.</p>
            <a href="tel:+919876543210" class="text-teal-600 hover:text-teal-700 font-bold mt-4 inline-block">
                +91-9876-543-210
            </a>
            <p class="text-sm text-gray-500 mt-2">Mon-Sat: 10 AM - 6 PM</p>
        </div>

        <!-- Address -->
        <div class="bg-white rounded-lg shadow p-6 text-center hover:shadow-lg transition">
            <i class="fa fa-map-marker text-teal-500 text-4xl mb-4 block"></i>
            <h3 class="text-xl font-bold mb-2">Visit Us</h3>
            <p class="text-gray-600">123 Fashion Street, Mumbai 400050, India</p>
            <a href="store-locator.php" class="text-teal-600 hover:text-teal-700 font-bold mt-4 inline-block">
                Find all locations
            </a>
        </div>
    </div>

    <!-- Contact Form -->
    <div class="bg-white rounded-lg shadow p-8 max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold mb-6">Send us a Message</h2>
        <form method="POST" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Name</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500" />
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Email</label>
                    <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500" />
                </div>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Phone</label>
                <input type="tel" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500" />
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Subject</label>
                <select name="subject" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white">
                    <option value="">-- Select a Subject --</option>
                    <option value="order">Order Related</option>
                    <option value="product">Product Question</option>
                    <option value="shipping">Shipping Issue</option>
                    <option value="return">Return/Exchange</option>
                    <option value="feedback">Feedback</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Message</label>
                <textarea name="message" rows="5" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
            </div>

            <button type="submit" class="w-full bg-teal-500 text-white font-bold py-3 rounded hover:bg-teal-600 transition">
                Send Message
            </button>
        </form>
    </div>
</div>

<!-- FOOTER -->
<footer class="bg-black text-white text-center py-4 mt-12">
    © 2026 TrendAura - Your Trusted Fashion Partner
</footer>

</body>
</html>
