<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['url'];
    // NFC書き込み処理を実装
    echo "NFC tag written with URL: " . htmlspecialchars($url);
}
?>
