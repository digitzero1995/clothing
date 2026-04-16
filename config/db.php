<?php
// Database configuration
$host = "localhost";
$user = "root";
$pass = "";
$db   = "clothing_db";

$conn = null;
if (extension_loaded('mysqli')) {
    $conn = @mysqli_connect($host, $user, $pass, $db);
}

// If mysqli is not available or connection failed, we'll use demo mode
$demo_mode = !$conn;

// Demo products data
if ($demo_mode) {
    $DEMO_PRODUCTS = [
        ['id' => 1, 'name' => 'Blue T-Shirt', 'price' => 499, 'image' => 'man.jpg', 'category_id' => 1, 'description' => 'Soft cotton t-shirt'],
        ['id' => 2, 'name' => 'Red Dress', 'price' => 1299, 'image' => 'woman.jpg', 'category_id' => 2, 'description' => 'Elegant evening dress'],
        ['id' => 3, 'name' => 'Kids Shoes', 'price' => 799, 'image' => 'kids.jpg', 'category_id' => 3, 'description' => 'Comfortable running shoes for kids'],
        ['id' => 4, 'name' => 'Beauty Collection', 'price' => 1200, 'image' => 'beauty.jpg', 'category_id' => 2, 'description' => 'Premium beauty products'],
        ['id' => 5, 'name' => 'Footwear Special', 'price' => 1400, 'image' => 'footware.jpg', 'category_id' => 6, 'description' => 'Latest footwear collection'],
        ['id' => 6, 'name' => 'New Season', 'price' => 1500, 'image' => 'newseason.jpg', 'category_id' => 1, 'description' => 'Trending new season collection'],
        ['id' => 7, 'name' => 'Premium Collection', 'price' => 5000, 'image' => 'banner1.jpg', 'category_id' => 2, 'description' => 'Exclusive premium collection'],
    ];
    
    $DEMO_CATEGORIES = [
        ['id' => 1, 'name' => 'Men'],
        ['id' => 2, 'name' => 'Women'],
        ['id' => 3, 'name' => 'Kids'],
        ['id' => 4, 'name' => 'Home & Living'],
        ['id' => 5, 'name' => 'Brands'],
        ['id' => 6, 'name' => 'Footwear']
    ];
}
?>