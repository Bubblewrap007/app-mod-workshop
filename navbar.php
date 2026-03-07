<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<nav style="background:#333; padding:10px 16px; display:flex; align-items:center; gap:16px;">
    <a href="index.php" style="color:<?php echo $current==='index.php'?'#fff':'#aaa'; ?>; text-decoration:none; font-weight:<?php echo $current==='index.php'?'bold':'normal'; ?>;">📷 Catalog</a>
    <a href="upload.php" style="color:<?php echo $current==='upload.php'?'#fff':'#aaa'; ?>; text-decoration:none; font-weight:<?php echo $current==='upload.php'?'bold':'normal'; ?>;">⬆️ Upload</a>
    <?php if (isset($_SESSION['user_id'])): ?>
        <span style="margin-left:auto; color:#aaa; font-size:13px;">
            <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>
            &nbsp;|&nbsp;
            <a href="logout.php" style="color:#aaa;">Logout</a>
        </span>
    <?php endif; ?>
</nav>
