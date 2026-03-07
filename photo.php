<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$is_admin = ($_SESSION['role'] == 'admin');

if ($is_admin) {
    $stmt = $pdo->prepare("SELECT * FROM images WHERE id = ?");
} else {
    $stmt = $pdo->prepare("SELECT * FROM images WHERE id = ? AND inappropriate = 0");
}
$stmt->execute([$id]);
$image = $stmt->fetch();

if (!$image) { header("Location: index.php"); exit; }

$desc = $image['description'];
$decoded = json_decode($desc, true);
if (is_array($decoded)) {
    $caption = isset($decoded[$lang]) ? $decoded[$lang] : (isset($decoded['en']) ? $decoded['en'] : reset($decoded));
} else {
    $caption = $desc;
}
$img_url = htmlspecialchars($image['filename']);
?>
<?php include 'navbar.php'; ?>
<?php include 'lang_switcher.php'; ?>
<div class="page">
    <a href="index.php" class="back-link">← Back to Catalog</a>
    <div class="photo-detail">
        <div class="photo-main">
            <img id="fullimg" src="<?php echo $img_url; ?>" alt="<?php echo $t['image_alt']; ?>" />
        </div>
        <div class="photo-sidebar">
            <?php if ($caption): ?>
                <div class="caption-label">Gemini Caption</div>
                <div class="caption-text" style="font-size:15px; margin-bottom:24px;"><?php echo htmlspecialchars($caption); ?></div>
            <?php endif; ?>
            <div class="photo-actions">
                <a href="<?php echo $img_url; ?>" download class="btn-action btn-download">⬇ Download</a>
                <button class="btn-action btn-copy" onclick="copyImage()">⎘ Copy Image</button>
            </div>
            <div id="copy-msg" style="display:none; color:#2e7d32; font-size:13px; margin-top:10px;">Copied to clipboard!</div>
            <?php if ($is_admin): ?>
                <?php if ($image['inappropriate']): ?>
                    <div class="flagged-notice">🚫 This image is flagged and hidden from regular users.</div>
                <?php endif; ?>
                <form method="post" action="inappropriate.php" style="margin-top:16px;">
                    <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>" />
                    <input type="hidden" name="redirect" value="photo.php?id=<?php echo $image['id']; ?>" />
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
</div>
<script>
function copyImage() {
    var img = document.getElementById('fullimg');
    fetch(img.src)
        .then(function(r) { return r.blob(); })
        .then(function(blob) {
            var item = new ClipboardItem({'image/png': blob});
            return navigator.clipboard.write([item]);
        })
        .then(function() {
            var msg = document.getElementById('copy-msg');
            msg.style.display = 'block';
            setTimeout(function() { msg.style.display = 'none'; }, 2500);
        })
        .catch(function() {
            alert('Copy not supported in this browser. Use the Download button instead.');
        });
}
</script>
<style>
.back-link { color: #e94560; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-block; margin-bottom: 20px; }
.back-link:hover { text-decoration: underline; }
.photo-detail { display: flex; gap: 32px; align-items: flex-start; }
.photo-main { flex: 1; background: #111; border-radius: 12px; overflow: hidden; display: flex; align-items: center; justify-content: center; min-height: 400px; }
.photo-main img { max-width: 100%; max-height: 75vh; object-fit: contain; display: block; }
.photo-sidebar { width: 280px; flex-shrink: 0; }
.photo-actions { display: flex; flex-direction: column; gap: 10px; }
.btn-action { display: block; padding: 12px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; text-align: center; cursor: pointer; text-decoration: none; border: none; transition: background 0.2s; }
.btn-download { background: #1a1a2e; color: #fff; }
.btn-download:hover { background: #0f3460; }
.btn-copy { background: #e94560; color: #fff; }
.btn-copy:hover { background: #c73652; }
@media (max-width: 700px) { .photo-detail { flex-direction: column; } .photo-sidebar { width: 100%; } }
</style>
<?php include 'footer.php'; ?>
