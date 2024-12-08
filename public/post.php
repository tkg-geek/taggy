<?php
require_once __DIR__ . '/../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$postId = $_GET['id'];
$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id WHERE posts.id = :id");
$stmt->execute([':id' => $postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    header('Location: home.php');
    exit;
}

include '../includes/header.php';
?>
<h2>Post Details</h2>
<div>
    <img src="../storage/uploads/<?= htmlspecialchars($post['image_path']) ?>" alt="Post Image">
    <p><?= htmlspecialchars($post['description']) ?></p>
    <p>Posted by: <?= htmlspecialchars($post['username']) ?></p>
</div>
<!-- NFC書き込みボタン -->
<form method="POST" action="write_nfc.php">
    <input type="hidden" name="url" value="<?= "https://taggy.com/post.php?id=" . htmlspecialchars($post['id']) ?>">
    <button type="submit">Write to NFC Tag</button>
</form>
<?php include '../includes/footer.php'; ?>
