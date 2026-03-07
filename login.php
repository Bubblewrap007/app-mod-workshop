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
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
    } else {
        echo $t['invalid_credentials'];
    }
}
?>
<?php include 'lang_switcher.php'; ?>

<form method="post">
    <input type="text" name="username" placeholder="<?php echo $t['username']; ?>" required />
    <input type="password" name="password" placeholder="<?php echo $t['password']; ?>" required />
    <button type="submit"><?php echo $t['login']; ?></button>
</form>
