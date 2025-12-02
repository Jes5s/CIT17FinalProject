<?php
require_once 'config.php';
$pdo = pdo_connect();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
$email = trim($_POST['email']);
$pass = $_POST['password'];
$stmt = $pdo->prepare('SELECT * FROM users WHERE email=:e LIMIT 1');
$stmt->execute([':e'=>$email]);
$user = $stmt->fetch();
if ($user && password_verify($pass, $user['password'])){
unset($user['password']);
$_SESSION['user'] = $user;
// redirect to dashboard
header('Location: dashboard_user.php'); exit;
} else {
$errors[] = 'Invalid email or password';
}
}
require_once 'inc/header.php';
?>
<h2>Login</h2>
<?php if($errors): ?><div class="errors"><?= implode('<br>',$errors) ?></div><?php endif; ?>
<form method="post">
<label>Email<input name="email"></label>
<label>Password<input type="password" name="password"></label>
<button class="btn">Login</button>
</form>
<?php require_once 'inc/footer.php'; ?>