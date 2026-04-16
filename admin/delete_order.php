<?php
session_start();
include '../db.php'; // database connection

// Only admins allowed
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($order_id <= 0) {
    die("Invalid Order ID.");
}

// Delete items first (foreign key safe)
$stmt1 = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
$stmt1->bind_param('i', $order_id);
$stmt1->execute();

// Delete main order
$stmt2 = $conn->prepare("DELETE FROM orders WHERE id = ?");
$stmt2->bind_param('i', $order_id);
if ($stmt2->execute()) {
    header("Location: orders.php?msg=Order+deleted+successfully");
    exit();
} else {
    die("Error deleting order: " . $conn->error);
}