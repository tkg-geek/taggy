<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../src/post.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$userId = $_SESSION['user_id'];
$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->execute([':user_id' => $userId]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>
<h2>My Posts</h2>
<?php foreach ($posts as $post): ?>
    <div>
        <img src="../storage/uploads/<?= htmlspecialchars($post['image_path']) ?>" alt="Post Image">
        <p><?= htmlspecialchars($post['description']) ?></p>
        <a href="post.php?id=<?= htmlspecialchars($post['id']) ?>">View Details</a>
    </div>
<?php endforeach; ?>
<?php include '../includes/footer.php'; ?>
