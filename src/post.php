<?php
require_once '../config/db.php';

function createPost($userId, $imagePath, $description, $title) {
    $pdo = getDBConnection();

    try {
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, image_path, description, title) VALUES (:user_id, :image_path, :description, :title)");
        $stmt->execute([
            ':user_id' => $userId,
            ':image_path' => $imagePath,
            ':description' => $description,
            ':title' => $title   // titleの追加
        ]);
    } catch (PDOException $e) {
        echo "データベースエラー: " . $e->getMessage();
        exit;
    }
}

function handleImageUpload() {
    if (isset($_FILES['uploaded_file']) && $_FILES['uploaded_file']['error'] === UPLOAD_ERR_OK) {
        // 4MB制限のチェック（より確実な方法）
        $maxFileSize = 4 * 1024 * 1024; // 4MB
        if ($_FILES['uploaded_file']['size'] > $maxFileSize) {
            echo "エラー: アップロードされた画像サイズは4MB以下である必要があります。";
            return false;
        }

        // アップロードディレクトリへのパス設定
        $uploadDir = '../storage/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $uploadPath = $uploadDir . basename($_FILES['uploaded_file']['name']);

        if (move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $uploadPath)) {
            return basename($_FILES['uploaded_file']['name']); // ファイル名のみを返す
        } else {
            echo "画像のアップロード中にエラーが発生しました。";
            return false;
        }
    }
    return null;
}

function createPostWithFile($userId, $description, $title) {
    $imagePath = handleImageUpload();

    if ($imagePath) {
        createPost($userId, $imagePath, $description, $title);
        echo "投稿が正常に作成されました！";
    } else {
        echo "投稿作成に失敗しました。";
    }
}


function getPosts() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
