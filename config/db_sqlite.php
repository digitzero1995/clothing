<?php
// SQLite Database configuration
$db_file = __DIR__ . '/../clothing.db';

try {
    $conn = new PDO('sqlite:' . $db_file);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create tables if they don't exist
    $conn->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            email TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            is_admin INTEGER DEFAULT 0
        );
        
        CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            price DECIMAL(10, 2) NOT NULL,
            image TEXT,
            category_id INTEGER,
            description TEXT
        );
        
        CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL UNIQUE
        );

        CREATE TABLE IF NOT EXISTS wishlist_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            product_id INTEGER NOT NULL,
            UNIQUE(user_id, product_id),
            FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY(product_id) REFERENCES products(id) ON DELETE CASCADE
        );
        
        CREATE TABLE IF NOT EXISTS orders (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            total_price DECIMAL(10, 2) NOT NULL,
            payment_method TEXT DEFAULT 'cod',
            status TEXT DEFAULT 'Pending',
            shipping_name TEXT,
            shipping_phone TEXT,
            shipping_address TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
        );
        
        CREATE TABLE IF NOT EXISTS cart_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            product_id INTEGER NOT NULL,
            quantity INTEGER DEFAULT 1,
            UNIQUE(user_id, product_id),
            FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY(product_id) REFERENCES products(id) ON DELETE CASCADE
        );
    ");
    
    // Insert sample categories if empty
    $stmt = $conn->query("SELECT COUNT(*) as cnt FROM categories");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result['cnt'] == 0) {
        $conn->exec("
            INSERT INTO categories (id, name) VALUES 
            (1, 'Men'),
            (2, 'Women'),
            (3, 'Kids'),
            (4, 'Home & Living'),
            (5, 'Brands'),
            (6, 'Footwear')
        ");
    }
    
    // Insert sample products if empty
    $stmt = $conn->query("SELECT COUNT(*) as cnt FROM products");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result['cnt'] == 0) {
        $conn->exec("
            INSERT INTO products (id, name, price, image, category_id, description) VALUES 
            (1, 'Blue T-Shirt', 499, 'man.jpg', 1, 'Soft cotton t-shirt'),
            (2, 'Red Dress', 1299, 'woman.jpg', 2, 'Elegant evening dress'),
            (3, 'Kids Shoes', 799, 'kids.jpg', 3, 'Comfortable running shoes for kids'),
            (4, 'Beauty Collection', 1200, 'beauty.jpg', 2, 'Premium beauty products'),
            (5, 'Footwear Special', 1400, 'footware.jpg', 6, 'Latest footwear collection'),
            (6, 'New Season', 1500, 'newseason.jpg', 1, 'Trending new season collection'),
            (7, 'Premium Collection', 5000, 'banner1.jpg', 2, 'Exclusive premium collection')
        ");
    }
    
    $demo_mode = false;
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
