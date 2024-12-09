<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

require_once __DIR__ . '/../config/db.php';
session_start();

// if (!isset($_SESSION['user_id'])) {
//     header('Location: index.php');
//     exit;
// }

$userId = $_SESSION['user_id'];
$slug = $_GET['slug'] ?? null;

$pdo = getDBConnection();

if ($slug) {
    try {
        $stmt = $pdo->prepare("
            SELECT posts.*, users.username 
            FROM posts 
            JOIN users ON posts.user_id = users.id 
            WHERE posts.slug = :slug
        ");
        $stmt->execute([':slug' => $slug]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$post) {
            header('Location: home.php');
            exit;
        }

        // アクセス制御チェック
        if ($post['visibility'] == 0 && $post['user_id'] != $userId) {
            // 非公開投稿で、ログインユーザーが投稿者ではない場合
            echo "この投稿にはアクセスできません。";
            exit;
        }

        // ユーザーが投稿したユーザーと同じかチェックするフラグ設定
        $isPostOwner = ($post['user_id'] == $userId);

        include '../includes/header.php';
        ?>

        <div class="post-container">
            <!-- 左側の画像 -->
            <div class="post-image">
                <img src="../storage/uploads/<?= htmlspecialchars($post['image_path']) ?>" alt="Post Image">
            </div>

            <!-- 右側の投稿情報 -->
            <div class="post-details">
                <h2><?= htmlspecialchars($post['title']) ?></h2>
                <p class="description"><?= htmlspecialchars($post['description']) ?></p>
                <p><strong>Posted by:</strong> <?= htmlspecialchars($post['username']) ?></p>

                <!-- NFC書き込みボタンを表示するかチェック -->
                <?php if ($isPostOwner): ?>
                    <button id="writeNFCButton">TAGGYタグに書き込む</button>
                    <p id="nfcStatus" class="status-message"></p>
                <?php endif; ?>
                
            </div>
        </div>

        <script>
            <?php if ($isPostOwner): ?>
                document.getElementById('writeNFCButton').addEventListener('click', async () => {
                    const status = document.getElementById('nfcStatus');
                    status.textContent = 'TAGGYタグにタッチして下さい';

                    if ('NDEFReader' in window) {
                        try {
                            const ndef = new NDEFReader();
                            await ndef.scan();

                            const link = `https://tkgeek.sakura.ne.jp/taggy/public/post.php?slug=<?= htmlspecialchars($slug) ?>`;
                            const text = "このアイテムの思い出を見る";

                            await ndef.write({
                                records: [
                                    { recordType: 'text', data: text },
                                    { recordType: 'url', data: link }
                                ]
                            });

                            status.textContent = 'TAGGYタグに書き込みました！';
                        } catch (error) {
                            console.error('NFC書き込みエラー:', error);
                            status.textContent = `エラー: ${error.message}`;
                        }
                    } else {
                        status.textContent = 'このブラウザはWeb NFCに対応していません';
                    }
                });
            <?php endif; ?>
        </script>

        <?php include '../includes/footer.php';

    } catch (PDOException $e) {
        echo "データベースエラー: " . $e->getMessage();
        exit;
    }
} else {
    header('Location: home.php');
    exit;
}

// Slug生成関数：タイトルからランダム文字列生成
function generateSlug($title) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    return $slug . '-' . substr(bin2hex(random_bytes(4)), 0, 8);  // ランダム要素を追加
}

// 投稿作成時にSlug生成してデータベースに保存する処理
function createPost($userId, $imagePath, $description, $title, $visibility) {
    $pdo = getDBConnection();

    $slug = generateSlug($title);

    try {
        $stmt = $pdo->prepare("
            INSERT INTO posts (user_id, image_path, description, title, visibility, slug)
            VALUES (:user_id, :image_path, :description, :title, :visibility, :slug)
        ");
        $stmt->execute([
            ':user_id' => $userId,
            ':image_path' => $imagePath,
            ':description' => $description,
            ':title' => $title,
            ':visibility' => $visibility,
            ':slug' => $slug
        ]);
    } catch (PDOException $e) {
        echo "データベースエラー: " . $e->getMessage();
        exit;
    }
}
?>
