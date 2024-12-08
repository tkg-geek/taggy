<?php
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

    // 画像が正しくアップロードされたかチェック
    if (isset($image) && $image['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../storage/uploads/';
        $imagePath = uniqid() . '_' . basename($image['name']);

        // アップロードディレクトリが存在しない場合は作成
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                echo "アップロードディレクトリの作成に失敗しました！";
                exit;
            }
        }

        // 書き込み権限があるか確認
        if (is_writable($uploadDir)) {
            if (move_uploaded_file($image['tmp_name'], $uploadDir . $imagePath)) {
                createPost($userId, $imagePath, $description, $title);
                header('Location: /taggy/public/home.php');
                exit;

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
    <label>Image: <input type="file" name="image" required></label><br>
    <label>Description: <textarea name="description" required></textarea></label><br>
    <button type="submit">Post</button>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>
