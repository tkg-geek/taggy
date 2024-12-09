<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../src/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    signup($_POST['username'], $_POST['email'], $_POST['password']);
    header('Location: ' . __DIR__ . '/../public/login.php');
    exit;
}

include __DIR__ . '/../includes/header.php';
?>
<h2>Sign Up</h2>
<form method="POST">
    <label>Username: <input type="text" name="username" required></label><br>
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <button type="submit">Sign Up</button>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>
