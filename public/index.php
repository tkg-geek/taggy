<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ' . __DIR__ . '/../public/home.php');
    exit;
}

include __DIR__ . '/../includes/header.php';
?>
<h1>Welcome to Taggy!</h1>
<p>A place to share and preserve memories with NFC tags.</p>
<a href="signup.php">Sign Up</a> | <a href="login.php">Login</a>
<?php include __DIR__ . '/../includes/footer.php'; ?>



