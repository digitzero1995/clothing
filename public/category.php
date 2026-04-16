<?php 
include '../config/db.php';
include '../includes/header.php';

// Get category from URL
$cat_name = isset($_GET['cat']) ? $_GET['cat'] : 'all';
$cat_name = htmlspecialchars($cat_name, ENT_QUOTES, 'UTF-8');

// Map categories to category IDs
$category_map = [
    'women' => 2,
    'men' => 1,
    'kids' => 3,
    'footwear' => 6,
    'home' => 4,  // Added
    'brands' => 5, // Added
    'sale' => 'all'
];

$cat_id = isset($category_map[$cat_name]) ? $category_map[$cat_name] : 'all';

// Fetch products based on mode
if ($demo_mode) {
    // Demo mode - filter from array
    if ($cat_id !== 'all' && $cat_id !== null) {
        $products = array_filter($DEMO_PRODUCTS, function($p) use ($cat_id) { return $p['category_id'] == $cat_id; });
    } else {
        $products = $DEMO_PRODUCTS;
    }
} else {
    // Database mode
    if($cat_id == 'all' || $cat_name == 'all' || $cat_name == 'sale') {
        $query = "SELECT * FROM products";
    } else {
        $cat_id = (int)$cat_id;
        $query = "SELECT * FROM products WHERE category_id = $cat_id";
    }
    
    $result = @mysqli_query($conn, $query);
    $products = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
    }
}
?>

<style>
    body { font-family: Arial, sans-serif; }
    .category-header { text-align: center; margin: 40px 0; }
    .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; padding: 20px; }
    .product-card { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.3s; }
    .product-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.2); }
    .product-image { width: 100%; height: 250px; object-fit: cover; border-radius: 8px; }
    .product-name { font-weight: bold; margin-top: 10px; }
    .product-price { color: #1abc9c; font-weight: bold; font-size: 18px; margin: 10px 0; }
    .btn-view { display: block; text-align: center; background: #1abc9c; color: white; padding: 10px; margin-top: 10px; border-radius: 5px; text-decoration: none; cursor: pointer; transition: 0.3s; }
    .btn-view:hover { background: #0a9478; }
    .no-products { text-align: center; padding: 40px; font-size: 18px; color: #666; }
</style>

<div class="category-header">
    <h2 style="font-size: 32px; color: #333; text-transform: uppercase;">📦 <?php echo $cat_name; ?> Collection</h2>
</div>

<div class="products-grid">
    <?php 
    if (!empty($products)) {
        foreach($products as $row) { 
    ?>
        <div class="product-card">
            <img src="assets/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="product-image" onerror="this.src='assets/placeholder.jpg'">
            <h3 class="product-name"><?php echo htmlspecialchars($row['name']); ?></h3>
            <p class="product-price">₹<?php echo number_format($row['price'], 2); ?></p>
            <a href="product_details.php?id=<?php echo (int)$row['id']; ?>" class="btn-view">View Details</a>
        </div>
    <?php 
        }
    } else {
        echo '<div class="no-products"><p>No products found in this category.</p></div>';
    }
    ?>
</div>

<?php include '../includes/foooter.php'; ?>

<?php include '../includes/foooter.php'; ?>