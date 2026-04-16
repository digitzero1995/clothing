<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    $rating = intval($_POST['rating']);
    $review = trim($_POST['review']);

    if ($product_id && $rating >= 1 && $rating <= 5 && $review !== '') {
        $stmt = $conn->prepare("INSERT INTO reviews (product_id, user_id, rating, review) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $product_id, $user_id, $rating, $review);
        if ($stmt->execute()) {
            header("Location: product.php?id=$product_id&review=success");
            exit();
        } else {
            $error = "Failed to submit review.";
        }
    } else {
        $error = "Please fill all fields properly.";
    }
}

if (isset($error)) {
    echo "<p style='color:red;'>$error</p>";
}
?>