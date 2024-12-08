<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taggy</title>
    <link rel="stylesheet" href="/taggy/public/assets/css/style.css">
</head>
<body>
<header>
    <a href="<?php echo isset($_SESSION['user_id']) ? '/taggy/public/home.php' : '/taggy/public/index.php'; ?>">
        <h1>Taggy</h1>
    </a>
    <nav>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="mypage.php">My Page</a>
            <a href="new_post.php">New Post</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="signup.php">Sign Up</a>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </nav>
</header>
<main>
