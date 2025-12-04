<?php
require_once 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$pdo = pdo_connect();
$user_id = $_SESSION['user']['user_id'];

$appointment_id = $_GET['appointment_id'] ?? null;

if (!$appointment_id) {
    die("Invalid appointment.");
}

$stmt = $pdo->prepare("SELECT * FROM appointments WHERE appointment_id = :id AND user_id = :uid");
$stmt->execute([
    ':id' => $appointment_id,
    ':uid' => $user_id
]);
$appt = $stmt->fetch();

if (!$appt) {
    die("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = intval($_POST['rating']);
    $comment = $_POST['comment'];

    $stmt = $pdo->prepare("
        INSERT INTO reviews (appointment_id, user_id, rating, comment)
        VALUES (:aid, :uid, :rating, :comment)
    ");

    $stmt->execute([
        ':aid' => $appointment_id,
        ':uid' => $user_id,
        ':rating' => $rating,
        ':comment' => $comment
    ]);

    header("Location: dashboard_user.php");
    exit;
}

require_once 'inc/header.php';
?>

<link rel="stylesheet" href="style.css">

<h2>Leave a Review</h2>

<form method="post">

    <label>Rating (1–5)
        <input type="number" name="rating" min="1" max="5" required>
    </label>

    <label>Comment
        <textarea name="comment" required></textarea>
    </label>

    <button class="btn">Submit Review</button>

</form>

<?php require_once 'inc/footer.php'; ?>
