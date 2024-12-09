<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../src/post.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . __DIR__ . '/../public/index.php');
    exit;
}

$posts = getPosts();
include __DIR__ . '/../includes/header.php';
?>
<h2>みんなのTAGGY 投稿一覧</h2>

<div class="card-container">
    <?php foreach ($posts as $post): ?>
        <div class="card">
            <img src="../storage/uploads/<?= htmlspecialchars($post['image_path']) ?>" alt="Post Image">
            <div class="card-content">
                <p><?= htmlspecialchars($post['description']) ?></p>
                <p><strong>Posted by:</strong> <?= htmlspecialchars($post['username']) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- <?php include __DIR__ . '/../includes/footer.php'; ?> -->
