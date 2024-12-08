<?php
// セッションを開始
session_start();

// 必要なファイルを読み込む
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/post.php';

// ルーティング処理をここで全部まとめる
$routes = [
    '/' => __DIR__ . '/../public/index.php',
    '/index.php' => __DIR__ . '/../public/index.php',
    '/home.php' => __DIR__ . '/../public/home.php',
    '/signup.php' => __DIR__ . '/../public/signup.php',
    '/login.php' => __DIR__ . '/../public/login.php',
    '/logout.php' => __DIR__ . '/../public/logout.php',
    '/mypage.php' => __DIR__ . '/../public/mypage.php',
    '/new_post.php' => __DIR__ . '/../public/new_post.php',
    '/post.php' => __DIR__ . '/../public/post.php',
    '/write_nfc.php' => __DIR__ . '/../public/write_nfc.php',
];

// 現在のリクエストURIを取得
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = rtrim($requestUri, '/'); // 末尾のスラッシュを削除

// ログインしていないユーザーが /home.php にアクセスした場合のリダイレクト処理
if ($requestUri === '/home.php' && !isset($_SESSION['user_id'])) {
    header('Location: ' . $_SERVER['HTTP_HOST'] . '/taggy/public/index.php');
    exit;
}


// 対応するビューファイルを取得
if (isset($routes[$requestUri])) {
    include $routes[$requestUri];
} else {
    // 404エラーページ（存在しないページの場合）
    http_response_code(404);
    echo "<h1>404 Not Found</h1>";
    echo "<p>The page you are looking for does not exist.</p>";
}