<?php
$host = 'localhost';
$dbname = 'system_project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("資料庫連線失敗: " . $e->getMessage());
    // header("Location: error_page.php");
    // exit;
}
?>