<?php
// 資料庫連線設定（目前雖保留但無使用）
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
    <title>建言數據分析報告</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f8f8f8; color: #333; }
        h2 { color: #2c3e50; }
        h3 { margin-top: 40px; color: #34495e; }
        p, ul { line-height: 1.6; }
        hr { margin: 30px 0; border: none; border-top: 1px solid #ccc; }
    </style>
</head>
<body>
<h2>建言統計報告（顯示部門名稱）</h2>
HTML;

    foreach ($departments as $dept) {
        echo "<h3>$dept</h3>";
        echo "<p>（此處原本顯示統計資料，已移除資料表查詢）</p>";
        echo "<hr>";
    }

    echo "</body></html>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>資料庫連線失敗：{$e->getMessage()}</p>";
}
?>
