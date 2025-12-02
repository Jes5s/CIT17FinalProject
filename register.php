<?php
require_once 'config.php';
$pdo = pdo_connect();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$pass = $_POST['password'];
if (!$name || !$email || !$pass) $errors[] = 'Name, email and password are required.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email.';
if (empty($errors)){
$hash = password_hash($pass, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO users (full_name,email,phone_number,password) VALUES (:n,:e,:p,:pw)');
$stmt->execute([':n'=>$name,':e'=>$email,':p'=>$phone,':pw'=>$hash]);
header('Location: login.php'); exit;
}
}
require_once 'inc/header.php';
?>
<h2>Create account</h2>
<?php if($errors): ?>
<div class="errors"><?= implode('<br>', $errors) ?></div>
<?php endif; ?>
<form method="post">
<label>Full name<input name="name"></label>
<label>Email<input name="email"></label>
<label>Phone<input name="phone"></label>
<label>Password<input type="password" name="password"></label>
<button class="btn">Register</button>
</form>
<?php require_once 'inc/footer.php'; ?>