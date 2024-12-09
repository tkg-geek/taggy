<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../src/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (login($_POST['email'], $_POST['password'])) {
        header('Location: /taggy/public/home.php');
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}

include __DIR__ . '/../includes/header.php';
?>
<div class="container">
    <div class="form-container">
        <h2>Login</h2>
        <form method="POST">
            <label>Email: <input type="email" name="email" required></label><br>
            <label>パスワード: <input type="password" name="password" required></label><br>
            <button type="submit">ログイン</button>
        </form>
    </div>
</div>

<?php if (isset($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>