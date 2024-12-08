<?php
require_once __DIR__ . '/../src/auth.php';
logout();

header('Location: ' . __DIR__ . '/../public/index.php');
exit;
?>
