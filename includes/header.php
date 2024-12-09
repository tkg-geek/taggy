<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="モノに想いを。想いをモノに。 | TAGGY" content="あなたのモノの物語を紡ぐ、新しい思い出の形。
    TAGGYは、あなたの大切なモノに新しい命を吹き込むサービスです。TAGGYタグを使って、モノの履歴と思い出を簡単にデジタル保存できます。">
    <title>TAGGY</title>
    <link rel="stylesheet" href="/taggy/public/assets/css/style.css">
    <link rel="icon" type="image/png" href="/taggy/public/assets/images/apple-touch-icon.png">
    <link rel="apple-touch-icon" type="image/png" href="/taggy/public/assets/images/android-crhome-192x192.png">
    <link rel="icon" href="/taggy/public/assets/images/favicon.ico">
</head>
<body>
<header>
    <a href="<?php echo isset($_SESSION['user_id']) ? '/taggy/public/home.php' : '/taggy/public/index.php'; ?>">
        <h1>TAGGY</h1>
        <!-- <img src="/taggy/public/assets/images/taggy_logo.svg" alt="TAGGY Logo" class=""> -->
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
