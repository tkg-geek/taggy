<?php
require_once __DIR__ . '/../config/db.php';

function signup($username, $email, $password) {
    $pdo = getDBConnection();
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password)");
    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':password' => $hashedPassword
    ]);
}

function login($email, $password) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        header('Location: home.php');
        exit;
    }
    return false; // ログイン失敗
}

function logout() {
    session_start();
    session_destroy();
    header('Location: index.php');
    exit;
}
?>
