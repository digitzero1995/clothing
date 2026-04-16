<?php
session_start();
include '../config/db.php';

// Redirect to login if user not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle Add or Remove actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'add') {
        // Check if already in wishlist
        $checkSql = "SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?";
        $stmt = mysqli_prepare($conn, $checkSql);
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 0) {
            // Insert into wishlist
            $insertSql = "INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)";
            $stmtInsert = mysqli_prepare($conn, $insertSql);
            mysqli_stmt_bind_param($stmtInsert, "ii", $user_id, $product_id);
            mysqli_stmt_execute($stmtInsert);
        }
        header('Location: wishlist.php');
        exit;

    } elseif ($action === 'remove') {
        // Remove from wishlist
        $deleteSql = "DELETE FROM wishlist WHERE user_id = ? AND product_id = ?";
        $stmtDel = mysqli_prepare($conn, $deleteSql);
        mysqli_stmt_bind_param($stmtDel, "ii", $user_id, $product_id);
        mysqli_stmt_execute($stmtDel);
        header('Location: wishlist.php');
        exit;
    }
}

// Fetch wishlist items for this user
$sql = "SELECT p.* FROM products p
        INNER JOIN wishlist w ON p.id = w.product_id
        WHERE w.user_id = ?
        ORDER BY p.id DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$wishlist_items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['image'] = !empty($row['image']) ? "uploads/" . htmlspecialchars($row['image']) : "uploads/no-image.png";
    $row['price'] = is_numeric($row['price']) ? number_format($row['price'], 2) : "0.00";
    $wishlist_items[] = $row;
}

include 'header.php';
?>

<style>
.wishlist-container {
    max-width: 1000px;
    margin: 40px auto;
    padding: 0 20px;
}
.wishlist-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill,minmax(220px,1fr));
    gap: 25px;
}
.wishlist-card {
    background: #f9f9f9;
    border-radius: 12px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    text-align: center;
    padding: 15px;
    transition: box-shadow 0.3s ease;
}
.wishlist-card:hover {
    box-shadow: 0 6px 30px rgba(0,0,0,0.15);
}
.wishlist-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 10px;
}
.wishlist-card h3 {
    margin: 12px 0 8px;
    font-size: 1.1rem;
    color: #333;
}
.wishlist-card p {
    font-weight: bold;
    color: #555;
    margin-bottom: 10px;
}
.remove-btn {
    background: #dc3545;
    color: #fff;
    padding: 8px 18px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s ease;
}
.remove-btn:hover {
    background: #b02a37;
}
</style>

<div class="wishlist-container">
    <h2>Your Wishlist</h2>
    <?php if (count($wishlist_items) > 0): ?>
    <div class="wishlist-grid">
        <?php foreach ($wishlist_items as $item): ?>
            <div class="wishlist-card">
                <img src="<?php echo $item['image']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                <p>₹<?php echo $item['price']; ?></p>
                <form method="GET" action="wishlist.php">
                    <input type="hidden" name="action" value="remove">
                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                    <button type="submit" class="remove-btn">Remove</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
        <p style="text-align:center; font-size: 18px; color: #666;">Your wishlist is empty.</p>
    <?php endif; ?>
</div>

<?php include '../includes/foooter.php'; ?>