<?php $current = basename($_SERVER['PHP_SELF']); ?>
<link rel="stylesheet" href="style.css">
<nav>
    <span class="brand"><span class="brand-icon">📷</span> <span class="brand-text">Amarcord</span></span>
    <a href="index.php" <?php if ($current==='index.php') echo 'class="active"'; ?>>Catalog</a>
    <a href="upload.php" <?php if ($current==='upload.php') echo 'class="active"'; ?>>Upload</a>
    <span class="spacer"></span>
    <?php if (isset($_SESSION['user_id'])): ?>
        <span class="user-info">
            <?php echo htmlspecialchars(isset($_SESSION['username']) ? $_SESSION['username'] : ''); ?>
            &nbsp;|&nbsp;<a href="logout.php">Logout</a>
        </span>
    <?php endif; ?>
</nav>
