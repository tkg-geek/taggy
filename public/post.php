<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
<div class="post-container">
    <!-- 左側の画像 -->
    <div class="post-image">
        <img src="../storage/uploads/<?= htmlspecialchars($post['image_path']) ?>" alt="Post Image">
    </div>

    <!-- 右側の情報 -->
    <div class="post-details">
        <h2><?= htmlspecialchars($post['title']) ?></h2>
        <p class="description"><?= htmlspecialchars($post['description']) ?></p>
        <p><strong>Posted by:</strong> <?= htmlspecialchars($post['username']) ?></p>

        <!-- NFC書き込みボタン -->
        <button id="writeNFCButton">Write to NFC Tag</button>
        <p id="nfcStatus" class="status-message"></p>
    </div>
</div>

<script>
    document.getElementById('writeNFCButton').addEventListener('click', async () => {
        const status = document.getElementById('nfcStatus');
        status.textContent = 'Taggyタグにタッチして下さい';

        if ('NDEFReader' in window) {
            try {
                const ndef = new NDEFReader();
                await ndef.scan();
                
                // 書き込むデータを作成
                const link = `https://tkgeek.sakura.ne.jp/taggy/public/post.php?id=<?= htmlspecialchars($postId) ?>`;
                const text = "このアイテムの思い出を見る";

                // 書き込み処理
                await ndef.write({
                    records: [
                        {
                            recordType: 'text',
                            data: text
                        },
                        {
                            recordType: 'url',
                            data: link
                        }
                    ]
                });

                status.textContent = 'Taggyタグに書き込みました！';
            } catch (error) {
                console.error('NFC書き込みエラー:', error);
                status.textContent = `エラー: ${error.message}`;
            }
        } else {
            status.textContent = 'このブラウザはWeb NFCに対応していません';
        }
    });
</script>

<?php include '../includes/footer.php'; ?>
