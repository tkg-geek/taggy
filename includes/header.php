<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// HTML開始
?>
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
        </a>

        <!-- 検索バー -->
        <form action="home.php" method="GET" class="search-form">
            <input type="text" name="keyword" placeholder="Search for everyone's memories...">
            <!-- <button type="submit">🔍</button> -->
        </form>

        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- ユーザープロフィールアイコンと名前表示 -->
                <div class="profile-dropdown">
                    <a id="profile-btn" href="#">
                        <?php
                        $profileImage = !empty($_SESSION['profile_image']) ? htmlspecialchars($_SESSION['profile_image']) : '/taggy/public/assets/images/profile.jpg';
                        $username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest';
                        ?>
                        <img src="<?= $profileImage ?>" alt="Profile" class="profile-icon">
                        <span><?= $username ?></span>
                    </a>
                    <div class="dropdown-content">
                        <a href="mypage.php">Mypage</a>
                        <a href="profile.php">Profile</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
                </div>
            <?php else: ?>
                <a href="signup.php">Sign Up</a>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const profileBtn = document.getElementById('profile-btn');
                const dropdownContent = document.querySelector('.profile-dropdown .dropdown-content');

                if (profileBtn && dropdownContent) {
                    profileBtn.addEventListener('click', function() {
                        dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
                    });

                    // ドロップダウン外クリックで閉じる処理
                    document.addEventListener('click', function(e) {
                        if (!profileBtn.contains(e.target) && !dropdownContent.contains(e.target)) {
                            dropdownContent.style.display = 'none';
                        }
                    });
                }
            });
        </script>