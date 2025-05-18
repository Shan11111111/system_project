<?php
// 資料庫連線設定
$host = 'localhost';
$dbname = 'system_project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $departments = ['教務處', '學務處', '總務處', '輔導室', '資訊中心', '體育組', '人事室', '圖書館'];

    echo <<<HTML
<!DOCTYPE html>
<html lang='zh-Hant'>
<head>
    <meta charset='UTF-8'>
    <title>部門報告總覽</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f8f8f8; color: #333; }
        h2 { color: #2c3e50; }
        h3 { margin-top: 40px; color: #34495e; }
        section { background: #fff; padding: 15px 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 40px; }
    </style>
</head>
<body>
<h2>部門報告總覽</h2>
HTML;

    foreach ($departments as $dept) {
        echo "<section><h3>" . htmlspecialchars($dept) . "</h3>";
        echo "<p>尚未提供統計資料。</p>";
        echo "</section>";
    }

    echo "</body></html>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>資料庫連線失敗：{$e->getMessage()}</p>";
}
?>
