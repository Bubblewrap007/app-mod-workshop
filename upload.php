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
        echo $t['image_uploaded'];
    } else {
        echo $t['error_uploading'];
    }
}
?>
<?php include 'lang_switcher.php'; ?>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="image" required />
    <button type="submit"><?php echo $t['upload_image']; ?></button>
</form>
