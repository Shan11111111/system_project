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

    // 使用 heredoc 輸出 HTML 頁面
    echo <<<HTML
<!DOCTYPE html>
<html lang='zh-Hant'>
<head>
    <meta charset='UTF-8'>
    <title>後台部門資訊</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f8f8f8; color: #333; }
        h2 { color: #2c3e50; }
        h3 { margin-top: 40px; color: #34495e; }
        p { line-height: 1.6; }
        hr { margin: 30px 0; border: none; border-top: 1px solid #ccc; }
    </style>
</head>
<body>
<h2>後台部門管理頁面</h2>
HTML;

    foreach ($departments as $dept) {
        echo "<h3>$dept</h3>";
        echo "<p>尚未設置資料分析功能。</p>";
        echo "<hr>";
    }

    echo "</body></html>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>資料庫連線失敗：{$e->getMessage()}</p>";
}
?>
