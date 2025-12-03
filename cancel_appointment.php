<?php
require_once 'config.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'] ?? null;
    if (!$id)
        die("Invalid appointment ID.");

    $user_id = $_SESSION['user']['user_id'];
    $pdo = pdo_connect();

    // Verify ownership
    $stmt = $pdo->prepare("SELECT * FROM appointments WHERE appointment_id = :id AND user_id = :uid");
    $stmt->execute([':id' => $id, ':uid' => $user_id]);
    $appt = $stmt->fetch();

    if (!$appt)
        die("Unauthorized.");

    // Mark as canceled
    $stmt = $pdo->prepare("UPDATE appointments SET status = 'canceled' WHERE appointment_id = :id");
    $stmt->execute([':id' => $id]);

    header("Location: dashboard_user.php");
    exit;
} else {
    echo "Invalid request.";
}
