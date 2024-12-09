<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../src/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    signup($_POST['username'], $_POST['email'], $_POST['password']);
    header('Location: /taggy/public/login.php');
    exit;
}

include __DIR__ . '/../includes/header.php';
?>
<div class="container">
    <div class="form-container">
        <h2>Sign Up</h2>
        <form method="POST">
            <label for="username">ユーザーネーム:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">パスワード:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">登録する</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>

