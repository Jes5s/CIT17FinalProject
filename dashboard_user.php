<?php
require_once 'config.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];
$pdo = pdo_connect();

// Status filter from dropdown (GET)
$status = $_GET['status'] ?? 'all';
$allowed_status = ['all', 'pending', 'confirmed', 'completed', 'canceled'];
if (!in_array($status, $allowed_status))
    $status = 'all';

/* -------------------------
   UPCOMING APPOINTMENTS
   (exclude canceled)
-------------------------- */

$upcoming_sql = "
    SELECT a.*, s.service_name
    FROM appointments a
    LEFT JOIN services s ON a.service_id = s.service_id
    WHERE a.user_id = :uid
      AND a.appointment_date >= CURDATE()
      AND a.status != 'canceled'
";

$upcoming_params = [':uid' => $user['user_id']];
if ($status !== 'all') {
    $upcoming_sql .= " AND a.status = :status";
    $upcoming_params[':status'] = $status;
}
$upcoming_sql .= " ORDER BY a.appointment_date ASC";

$stmt = $pdo->prepare($upcoming_sql);
$stmt->execute($upcoming_params);
$upcoming = $stmt->fetchAll();

/* -------------------------
   PAST APPOINTMENTS
   (include canceled OR past dates)
-------------------------- */

$past_sql = "
    SELECT a.*, s.service_name
    FROM appointments a
    LEFT JOIN services s ON a.service_id = s.service_id
    WHERE a.user_id = :uid
";

$past_params = [':uid' => $user['user_id']];

if ($status === 'all') {
    $past_sql .= " AND (a.appointment_date < CURDATE() OR a.status = 'canceled')";
} else {
    $past_sql .= " AND a.status = :status";
    $past_params[':status'] = $status;
}

$past_sql .= " ORDER BY a.appointment_date DESC";

$stmt = $pdo->prepare($past_sql);
$stmt->execute($past_params);
$past = $stmt->fetchAll();

require_once 'inc/header.php';
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/style.css">

<script>
    function onStatusChange() {
        document.getElementById('statusFilterForm').submit();
    }
</script>

<div>
    <div class="wave"></div>
    <div class="wave"></div>
    <div class="wave"></div>
</div>

<div class="container">
    <h2>My Dashboard</h2>

    <!-- Filter -->
    <form id="statusFilterForm" method="get" style="margin-bottom: 16px;">
        <label for="statusFilter">Filter by status: </label>
        <select id="statusFilter" name="status" onchange="onStatusChange()">
            <option value="all" <?= $status === 'all' ? 'selected' : '' ?>>All</option>
            <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="confirmed" <?= $status === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
            <option value="completed" <?= $status === 'completed' ? 'selected' : '' ?>>Completed</option>
            <option value="canceled" <?= $status === 'canceled' ? 'selected' : '' ?>>Canceled</option>
        </select>
    </form>

    <!-- UPCOMING -->
    <section>
        <h3>Upcoming Appointments</h3>

        <?php if (!empty($upcoming)): ?>
            <?php foreach ($upcoming as $a): ?>
                <div class="card">
                    <p>
                        <strong><?= htmlspecialchars($a['service_name'] ?? 'Unknown Service') ?></strong><br>
                        Date: <?= htmlspecialchars($a['appointment_date']) ?> |
                        Time: <?= htmlspecialchars($a['start_time']) ?><br>
                        Status: <span class="label"><?= ucfirst($a['status']) ?></span>
                    </p>

                    <div class="card-actions">
                        <form method="post" action="cancel_appointment.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $a['appointment_id'] ?>">
                            <button class="btn outline" type="submit">Cancel</button>
                        </form>

                        <a class="btn"
                            href="reschedule_appointment.php?appointment_id=<?= $a['appointment_id'] ?>">Reschedule</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No upcoming appointments.</p>
        <?php endif; ?>
    </section>

    <!-- PAST -->
    <section>
        <h3>Past Appointments</h3>

        <?php if (!empty($past)): ?>
            <?php foreach ($past as $a): ?>
                <div class="card">
                    <p>
                        <strong><?= htmlspecialchars($a['service_name'] ?? 'Unknown Service') ?></strong><br>
                        Date: <?= htmlspecialchars($a['appointment_date']) ?> |
                        Time: <?= htmlspecialchars($a['start_time']) ?><br>

                        Status:
                        <?php if ($a['status'] === 'canceled'): ?>
                            <span class="label label-canceled">Canceled</span>
                        <?php else: ?>
                            <span class="label"><?= ucfirst($a['status']) ?></span>
                        <?php endif; ?>
                    </p>

                    <div class="card-actions">
                        <!-- Leave review only if date is past -->
                        <?php if ($a['appointment_date'] < date('Y-m-d')): ?>
                            <a class="btn" href="leave_review.php?appointment_id=<?= $a['appointment_id'] ?>">Leave Review</a>
                        <?php endif; ?>

                        <!-- Remove (delete) -->
                        <form method="post" action="remove_appointment.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $a['appointment_id'] ?>">
                            <button class="btn outline danger" type="submit">Remove</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No past appointments.</p>
        <?php endif; ?>
    </section>

</div>

<?php require_once 'inc/footer.php'; ?>