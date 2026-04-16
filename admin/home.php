<?php
session_start();
include '../db.php';

// Redirect if not logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch counts for dashboard
$userCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
$categoryCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM categories"))['total'];
$productCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM products"))['total'];
$orderCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders"))['total'];
$couponCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM coupons"))['total']; // ✅ Added
// Fetch latest 5 news
$newsQuery = mysqli_query($conn, "SELECT * FROM news ORDER BY created_at DESC LIMIT 5");
$newsItems = mysqli_fetch_all($newsQuery, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="../style.css">
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
body { font-family: Arial, sans-serif; margin:0; padding:0; background:#1e1e2f; }

/* Header */
header { text-align:center; padding:25px; color:#fff; font-size:2.5em; font-weight:bold; text-shadow: 2px 2px 5px rgba(0,0,0,0.3); }

/* Dashboard Cards */
.container { display:flex; flex-wrap:wrap; justify-content:center; margin:40px 20px; gap:30px; }
.card { background: rgba(255,255,255,0.95); width:250px; height:150px; border-radius:20px; box-shadow:0 8px 25px rgba(0,0,0,0.2); display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center; cursor:pointer; transition: all 0.4s ease; }
.card:hover { transform: translateY(-10px) scale(1.05); box-shadow: 0 0 20px #00ffd5, 0 0 40px #00ffd5, 0 0 60px #00ffd5; }
.card i { font-size:40px; margin-bottom:12px; color:#007bff; transition: transform 0.3s, color 0.3s; }
.card:hover i { transform: rotate(10deg) scale(1.2); color:#00ffd5; }
.card span { font-size:18px; font-weight:bold; color:#333; }
.card p { margin:5px 0 0; font-size:16px; color:#555; }
a.card-link { text-decoration:none; }

/* News Section */
.news-container { flex-direction: column; max-width:850px; margin:auto; }
.news-container h2 { color:#fff; text-align:center; margin-bottom:20px; }
.news-container .card { width:100%; padding:20px; margin-bottom:20px; background: rgba(30,30,50,0.95); border-radius:15px; box-shadow:0 4px 15px rgba(0,0,0,0.2); flex-direction:column; transition: all 0.4s ease; }
.news-container .card:hover { transform: translateY(-5px) scale(1.03); box-shadow: 0 0 20px #00ffd5, 0 0 40px #00ffd5, 0 0 60px #00ffd5; }
.news-container .card i { color:#ff9800; margin-bottom:10px; }
.news-container .card:hover i { color:#00ffd5; }
.news-container small { color:#bbb; margin-top:8px; }

/* Logout Button */
.logout { display:block; width:150px; margin:30px auto 40px auto; text-align:center; padding:12px; background:#e74c3c; color:#fff; border-radius:10px; text-decoration:none; font-weight:bold; transition:all 0.4s ease; }
.logout:hover { transform:scale(1.1); box-shadow:0 0 15px #ff4b2b,0 0 30px #ff4b2b; }
</style>
</head>
<body>

<header>
Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?> (Admin)
</header>

<!-- Dashboard Buttons -->
<div class="container">
    <a href="users.php" class="card-link">
        <div class="card">
            <i class="fas fa-users"></i>
            <span>Manage Users</span>
            <p><?php echo $userCount; ?> Users</p>
        </div>
    </a>
    <a href="add_category.php" class="card-link">
        <div class="card">
            <i class="fas fa-folder-plus"></i>
            <span>Categories</span>
            <p><?php echo $categoryCount; ?> Categories</p>
        </div>
    </a>
    <a href="add_product.php" class="card-link">
        <div class="card">
            <i class="fas fa-box-open"></i>
            <span>Products</span>
            <p><?php echo $productCount; ?> Products</p>
        </div>
    </a>
    <a href="orders.php" class="card-link">
        <div class="card">
            <i class="fas fa-receipt"></i>
            <span>Orders</span>
            <p><?php echo $orderCount; ?> Orders</p>
        </div>
    </a>
    <!-- ✅ Coupons Button Added -->
    <a href="coupons.php" class="card-link">
        <div class="card">
            <i class="fas fa-tags"></i>
            <span>Coupons</span>
            <p><?php echo $couponCount; ?> Coupons</p>
        </div>
    </a>
</div>

<!-- News Section -->
<div class="container news-container">
    <h2>Latest News & Updates</h2>  
    <?php if(!empty($newsItems)) {
        foreach($newsItems as $news) { ?>
            <div class="card">
                <i class="fas fa-newspaper"></i>
                <span style="font-size:20px;color:#00ffd5;"><?php echo htmlspecialchars($news['title']); ?></span>
                <p style="color:#fff;"><?php echo htmlspecialchars($news['content']); ?></p>
                <small><?php echo date('d M Y, H:i', strtotime($news['created_at'])); ?></small>
            </div>
    <?php }} else { ?>
        <p style="text-align:center;color:#fff;">No news available.</p>
    <?php } ?>
</div>

<a href="logout.php" class="logout">Logout</a>

</body>
</html>