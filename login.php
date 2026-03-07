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
        <div class="hero-logo">📷 Amarcord</div>
        <div class="hero-tagline">The photo catalog that remembers everything —<br>and explains it in any language.</div>
        <ul class="hero-features">
            <li>🤖 AI-powered captions in 10 languages, instantly</li>
            <li>☁️ Cloud-native storage — your photos live forever</li>
            <li>🌍 Built for a global audience from day one</li>
        </ul>
    </div>
    <div class="auth-card">
        <h2>Welcome back</h2>
        <p class="subtitle">Sign in to your account</p>
        <?php if (isset($login_error)): ?>
            <div class="error"><?php echo htmlspecialchars($login_error); ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="username" placeholder="<?php echo $t['username']; ?>" required autofocus />
            <input type="password" name="password" placeholder="<?php echo $t['password']; ?>" required />
            <button type="submit"><?php echo $t['login']; ?></button>
        </form>
        <div class="alt-link">Don't have an account? <a href="register.php">Register</a></div>
    </div>
</div>
<?php include 'footer.php'; ?>
