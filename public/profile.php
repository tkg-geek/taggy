<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/db.php';
session_start();

// データベース接続取得
$pdo = getDBConnection();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// ユーザー情報取得
$user_id = $_SESSION['user_id'];
$query = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$query->execute([$user_id]);
$user = $query->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $profile_image = $_FILES['profile_image'];

    try {
        // ユーザーネームとEmailを更新
        $updateQuery = $pdo->prepare('UPDATE users SET username = ?, email = ? WHERE id = ?');
        $updateQuery->execute([$username, $email, $user_id]);

        // パスワードが入力されていれば更新
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updatePassQuery = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
            $updatePassQuery->execute([$hashedPassword, $user_id]);
        }

        // プロフィール画像の更新
        if ($profile_image && $profile_image['error'] == UPLOAD_ERR_OK) {
            $target_dir = "../storage/uploads/";
            if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);

            $filePath = $target_dir . basename($profile_image['name']);
            move_uploaded_file($profile_image['tmp_name'], $filePath);

            $updateImgQuery = $pdo->prepare('UPDATE users SET profile_image = ? WHERE id = ?');
            $updateImgQuery->execute([$filePath, $user_id]);

            // セッションに新しいプロフィール画像のパスを格納
            $_SESSION['profile_image'] = $filePath;
        }

        // セッションに新しいユーザーネームを格納
        $_SESSION['username'] = $username;

        // リダイレクトして情報を反映
        header('Location: profile.php');
        exit();

    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
        exit();
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="form-container">
        <h2>ユーザー情報編集</h2>

        <form method="POST" enctype="multipart/form-data">
            <label for="username">ユーザーネーム</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <label for="password">新しいパスワード</label>
            <input type="password" id="password" name="password" placeholder="Enter New Password">

            <label for="profile_image">プロフィール画像</label>
            <input type="file" id="profile_image" name="profile_image">

            <button type="submit">保存する</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
