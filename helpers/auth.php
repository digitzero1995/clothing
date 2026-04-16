<?php
function isLoggedIn(): bool {
return isset($_SESSION['user']);
}
function currentUser() {
return $_SESSION['user'] ?? null;
}
function isAdmin(): bool {
return isset($_SESSION['user']) && (int)$_SESSION['user']['is_admin'] === 1;
}
function requireLogin() {
if (!isLoggedIn()) {
header('Location: /clothing/auth/login.php');
exit;
}
}
function requireAdmin() {
if (!isAdmin()) {
header('Location: /clothing/auth/login.php');
exit;
}
}
?>