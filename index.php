<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// If the user is an administrator, they can see all images
$is_admin = ($_SESSION['role'] == 'admin');

if ($is_admin) {
    $stmt = $pdo->query("SELECT * FROM images");
} else {
    $stmt = $pdo->query("SELECT * FROM images WHERE inappropriate = 0");
}

$images = $stmt->fetchAll();
?>

<h1>Image Catalog</h1>

<?php foreach ($images as $image): ?>
    <div>
        <img src="<?php echo $image['filename']; ?>" alt="Image" width="200" />
        <?php if ($is_admin): ?>
            <form method="post" action="inappropriate.php">
                <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>" />
                <button type="submit">Flag as Inappropriate</button>
            </form>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
