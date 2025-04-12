<?php
// 資料庫連線設定
$host = 'localhost';  // 資料庫伺服器
$dbname = 'system_project';  // 資料庫名稱
$username = 'root';  // 資料庫使用者名稱
$password = '';  // 資料庫密碼

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>