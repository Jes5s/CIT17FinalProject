<?php
require_once 'config.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'] ?? null;
    if (!$id)
        die("Invalid ID.");

    $user_id = $_SESSION['user']['user_id'];
    $pdo = pdo_connect();

    // Verify appointment belongs to user
    $stmt = $pdo->prepare("SELECT * FROM appointments WHERE appointment_id = :id AND user_id = :uid");
    $stmt->execute([':id' => $id, ':uid' => $user_id]);
    if (!$stmt->fetch())
        die("Unauthorized.");

    // Delete appointment permanently
    $stmt = $pdo->prepare("DELETE FROM appointments WHERE appointment_id = :id");
    $stmt->execute([':id' => $id]);

    header("Location: dashboard_user.php");
    exit;
} else {
    echo "Invalid request.";
}
