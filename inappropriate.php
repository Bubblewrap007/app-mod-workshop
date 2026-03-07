<?php
include 'config.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $image_id = (int)$_POST['image_id'];
    $action   = isset($_POST['action']) ? $_POST['action'] : 'flag';
    if ($action === 'unflag') {
        $stmt = $pdo->prepare("UPDATE images SET inappropriate = 0 WHERE id = ?");
    } else {
        $stmt = $pdo->prepare("UPDATE images SET inappropriate = 1 WHERE id = ?");
    }
    $stmt->execute([$image_id]);
    $redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 'index.php';
    header("Location: " . $redirect);
    exit;
}
?>
