<?php
require_once 'config.php';
$pdo = pdo_connect();
$id = $_GET['id'] ?? null;
$stmt = $pdo->prepare('SELECT a.*, s.service_name FROM appointments a JOIN
services s ON a.service_id=s.service_id WHERE a.appointment_id=:id');
$stmt->execute([':id' => $id]);
$ap = $stmt->fetch();
require_once 'inc/header.php';
?>
<link rel="stylesheet" href="style.css">
<!--BG-->
<div>
    <div class="wave"></div>
    <div class="wave"></div>
    <div class="wave"></div>
</div>
<h2>Booking Confirmed</h2>
<?php if ($ap): ?>
    <div class="card">
        <p>Service: <?= htmlspecialchars($ap['service_name']) ?></p>
        <p>Date: <?= $ap['appointment_date'] ?> at <?= $ap['start_time'] ?></p>
        <p>Status: <?= $ap['status'] ?></p>
    </div>
<?php else: ?>
    <p>Booking not found.</p>
<?php endif; ?>
<?php require_once 'inc/footer.php'; ?>