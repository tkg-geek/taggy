<?php
require_once '../config/db.php';

// Slug生成関数
function generateSlug($title) {
    if (empty($title)) {
        return bin2hex(random_bytes(4));  // タイトルが空の場合でもランダム文字列生成
    }
    
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    return substr(bin2hex(random_bytes(4)), 0, 8);  // ランダム文字列追加
}

// 投稿作成関数
function createPost($userId, $imagePath, $description, $title, $visibility) {
    $pdo = getDBConnection();

    // タイトルからSlugを生成
    $slug = generateSlug($title);

    try {
        $stmt = $pdo->prepare("
            INSERT INTO posts (user_id, image_path, description, title, visibility, slug)
            VALUES (:user_id, :image_path, :description, :title, :visibility, :slug)
        ");
        $stmt->execute([
            ':user_id'    => $userId,
            ':image_path' => $imagePath,
            ':description' => $description,
            ':title'     => $title,
            ':visibility' => $visibility,
            ':slug'      => $slug
        ]);
    } catch (PDOException $e) {
        echo "データベースエラー: " . $e->getMessage();
        exit;
    }
}


// 画像アップロード処理
function handleImageUpload() {
    if (isset($_FILES['uploaded_file']) && $_FILES['uploaded_file']['error'] === UPLOAD_ERR_OK) {
        $maxFileSize = 4 * 1024 * 1024; // 4MB制限チェック
        if ($_FILES['uploaded_file']['size'] > $maxFileSize) {
            echo "エラー: アップロードされた画像サイズは4MB以下である必要があります。";
            return false;
        }

        $uploadDir = '../storage/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $uploadPath = $uploadDir . basename($_FILES['uploaded_file']['name']);

        if (move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $uploadPath)) {
            return basename($_FILES['uploaded_file']['name']);
        } else {
            echo "画像のアップロード中にエラーが発生しました。";
            return false;
        }
    }
    return null;
}

// 投稿作成と画像アップロード統一処理
function createPostWithFile($userId, $description, $title, $visibility) {
    $imagePath = handleImageUpload();

    if ($imagePath) {
        $slug = generateSlug($title);
        createPost($userId, $imagePath, $description, $title, $visibility, $slug);
        echo "投稿が正常に作成されました！";
    } else {
        echo "投稿作成に失敗しました。";
    }
}

// 投稿一覧取得関数
function getPosts() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
