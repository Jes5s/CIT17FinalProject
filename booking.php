<?php
require_once 'config.php';
$pdo = pdo_connect();
$service = null;
if (!empty($_GET['service_id'])){
$stmt = $pdo->prepare('SELECT * FROM services WHERE service_id=:id');
$stmt->execute([':id'=>$_GET['service_id']]);
$service = $stmt->fetch();
}
// if booking submission
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
if (!isset($_SESSION['user'])){ header('Location: login.php'); exit; }
$user = $_SESSION['user'];
$service_id = $_POST['service_id'];
$date = $_POST['date'];
$start = $_POST['start_time'];
$duration = intval($_POST['duration']);
$end_time = date('H:i:s', strtotime($start) + $duration*60);
// basic conflict check: same therapist not assigned here, just store without
therapist
$stmt = $pdo->prepare('INSERT INTO appointments
(user_id,service_id,appointment_date,start_time,end_time,status) VALUES
(:uid,:sid,:d,:s,:e,:st)');
$stmt->execute([
':uid'=>$user['user_id'],
':sid'=>$service_id,
':d'=>$date,
':s'=>$start,
':e'=>$end_time,
':st'=>'pending'
]);
$appointment_id = $pdo->lastInsertId();
header('Location: booking_success.php?id='.$appointment_id); exit;
}
require_once 'inc/header.php';
?>
<h2>Book Service</h2>
<?php if($service): ?>
 <div class="card">
 <h3><?= htmlspecialchars($service['service_name']) ?></h3>
 <p><?= htmlspecialchars($service['description']) ?></p>
 <p><strong>Duration:</strong> <?= $service['duration'] ?> min</p>
 <p><strong>Price:</strong> ₱<?= number_format($service['price'],2) ?></p>
 </div>
<?php endif; ?>
<form method="post">
 <input type="hidden" name="service_id" value="<?= $service['service_id'] ??
'' ?>">
 <label>Date<input type="date" name="date" required></label>
 <label>Start time<input type="time" name="start_time" required></label>
 <input type="hidden" name="duration" value="<?= $service['duration'] ?? 60 ?
>">
 <button class="btn">Confirm Booking</button>
</form>
<?php require_once 'inc/footer.php'; ?>
