<?php
require_once __DIR__ . '/../config.php';
// helper to check logged in
function is_logged_in(){
return isset($_SESSION['user']);
}
function current_user(){
return $_SESSION['user'] ?? null;
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Wellness Booking</title>
<link rel="stylesheet" href="/wellness/assets/styles.css">
</head>
<body>
<header class="site-header">
<div class="container">
<a class="brand" href="<?= BASE_URL ?>">Wellness</a>
<nav>
<a href="<?= BASE_URL ?>">Home</a>
<a href="<?= BASE_URL ?>/services.php">Services</a>
<?php if(is_logged_in()): ?>
<a href="<?= BASE_URL ?>/dashboard_user.php">Dashboard</a>
<a href="<?= BASE_URL ?>/logout.php">Logout</a>
<?php else: ?>
<a href="<?= BASE_URL ?>/login.php">Login</a>
<a href="<?= BASE_URL ?>/register.php">Register</a>
<?php endif; ?>
</nav>
</div>
</header>
<main class="container">