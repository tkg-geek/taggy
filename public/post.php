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

<!-- NFCタグ書き込みボタン -->
<div>
    <input type="hidden" id="nfcData" value="<?= "https://taggy.com/post.php?id=" . htmlspecialchars($post['id']) ?>">
    <button onclick="writeToNFCTag()">NFCタグに書き込む</button>
    <div id="status" style="color: green; margin-top: 10px;"></div>
</div>

<script>
    async function writeToNFCTag() {
        const dataInput = document.getElementById('nfcData');
        const statusDiv = document.getElementById('status');

        if ('NDEFReader' in window) {
            try {
                const ndef = new NDEFReader();
                await ndef.scan();

                await ndef.write({
                    records: [{
                        recordType: 'text',
                        data: dataInput.value
                    }]
                });

                statusDiv.textContent = 'Taggyタグに書き込みました！';
            } catch (error) {
                console.error('NFC書き込みエラー:', error);
                statusDiv.textContent = `エラー: ${error.message}`;
            }
        } else {
            statusDiv.textContent = 'このブラウザはWeb NFCに対応していません';
        }
    }
</script>

<?php include '../includes/footer.php'; ?>
