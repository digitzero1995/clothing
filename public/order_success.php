<?php require_once __DIR__ . '/../includes/header.php'; requireLogin(); ?>
<?php $id = (int)($_GET['id'] ?? 0); ?>
<div class="text-center py-5">
<h2>Order Placed!</h2>
<p>Your order #<?= $id ?> is now <span class="badge bg-secondary">Pending</span>.</p>
<a class="btn btn-primary" href="/public/index.php">Shop more</a>
</div>
<?php require_once __DIR__ . '/../includes/foooter.php'; ?>
