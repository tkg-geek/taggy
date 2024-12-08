<?php
function getDBConnection() {
    $host = 'localhost';
    $dbname = 'taggy';
    $username = 'root'; // 適宜変更
    $password = '';     // 適宜変更

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die('Database connection failed: ' . $e->getMessage());
    }
}
?>
