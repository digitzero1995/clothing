<?php
session_start();
include '../db.php';

// Only admins allowed
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$order_id  = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$status_id = isset($_GET['status_id']) ? (int)$_GET['status_id'] : 0;

if ($order_id <= 0 || $status_id <= 0) {
    die("Invalid input.");
}

// Update order status
$stmt = $conn->prepare("UPDATE orders SET status_id = ? WHERE id = ?");
$stmt->bind_param('ii', $status_id, $order_id);

if ($stmt->execute()) {
    header("Location: orders.php?msg=Order+status+updated");
    exit();
} else {
    die("Error updating order status: " . $conn->error);
}
?>