<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $filename = 'uploads/' . basename($_FILES['image']['name']);
    if (copy($_FILES['image']['tmp_name'], $filename) && unlink($_FILES['image']['tmp_name'])) {
        $stmt = $pdo->prepare("INSERT INTO images (user_id, filename) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $filename]);
        $upload_success = $t['image_uploaded'];
    } else {
        $upload_error = $t['error_uploading'];
    }
}
?>
<?php include 'navbar.php'; ?>
<?php include 'lang_switcher.php'; ?>
<div class="page">
<h1><?php echo $t['upload_image']; ?></h1>
<div class="upload-card">
    <?php if (isset($upload_success)): ?>
        <div class="success-msg"><?php echo htmlspecialchars($upload_success); ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="image" accept="image/*" required />
        <button type="submit"><?php echo $t['upload_image']; ?></button>
    </form>
</div>
</div>
