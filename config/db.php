<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;

$envFile = '.env.development';  // デフォルトは開発環境用
if (getenv('APP_ENV') === 'production') {
    $envFile = '.env.production';
}

$dotenv = Dotenv::createImmutable(__DIR__ . '/../', $envFile);
$dotenv->load();

function getDBConnection() {
    $host = $_ENV['DB_HOST'];
    $dbname = $_ENV['DB_NAME'];
    $username = $_ENV['DB_USERNAME'];
    $password = $_ENV['DB_PASSWORD'];

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die('Database connection failed: ' . $e->getMessage());
    }
}
