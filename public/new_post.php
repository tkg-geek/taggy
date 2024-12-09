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

// POSTリクエストの場合の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $description = $_POST['description'];
    $title = $_POST['title'];
    $image = $_FILES['image'];
    $visibility = intval($_POST['visibility']);

    // 画像サイズ制限（4MB）
    if ($image['size'] > 4 * 1024 * 1024) {
        echo "アップロードする画像は4MB以下である必要があります。";
        exit;
    }

    if (isset($image) && $image['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../storage/uploads/';
        $imagePath = uniqid() . '_' . basename($image['name']);

        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                echo "アップロードディレクトリの作成に失敗しました！";
                exit;
            }
        }

        if (is_writable($uploadDir)) {
            if (move_uploaded_file($image['tmp_name'], $uploadDir . $imagePath)) {
                $slug = generateSlug($title);  // slug生成関数を追加
                createPost($userId, $imagePath, $description, $title, $visibility, $slug);

                header('Location: /taggy/public/home.php');
                exit;
            } else {
                echo "画像のアップロードに失敗しました！";
            }
        } else {
            echo "ディレクトリに書き込み権限がありません。";
        }
    } else {
        echo "画像が選択されていないか、アップロードに失敗しました！";
    }
}

include __DIR__ . '/../includes/header.php';
?>

<h2>New Post</h2>

<form method="POST" enctype="multipart/form-data">
    <label>Title: <input type="text" name="title" required></label><br>
    <label>Image: <input type="file" id="imageInput" name="image" accept="image/*" required></label><br>
    <span id="fileStatus" style="color: red;"></span><br>

    <label>Description: <textarea name="description" required></textarea></label><br>

    <!-- Visibility選択肢 -->
    <label>Visibility:
        <select name="visibility">
            <option value="2">公開</option>
            <option value="1">限定公開</option>
            <option value="0">非公開</option>
        </select>
    </label><br>

    <button type="submit">Post</button>
</form>


<!-- クライアント側のサイズチェック -->
<script>
    const maxFileSizeMB = 4; // 4MB制限

    document.getElementById('imageInput').addEventListener('change', (event) => {
        const file = event.target.files[0];
        const status = document.getElementById('fileStatus');

        if (file) {
            if (file.size > maxFileSizeMB * 1024 * 1024) {
                status.textContent = `投稿できる画像サイズは最大${maxFileSizeMB}MBまでです！`;
                event.target.value = ""; // サイズ超過の場合、入力内容をクリア
            } else {
                status.textContent = '';
            }
        }
    });
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>