<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../src/post.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /taggy/public/index.php');
    exit;
}

// ユーザー情報を取得してセッションに格納する
$posts = getPosts();
$username = null;

foreach ($posts as $post) {
    if ($post['user_id'] == $_SESSION['user_id']) {
        $username = $post['username'];
        break;
    }
}

if ($username) {
    $_SESSION['username'] = $username;
}

// 検索ワードの取得
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$filteredPosts = [];

// 検索ワードがある場合にフィルタリング
if ($keyword) {
    foreach ($posts as $post) {
        // キーワードがタイトル、説明、ユーザー名に含まれているかチェック
        if (
            stripos($post['title'], $keyword) !== false ||
            stripos($post['description'], $keyword) !== false ||
            stripos($post['username'], $keyword) !== false
        ) {
            $filteredPosts[] = $post;
        }
    }
} else {
    $filteredPosts = $posts;
}

include __DIR__ . '/../includes/header.php';
?>

<div class="card-container">
    <?php foreach ($filteredPosts as $post): ?>
        <?php
        if ($post['visibility'] == 2 ||
            ($post['visibility'] == 1 && isset($_GET['id'])) ||
            ($post['visibility'] == 0 && $_SESSION['user_id'] == $post['user_id'])):
        ?>
            <!-- SlugベースのURL生成 -->
            <a href="post.php?slug=<?= urlencode($post['slug']) ?>" class="card">
                <img src="../storage/uploads/<?= htmlspecialchars($post['image_path']) ?>" alt="Post Image">
                <div class="card-content">
                    <p><?= htmlspecialchars($post['description']) ?></p>
                    <p><strong>Posted by:</strong> <?= htmlspecialchars($post['username']) ?></p>
                </div>
            </a>
        <?php endif; ?>
    <?php endforeach; ?>
</div>



<!-- <?php include __DIR__ . '/../includes/footer.php'; ?> -->
