<?php
require_once 'config.php';
$pdo = pdo_connect();

// Fetch selected service
$service = null;
if (!empty($_GET['service_id'])) {
    $stmt = $pdo->prepare('SELECT * FROM services WHERE service_id = :id');
    $stmt->execute([':id' => $_GET['service_id']]);
    $service = $stmt->fetch();
}

// When form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // User must be logged in
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }

    $user = $_SESSION['user'];
    $service_id = $_POST['service_id'];
    $date = $_POST['date'];
    $start = $_POST['start_time'];
    $duration = intval($_POST['duration']);

    // Calculate end time
    $end_time = date('H:i:s', strtotime($start) + ($duration * 60));

    /* 
        Basic conflict check can be added later.
        For now, we simply store the booking.
    */

    $stmt = $pdo->prepare(
        'INSERT INTO appointments 
        (user_id, service_id, appointment_date, start_time, end_time, status) 
        VALUES 
        (:uid, :sid, :d, :s, :e, :st)'
    );

    $stmt->execute([
        ':uid' => $user['user_id'],
        ':sid' => $service_id,
        ':d' => $date,
        ':s' => $start,
        ':e' => $end_time,
        ':st' => 'pending'
    ]);

    $appointment_id = $pdo->lastInsertId();
    header('Location: booking_success.php?id=' . $appointment_id);
    exit;
}

require_once 'inc/header.php';
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/style.css">

<!-- Background Waves -->
<div>
    <div class="wave"></div>
    <div class="wave"></div>
    <div class="wave"></div>
</div>

<h2>Book Service</h2>

<?php if ($service): ?>
    <div class="card">
        <h3><?= htmlspecialchars($service['service_name']) ?></h3>
        <p><?= htmlspecialchars($service['description']) ?></p>
        <p><strong>Duration:</strong> <?= $service['duration'] ?> min</p>
        <p><strong>Price:</strong> ₱<?= number_format($service['price'], 2) ?></p>
    </div>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="service_id" value="<?= $service['service_id'] ?? '' ?>">
    <input type="hidden" name="duration" value="<?= $service['duration'] ?? 60 ?>">

    <label>Date
        <input type="date" name="date" required>
    </label>

    <label>Start Time
        <input type="time" name="start_time" required>
    </label>

    <button class="btn">Confirm Booking</button>
</form>

<?php require_once 'inc/footer.php'; ?>