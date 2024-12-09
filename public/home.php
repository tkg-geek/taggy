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

$posts = getPosts();
include __DIR__ . '/../includes/header.php';
?>

<div class="card-container">
    <?php foreach ($posts as $post): ?>
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
