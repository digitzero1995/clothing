<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

// Check if a specific product ID is requested
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id > 0) {
    // Single product view
    $product = null;
    
    if ($demo_mode) {
        // Find product from demo data
        foreach ($DEMO_PRODUCTS as $p) {
            if ($p['id'] == $product_id) {
                $product = $p;
                break;
            }
        }
    } else {
        // Database mode
        $query = "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = $product_id";
        $result = @mysqli_query($conn, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $product = mysqli_fetch_assoc($result);
        }
    }
    
    if ($product) {
        
        // Handle add to cart
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            $qty = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
            $_SESSION['cart'][$product_id] = ($_SESSION['cart'][$product_id] ?? 0) + $qty;
            $_SESSION['cart_message'] = "✅ Product added to cart!";
            
            // Auto redirect to cart after adding
            header("Location: cart.php");
            exit();
        }
        
        ?>
        <style>
            .product-detail-container { max-width: 1200px; margin: 30px auto; display: grid; grid-template-columns: 1fr 1fr; gap: 40px; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
            .product-image { width: 100%; max-height: 500px; object-fit: cover; border-radius: 8px; }
            .product-detail h1 { font-size: 32px; color: #333; margin: 0 0 15px; }
            .product-category { color: #999; font-size: 14px; margin-bottom: 10px; }
            .product-detail .price { font-size: 28px; color: #1abc9c; font-weight: bold; margin: 15px 0; }
            .product-description { color: #666; line-height: 1.6; margin: 20px 0; }
            .quantity-selector { display: flex; align-items: center; gap: 10px; margin: 20px 0; }
            .quantity-selector input { width: 60px; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
            .btn-add-cart { background: #1abc9c; color: white; border: none; padding: 15px 30px; font-size: 16px; border-radius: 4px; cursor: pointer; transition: 0.3s; }
            .btn-add-cart:hover { background: #0a9478; }
            .btn-back { background: #666; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; margin-bottom: 20px; }
            @media (max-width: 768px) { .product-detail-container { grid-template-columns: 1fr; gap: 20px; } }
        </style>
        
        <div class="product-detail-container">
            <div>
                <img src="assets/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image" onerror="this.src='assets/placeholder.jpg'">
            </div>
            
            <div class="product-detail">
                <a href="javascript:history.back()" class="btn-back">← Back</a>
                <p class="product-category"><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></p>
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <p class="price">₹<?php echo number_format($product['price'], 2); ?></p>
                <p class="product-description"><?php echo htmlspecialchars($product['description'] ?? 'No description available'); ?></p>
                
                <form method="POST">
                    <div class="quantity-selector">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="10" required>
                    </div>
                    <button type="submit" name="add_to_cart" class="btn-add-cart">🛒 Add to Cart</button>
                </form>
            </div>
        </div>
        <?php
        include '../includes/foooter.php';
        exit();
    }
}

// If no valid product ID or product not found, show products listing instead
// --- Pagination setup ---
$limit = 8;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

$selectedCategory = isset($_GET['category']) ? intval($_GET['category']) : 0;

// Fetch products for listing
$products = [];
$categories = [];
$totalPages = 1;

if ($demo_mode) {
    // Demo mode
    $categories = $DEMO_CATEGORIES;
    
    if ($selectedCategory > 0) {
        $products = array_filter($DEMO_PRODUCTS, function($p) use ($selectedCategory) {
            return $p['category_id'] == $selectedCategory;
        });
    } else {
        $products = $DEMO_PRODUCTS;
    }
    
    $totalProducts = count($products);
    $totalPages = ceil($totalProducts / $limit);
    $products = array_slice($products, $offset, $limit);
} else if ($conn) {
    // Database mode
    $categories_result = @mysqli_query($conn, "SELECT DISTINCT c.id, c.name FROM categories c INNER JOIN products p ON p.category_id = c.id ORDER BY c.name ASC");
    if ($categories_result) {
        while ($cat = mysqli_fetch_assoc($categories_result)) {
            $categories[] = $cat;
        }
    }
    
    // Count total products
    $countSql = "SELECT COUNT(*) AS total FROM products p " . ($selectedCategory > 0 ? "WHERE p.category_id = $selectedCategory" : "");
    $countRes = @mysqli_query($conn, $countSql);
    $countRow = $countRes ? mysqli_fetch_assoc($countRes) : null;
    $totalProducts = isset($countRow['total']) ? $countRow['total'] : 0;
    $totalPages = ceil($totalProducts / $limit);
    
    // Fetch products
    $sql = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id";
    if ($selectedCategory > 0) {
        $sql .= " WHERE p.category_id = $selectedCategory";
    }
    $sql .= " ORDER BY p.id DESC LIMIT $limit OFFSET $offset";
    $result = @mysqli_query($conn, $sql);
    if ($result) {
        while ($p = mysqli_fetch_assoc($result)) {
            $products[] = $p;
        }
    }
}


// Helper: render star icons
function renderStars($avg) {
    $avg = floatval($avg);
    $full = floor($avg);
    $half = ($avg - $full >= 0.5) ? 1 : 0;
    $empty = 5 - $full - $half;

    $html = '';
    for ($i = 0; $i < $full; $i++) { $html .= '<i class="fa-solid fa-star"></i>'; }
    if ($half) { $html .= '<i class="fa-solid fa-star-half-stroke"></i>'; }
    for ($i = 0; $i < $empty; $i++) { $html .= '<i class="fa-regular fa-star"></i>'; }
    return $html;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Products</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f9f9f9; }
    .container { display: flex; margin: 20px; gap: 20px; }
    .filter { width: 220px; background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); position: sticky; top: 80px; height: fit-content; }
    .filter h3 { margin-bottom: 15px; color: #333; }
    .filter ul { list-style: none; padding: 0; }
    .filter ul li { margin-bottom: 10px; }
    .filter ul li a { text-decoration: none; color: #444; padding: 8px 12px; display: block; border-radius: 8px; transition: all 0.3s; }
    .filter ul li a:hover, .filter ul li a.active { background: #1abc9c; color: white; transform: translateX(5px); }
    .products { flex: 1; display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
    .product-card { background: white; border-radius: 15px; padding: 15px; text-align: center; box-shadow: 0 6px 15px rgba(0,0,0,0.1); transition: transform 0.3s, box-shadow 0.3s; }
    .product-card:hover { transform: translateY(-8px); box-shadow: 0 12px 25px rgba(0,0,0,0.2); }
    .product-card img { width: 100%; height: auto; max-height: 250px; object-fit: cover; border-radius: 12px; margin-bottom: 10px; }
    .product-card h4 { margin: 10px 0; color: #333; }
    .product-card .category { color: #777; font-size: 14px; }
    .product-card .price { color: #1abc9c; font-size: 18px; font-weight: bold; margin: 10px 0; }
    .btn { display: inline-block; padding: 8px 14px; margin: 5px 2px; border-radius: 8px; text-decoration: none; font-size: 14px; transition: all 0.3s ease; }
    .btn:hover { opacity: 0.85; }
    .btn-primary { background: #1abc9c; color: white; }
    .pagination { text-align: center; margin: 30px 0; }
    .pagination a { display: inline-block; padding: 8px 14px; margin: 3px; border-radius: 6px; text-decoration: none; color: #333; border: 1px solid #ccc; transition: all 0.3s; }
    .pagination a:hover { background: #1abc9c; color: white; border-color: #1abc9c; }
    .pagination a.active { background: #1abc9c; color: white; border-color: #1abc9c; }
    @media (max-width: 768px) { .products { grid-template-columns: repeat(2, 1fr); } .filter { display: none; } }
  </style>
</head>
<body>

  <div class="container">
    <?php if (!empty($categories)): ?>
    <!-- Sidebar -->
    <aside class="filter">
      <h3>Filter by Category</h3>
      <ul>
        <li><a href="?category=0" class="<?php echo $selectedCategory==0?'active':''; ?>">All</a></li>
        <?php foreach ($categories as $cat) { ?>
          <li><a href="?category=<?php echo $cat['id']; ?>" class="<?php echo $selectedCategory==$cat['id']?'active':''; ?>"><?php echo htmlspecialchars($cat['name']); ?></a></li>
        <?php } ?>
      </ul>
    </aside>
    <?php endif; ?>

    <!-- Products -->
    <section class="products">
      <?php 
      if (!empty($products)) {
        foreach ($products as $p) { 
          $category_name = $p['category_name'] ?? 'Uncategorized';
    ?>
        <div class="product-card">
          <img src="assets/<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" onerror="this.src='assets/placeholder.jpg'">
          <h4><?php echo htmlspecialchars($p['name']); ?></h4>
          <p class="category"><?php echo htmlspecialchars($category_name); ?></p>
          <p class="price">₹<?php echo number_format($p['price'], 2); ?></p>
          <a href="product_details.php?id=<?php echo $p['id']; ?>" class="btn btn-primary">View Details</a>
        </div>
      <?php }
      } else {
        echo '<p style="padding: 20px; text-align: center;">No products found.</p>';
      }
      ?>
    </section>
  </div>

  <!-- Pagination -->
  <div class="pagination">
    <?php
      if ($totalPages > 1) {
        $queryString = "&category=$selectedCategory";
        for ($i = 1; $i <= $totalPages; $i++) {
          $active = $i == $page ? "active" : "";
          echo "<a class='$active' href='?page=$i$queryString'>$i</a>";
        }
      }
    ?>
  </div>

</body>
</html>
<?php include '../includes/foooter.php'; ?>