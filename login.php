<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit;
    } else {
        $login_error = $t['invalid_credentials'];
    }
}
?>
<link rel="stylesheet" href="style.css">
<?php include 'lang_switcher.php'; ?>
<div class="auth-wrap">
    <div class="auth-hero">
        <div class="hero-logo"><span class="hero-logo-icon">📷<span class="brand-flash">✦</span></span> <span class="hero-logo-text">Amarcord</span></div>
        <div class="hero-tagline"><?php echo htmlspecialchars($t['hero_tagline']); ?></div>
        <ul class="hero-features">
            <li><?php echo htmlspecialchars($t['hero_feature_1']); ?></li>
            <li><?php echo htmlspecialchars($t['hero_feature_2']); ?></li>
            <li><?php echo htmlspecialchars($t['hero_feature_3']); ?></li>
        </ul>
    </div>
    <div class="auth-card">
        <h2><?php echo htmlspecialchars($t['welcome_back']); ?></h2>
        <p class="subtitle"><?php echo htmlspecialchars($t['sign_in_subtitle']); ?></p>
        <?php if (isset($login_error)): ?>
            <div class="error"><?php echo htmlspecialchars($login_error); ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="username" placeholder="<?php echo $t['username']; ?>" required autofocus />
            <input type="password" name="password" placeholder="<?php echo $t['password']; ?>" required />
            <button type="submit"><?php echo $t['login']; ?></button>
        </form>
        <div class="alt-link"><?php echo htmlspecialchars($t['no_account']); ?> <a href="register.php"><?php echo $t['register']; ?></a></div>
    </div>
</div>
<?php include 'footer.php'; ?>
