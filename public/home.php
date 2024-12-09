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
<h2>Home</h2>

<?php foreach ($posts as $post): ?>
    <div>
    <img src="../storage/uploads/<?= htmlspecialchars($post['image_path']) ?>" alt="Post Image">
        <p><?= htmlspecialchars($post['description']) ?></p>
        <p>Posted by: <?= htmlspecialchars($post['username']) ?></p>
    </div>
<?php endforeach; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
