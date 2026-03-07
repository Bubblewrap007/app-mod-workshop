<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// If the user is an administrator, they can see all images
$is_admin = ($_SESSION['role'] == 'admin');

if ($is_admin) {
    $stmt = $pdo->query("SELECT * FROM images ORDER BY id DESC");
} else {
    $stmt = $pdo->query("SELECT * FROM images WHERE inappropriate = 0 ORDER BY id DESC");
}

$images = $stmt->fetchAll();
?>
<?php include 'navbar.php'; ?>
<?php include 'lang_switcher.php'; ?>
<div class="page">
<h1><?php echo $t['image_catalog']; ?></h1>
<div class="image-grid">
<?php foreach ($images as $image): ?>
    <div class="image-card">
        <a href="photo.php?id=<?php echo $image['id']; ?>">
            <img src="<?php echo htmlspecialchars($image['filename']); ?>" alt="<?php echo $t['image_alt']; ?>" />
        </a>
        <div class="card-body">
        <?php if (!empty($image['description'])): ?>
            <?php
            $desc = $image['description'];
            $decoded = json_decode($desc, true);
            if (is_array($decoded)) {
                $caption = isset($decoded[$lang]) ? $decoded[$lang] : (isset($decoded['en']) ? $decoded['en'] : reset($decoded));
            } else {
                $caption = $desc;
            }
            ?>
            <div class="caption-label"><span class="gemini-star">✦</span> Gemini Caption</div>
            <div class="caption-text"><?php echo htmlspecialchars($caption); ?></div>
        <?php endif; ?>
        <?php if ($is_admin): ?>
            <?php if ($image['inappropriate']): ?>
                <div class="flagged-badge">🚫 Hidden from users</div>
            <?php endif; ?>
            <form method="post" action="inappropriate.php">
                <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>" />
                <?php if ($image['inappropriate']): ?>
                    <input type="hidden" name="action" value="unflag" />
                    <button type="submit" class="flag-btn unflag-btn">✓ Restore Image</button>
                <?php else: ?>
                    <input type="hidden" name="action" value="flag" />
                    <button type="submit" class="flag-btn"><?php echo $t['flag_inappropriate']; ?></button>
                <?php endif; ?>
            </form>
        <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
</div>
</div>
<?php include 'footer.php'; ?>
