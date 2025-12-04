<?php
require_once 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$pdo = pdo_connect();
$user_id = $_SESSION['user']['user_id'];

// Get all reviews for this user
$stmt = $pdo->prepare("
    SELECT r.*, s.service_name, a.appointment_date 
    FROM reviews r
    JOIN appointments a ON r.appointment_id = a.appointment_id
    LEFT JOIN services s ON a.service_id = s.service_id
    WHERE r.user_id = :uid
    ORDER BY r.created_at DESC
");
$stmt->execute([':uid' => $user_id]);

$reviews = $stmt->fetchAll();

require_once 'inc/header.php';
?>

<link rel='stylesheet' href='style.css'>

<h2>My Reviews</h2>

<?php if ($reviews): ?>
    <?php foreach ($reviews as $r): ?>
        <div class='card'>

            <p>
                <strong><?= htmlspecialchars($r['service_name'] ?? 'Unknown Service') ?></strong><br>
                Date: <?= $r['appointment_date'] ?><br>
                Rating: ⭐ <?= $r['rating'] ?>/5
            </p>

            <p><?= nl2br(htmlspecialchars($r['comment'])) ?></p>

            <small>Submitted: <?= $r['created_at'] ?></small>

        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No reviews submitted yet.</p>
<?php endif; ?>

<?php require_once 'inc/footer.php'; ?>
