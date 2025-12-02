<?php
require_once 'config.php';
require_once 'inc/header.php';
$pdo = pdo_connect();
// simple filters via GET
$where = [];
$params = [];
if (!empty($_GET['q'])) {
$where[] = 'service_name LIKE :q';
$params[':q'] = '%'.$_GET['q'].'%';
}
$sql = 'SELECT * FROM services' . (count($where) ? ' WHERE '.implode(' AND ', $where) : '') . ' ORDER BY created_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$services = $stmt->fetchAll();
?>
<h2>Services</h2>
<form method="get" class="filters">
<input type="text" name="q" placeholder="Search services" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
<button>Search</button>
</form>
<div class="grid">
<?php foreach($services as $s): ?>
<div class="card">
<h3><?= htmlspecialchars($s['service_name']) ?></h3>
<p><?= nl2br(htmlspecialchars($s['description'])) ?></p>
<p><strong>₱<?= number_format($s['price'],2) ?></strong> &middot; <?= $s['duration'] ?> min</p>
<a class="btn" href="<?= BASE_URL ?>/booking.php?service_id=<?= $s['service_id'] ?>">Book Now</a>
</div>
<?php endforeach; ?>
</div>


<?php require_once 'inc/footer.php'; ?>