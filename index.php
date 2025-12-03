<?php
require_once 'config.php';
require_once 'inc/header.php';
$pdo = pdo_connect();
// fetch some services for hero
$stmt = $pdo->query('SELECT * FROM services ORDER BY created_at DESC LIMIT 6');
$services = $stmt->fetchAll();
?>
<section class="hero">
    <h1>Your Wellness Journey Starts Here</h1>
    <p>Relax, recharge, renew.</p>
    <div class="cta">
        <a class="btn" href="<?= BASE_URL ?>/services.php">View Services</a>
        <a class="btn outline" href="<?= BASE_URL ?>/register.php">Create Account</a>
    </div>
</section>


<section>
    <link rel="stylesheet" href="style.css">
    <!--BG-->
    <div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
    </div>
    <h2>Popular Services</h2>
    <div class="grid">
        <?php foreach ($services as $s): ?>
            <div class="card">
                <h3><?= htmlspecialchars($s['service_name']) ?></h3>
                <p><?= htmlspecialchars(substr($s['description'], 0, 120)) ?></p>
                <p><strong>₱<?= number_format($s['price'], 2) ?></strong> &middot; <?= $s['duration'] ?> min</p>
                <a class="btn" href="<?= BASE_URL ?>/booking.php?service_id=<?= $s['service_id'] ?>">Book Now</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>


<?php require_once 'inc/footer.php'; ?>