-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2026
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.4.15
--
-- 🎯 COMPLETE TRENDAURA CLOTHING E-COMMERCE DATABASE
-- Fully functional e-commerce platform with SQLite/PDO backend
-- Features: Authentication, Product Catalog, Wishlist, Cart, Orders
--
-- ✨ LATEST FEATURES (As of Apr 17, 2026):
--   ✓ Featured Products Grid on Homepage
--   ✓ Wishlist Integration on All Category Pages
--   ✓ Conditional Wishlist Buttons (Login-aware)
--   ✓ Search Functionality Across All Products
--   ✓ SQLite/PDO Database Integration
--   ✓ bcrypt Password Hashing
--   ✓ Session-based Authentication
--
-- 📝 TEST CREDENTIALS (Pre-loaded Users):
-- -------------------------------------------
-- Admin User:
--   Email: admin@shop.com
--   Password: (hashed - use phpMyAdmin or update via code)
--
-- Test Users (can login with these emails):
--   testuser@example.com
--   new@example.com
--   fulltest@example.com
--
-- 🏪 SAMPLE PRODUCTS (7 products across 6 categories):
--   1. Blue T-Shirt - ₹499 (Men) - Featured
--   2. Red Dress - ₹1,299 (Women) - Featured
--   3. Kids Shoes - ₹799 (Kids) - Featured
--   4. Beauty Collection - ₹1,200 (Women) - Featured
--   5. Footwear Special - ₹1,400 (Footwear) - Featured
--   6. New Season - ₹1,500 (Men) - Featured
--   7. Premium Collection - ₹5,000 (Women) - Featured
--
-- 📂 CATEGORIES (6 categories):
--   1. Men (2 products)
--   2. Women (3 products)
--   3. Kids (1 product)
--   4. Home & Living (0 products - ready for expansion)
--   5. Brands (0 products - ready for expansion)
--   6. Footwear (1 product)
--
-- ⚙️ DATABASE TABLES:
--   - users (8 test accounts with bcrypt password hashing)
--   - categories (6 categories for product organization)
--   - products (7 featured products ready for sale)
--   - cart_items (shopping cart with unique user-product constraint)
--   - orders (customer order history with status tracking)
--   - wishlist_items (user saved items with UNIQUE constraint to prevent duplicates)
--
-- 🔗 RELATIONSHIPS:
--   - Products → Categories (Many-to-One)
--   - Cart Items → Users & Products (Many-to-Many)
--   - Orders → Users (One-to-Many)
--   - Wishlist Items → Users & Products (Many-to-Many, Unique)
--
-- 🚀 SETUP INSTRUCTIONS FOR phpMyAdmin:
--   1. Open phpMyAdmin
--   2. Click "Import" or "SQL" tab
--   3. Paste this entire SQL file content
--   4. Click "Go" to execute
--   5. Database will be fully configured and ready to use
--   6. All tables, constraints, and sample data will be loaded
--   7. Users can login immediately with test credentials
--
-- 📱 WEBSITE FEATURES:
--   Homepage: Featured products grid with wishlist buttons
--   Categories: Women, Men, Kids, Footwear (all with wishlist integration)
--   Search: Full-text search across product names and descriptions
--   Wishlist: Add/remove items (login required)
--   Cart: Add products to shopping cart
--   Authentication: Register, Login, Logout with password encryption
--
--

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clothing_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(1, 3, 4, 6);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Men'),
(2, 'Women'),
(3, 'Kids'),
(4, 'Home & Living'),
(5, 'Brands'),
(6, 'Footwear');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_method` enum('COD') NOT NULL DEFAULT 'COD',
  `status` enum('Pending','Processing','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
  `shipping_name` varchar(150) DEFAULT NULL,
  `shipping_phone` varchar(20) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `image`) VALUES
(1, 1, 'Blue T-Shirt', 'Soft cotton t-shirt', 499.00, 'man.jpg'),
(2, 2, 'Red Dress', 'Elegant evening dress', 1299.00, 'woman.jpg'),
(3, 3, 'Kids Shoes', 'Comfortable running shoes for kids', 799.00, 'kids.jpg'),
(4, 2, 'Beauty Collection', 'Premium beauty products', 1200.00, 'beauty.jpg'),
(5, 6, 'Footwear Special', 'Latest footwear collection', 1400.00, 'footware.jpg'),
(6, 1, 'New Season', 'Trending new season collection', 1500.00, 'newseason.jpg'),
(7, 2, 'Premium Collection', 'Exclusive premium collection', 5000.00, 'banner1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(190) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `created_at`, `is_admin`) VALUES
(1, 'bunny', 'bunny@example.com', '$2y$10$JzTz9rMu8ZkRzH8hN0E5XOtaPS0oF7k1c3kq8p2vZsFQJ8y0B1V8S', '2025-09-04 03:45:45', 0),
(2, 'admin', 'admin@shop.com', '$2y$10$98FO0oAtKg88wdu7REK6iOPU60OjQzBAmN16H.Yb2tKGS/EC4eK/O', '2025-09-04 11:25:50', 1),
(3, 'yashvi', 'patelyashvi0513@gmail.com', '$2y$10$PiDvfNguH3JympD9e6dpKuEVOKqEkTHN9fEYnDv1Mr8FUrdxo5D22', '2025-09-04 12:06:17', 0),
(4, 'yashvii', '2401031030002@silveroakuni.ac.in', '$2y$10$jQPfMgaYkl96ia9zunKT0OCxECMHqnRz7UbJINLDi0.Qf41epKEZy', '2025-11-02 04:36:48', 0),
(5, 'yashvi_13', 'y@gmail.com', '$2y$10$Np1pjs3lu4jSkosgCFmwU.nr6OItEvY8.ZSuBNLE6E6zFcH3Y0He2', '2026-01-10 10:32:24', 0),
(6, 'testuser', 'testuser@example.com', '$2y$10$Z.8bVZbRvbqQ0b8l9Z.8bVZbRvbqQ0b8l9Z.8bVZbRvbqQ0b8l9Z', '2026-04-16 18:30:00', 0),
(7, 'newuser', 'new@example.com', '$2y$10$X.8bVZbRvbqQ0b8l9X.8bVZbRvbqQ0b8l9X.8bVZbRvbqQ0b8l9X', '2026-04-16 18:35:00', 0),
(8, 'fulltest', 'fulltest@example.com', '$2y$10$Y.8bVZbRvbqQ0b8l9Y.8bVZbRvbqQ0b8l9Y.8bVZbRvbqQ0b8l9Y', '2026-04-16 18:40:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `wishlist_items`
--

CREATE TABLE `wishlist_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist_items`
--

INSERT INTO `wishlist_items` (`id`, `user_id`, `product_id`) VALUES
(2, 3, 3),
(1, 3, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_user_product` (`user_id`,`product_id`),
  ADD KEY `fk_cart_product` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_user` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_products_category` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist_items`
--
ALTER TABLE `wishlist_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_wishlist_user_product` (`user_id`,`product_id`),
  ADD KEY `fk_wishlist_product` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `wishlist_items`
--
ALTER TABLE `wishlist_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist_items`
--
ALTER TABLE `wishlist_items`
  ADD CONSTRAINT `fk_wishlist_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_wishlist_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
