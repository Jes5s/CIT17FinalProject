<?php
require_once 'config.php';
if (!isset($_SESSION['user'])){ header('Location: login.php'); exit; }
$user = $_SESSION['user'];
$pdo = pdo_connect();
// upcoming
$stmt = $pdo->prepare('SELECT a.*, s.service_name FROM appointments a JOIN
services s ON a.service_id=s.service_id WHERE a.user_id=:uid AND
a.appointment_date >= CURDATE() ORDER BY a.appointment_date ASC');
$stmt->execute([':uid'=>$user['user_id']]);
$upcoming = $stmt->fetchAll();
// past
$stmt = $pdo->prepare('SELECT a.*, s.service_name FROM appointments a JOIN
services s ON a.service_id=s.service_id WHERE a.user_id=:uid AND
a.appointment_date < CURDATE() ORDER BY a.appointment_date DESC');
$stmt->execute([':uid'=>$user['user_id']]);
$past = $stmt->fetchAll();
require_once 'inc/header.php';
?>
<h2>My Dashboard</h2>
<section>
 <h3>Upcoming Appointments</h3>
<?php if($upcoming): foreach($upcoming as $a): ?>
 <div class="card">
 <p><?= htmlspecialchars($a['service_name']) ?> — <?=
$a['appointment_date'] ?> <?= $a['start_time'] ?></p>
 <form method="post" action="cancel_appointment.php"
style="display:inline">
 <input type="hidden" name="id" value="<?= $a['appointment_id'] ?>">
 <button class="btn outline">Cancel</button>
 </form>
 </div>
<?php endforeach; else: ?><p>No upcoming appointments.</p><?php endif; ?>
</section>
<section>
 <h3>Past Appointments</h3>
<?php if($past): foreach($past as $a): ?>
 <div class="card">
 <p><?= htmlspecialchars($a['service_name']) ?> — <?=
$a['appointment_date'] ?></p>
 <a class="btn" href="leave_review.php?appointment_id=<?=
$a['appointment_id'] ?>">Leave Review</a>
 </div>
<?php endforeach; else: ?><p>No past appointments.</p><?php endif; ?>
    </section>
<?php require_once 'inc/footer.php'; ?>