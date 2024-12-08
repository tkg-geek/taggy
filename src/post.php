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

function getPosts() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
