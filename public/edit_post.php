<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

require_once __DIR__ . '/../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$userId = $_SESSION['user_id'];
$slug = $_GET['slug'] ?? null;

$pdo = getDBConnection();

if ($slug) {
    try {
        $stmt = $pdo->prepare("
            SELECT * FROM posts WHERE slug = :slug
        ");
        $stmt->execute([':slug' => $slug]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$post || $post['user_id'] != $userId) {
            echo "編集権限がありません。";
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // フォームから送信されたデータを取得して保存
            $title = $_POST['title'];
            $description = $_POST['description'];
            $visibility = $_POST['visibility'];
            $image = $_FILES['image'];

            if ($image && $image['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../storage/uploads/';
                $imagePath = uniqid() . '_' . basename($image['name']);

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                if (move_uploaded_file($image['tmp_name'], $uploadDir . $imagePath)) {
                    $post['image_path'] = $imagePath;
                }
            }

            $stmtUpdate = $pdo->prepare("
                UPDATE posts 
                SET title = :title, description = :description, visibility = :visibility, image_path = :image_path 
                WHERE slug = :slug
            ");
            $stmtUpdate->execute([
                ':title' => $title,
                ':description' => $description,
                ':visibility' => $visibility,
                ':slug' => $slug,
                ':image_path' => $post['image_path'],
            ]);

            header("Location: post.php?slug=$slug");
            exit;
        }

        include '../includes/header.php';
?>

        <h2>投稿を編集する</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>思い出のタイトル<input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required></label><br>

            <label>思い出の画像、またはタグを貼るモノの画像<input type="file" id="imageInput" name="image" accept="image/*"></label><br>
            <img src="../storage/uploads/<?= htmlspecialchars($post['image_path']) ?>" width="200"><br>

            <label>思い出の説明<textarea name="description" required><?= htmlspecialchars($post['description']) ?></textarea></label><br>

            <label>公開設定：
                <select name="visibility">
                    <option value="2" <?= $post['visibility'] == 2 ? 'selected' : '' ?>>公開（一般ユーザーがアクセスできます）</option>
                    <option value="1" <?= $post['visibility'] == 1 ? 'selected' : '' ?>>限定公開（URLを知っている人のみアクセスできます）</option>
                    <option value="0" <?= $post['visibility'] == 0 ? 'selected' : '' ?>>非公開（自分の身がアクセスできます）</option>
                </select>
            </label><br>
            <div class="button-container">
                <button type="submit" class="post-button">更新</button>
            </div>
        </form>

        <script>
            const maxFileSizeMB = 4;

            document.getElementById('imageInput').addEventListener('change', (event) => {
                const file = event.target.files[0];

                if (file && file.size > maxFileSizeMB * 1024 * 1024) {
                    alert(`画像サイズは最大 ${maxFileSizeMB} MBまで！`);
                    event.target.value = '';
                }
            });
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
?>