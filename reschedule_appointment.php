<?php
require_once 'config.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$pdo = pdo_connect();
$user = $_SESSION['user'];

// GET appointment_id for form
$appointment_id = $_GET['appointment_id'] ?? null;
if (!$appointment_id) {
    header('Location: dashboard_user.php');
    exit;
}

// Load appointment & service
$stmt = $pdo->prepare("
    SELECT a.*, s.service_name, s.duration
    FROM appointments a
    JOIN services s ON a.service_id = s.service_id
    WHERE a.appointment_id = :id AND a.user_id = :uid
");
$stmt->execute([':id' => $appointment_id, ':uid' => $user['user_id']]);
$appt = $stmt->fetch();

if (!$appt) {
    die("Appointment not found or you do not have permission.");
}

// Handle POST (update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_date = $_POST['date'] ?? null;
    $new_time = $_POST['start_time'] ?? null;

    if (!$new_date || !$new_time) {
        $error = "Please choose a new date and time.";
    } else {
        // compute end_time using service duration (minutes)
        $duration = intval($appt['duration']);
        $end_time = date('H:i:s', strtotime($new_time) + ($duration * 60));

        $stmt = $pdo->prepare("UPDATE appointments SET appointment_date = :date, start_time = :start, end_time = :end_time, status = 'pending' WHERE appointment_id = :id");
        $stmt->execute([
            ':date' => $new_date,
            ':start' => $new_time,
            ':end_time' => $end_time,
            ':id' => $appointment_id
        ]);

        header('Location: dashboard_user.php');
        exit;
    }
}

require_once 'inc/header.php';
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/style.css">

<div class="container">
    <h2>Reschedule Appointment</h2>

    <div class="card">
        <p><strong>Service:</strong> <?= htmlspecialchars($appt['service_name']) ?></p>
        <p><strong>Current:</strong> <?= htmlspecialchars($appt['appointment_date']) ?> at
            <?= htmlspecialchars($appt['start_time']) ?>
        </p>

        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post">
            <label>New date
                <input type="date" name="date" min="<?= date('Y-m-d') ?>"
                    value="<?= htmlspecialchars($appt['appointment_date']) ?>" required>
            </label>

            <label>New start time
                <input type="time" name="start_time" value="<?= htmlspecialchars(substr($appt['start_time'], 0, 5)) ?>"
                    required>
            </label>

            <p>Duration: <?= intval($appt['duration']) ?> minutes</p>

            <button class="btn" type="submit">Save new time</button>
            <a class="btn outline" href="<?= BASE_URL ?>/dashboard_user.php">Cancel</a>
        </form>
    </div>
</div>

<?php require_once 'inc/footer.php'; ?>