<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    try {
        $stmt->execute([$username, $password]);
        echo $t['registration_successful'];
    } catch (PDOException $e) {
        echo $t['error'] . ": " . $e->getMessage();
    }
}
?>
<link rel="stylesheet" href="style.css">
<?php include 'lang_switcher.php'; ?>
<div class="auth-wrap">
    <div class="auth-card">
        <h2>Create account</h2>
        <p class="subtitle">Join the catalog</p>
        <form method="post">
            <input type="text" name="username" placeholder="<?php echo $t['username']; ?>" required autofocus />
            <input type="password" name="password" placeholder="<?php echo $t['password']; ?>" required />
            <button type="submit"><?php echo $t['register']; ?></button>
        </form>
        <div class="alt-link">Already have an account? <a href="login.php">Sign in</a></div>
    </div>
</div>
