<?php
include 'config.php';
include 'gcs.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $objectName = basename($_FILES['image']['name']);
    $tmpPath    = $_FILES['image']['tmp_name'];
    $mimeType   = $_FILES['image']['type'];

    if (gcs_upload($gcs_bucket, $objectName, $tmpPath, $mimeType)) {
        $publicUrl = gcs_public_url($gcs_bucket, $objectName);
        $stmt = $pdo->prepare("INSERT INTO images (user_id, filename) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $publicUrl]);
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
