<?php
require_once 'config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
header('Location: login.php'); exit;
}
$pdo = pdo_connect();
// recent bookings
$stmt = $pdo->query('SELECT a.*, s.service_name, u.full_name AS customer FROM
appointments a JOIN services s ON a.service_id=s.service_id JOIN users u ON
a.user_id=u.user_id ORDER BY a.created_at DESC LIMIT 50');
$bookings = $stmt->fetchAll();
require_once 'inc/header.php';
?>
<h2>Admin Dashboard</h2>
<section>
 <h3>Bookings</h3>
 <table class="table">
 <thead><tr><th>ID</th><th>Customer</th><th>Service</th><th>Date</
th><th>Start</th><th>Status</th></tr></thead>
 <tbody>
<?php foreach($bookings as $b): ?>
 <tr>
 <td><?= $b['appointment_id'] ?></td>
 <td><?= htmlspecialchars($b['customer']) ?></td>
 <td><?= htmlspecialchars($b['service_name']) ?></td>
 <td><?= $b['appointment_date'] ?></td>
 <td><?= $b['start_time'] ?></td>
 <td><?= $b['status'] ?></td>
 </tr>
<?php endforeach; ?>
 </tbody>
 </table>
</section>
<?php require_once 'inc/footer.php'; ?>
