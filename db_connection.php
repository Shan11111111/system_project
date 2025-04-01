<?php
// 資料庫連線設定
$host = 'localhost';  // 資料庫伺服器
$dbname = 'system_project';  // 資料庫名稱
$username = 'root';  // 資料庫使用者名稱
$password = '';  // 資料庫密碼

// 建立資料庫連線
$conn = new mysqli($host, $username, $password, $dbname);

// 檢查是否成功連接
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>