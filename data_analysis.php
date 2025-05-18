<?php
// 資料庫連線設定（目前雖保留但未使用）
$host = 'localhost';
$dbname = 'system_project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 靜態部門清單
    $departments = ['教務處', '學務處', '總務處', '輔導室', '資訊中心', '體育組', '人事室', '圖書館'];

    // HTML 開始
    echo <<<HTML
<!DOCTYPE html>
<html lang='zh-Hant'>
<head>
    <meta charset='UTF-8'>
    <title>建言數據分析報告</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f8f8f8; color: #333; }
        h2 { color: #2c3e50; }
        .section { margin-bottom: 40px; border-bottom: 1px solid #ccc; padding-bottom: 20px; }
        .details { display: none; margin-top: 10px; }
        button { margin-top: 10px; }
    </style>
    <script>
        function toggleDetails(id) {
            var el = document.getElementById(id);
            el.style.display = el.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</head>
<body>
<h2>建言統計報告（靜態版，無資料表查詢）</h2>
HTML;

    // 每個部門顯示一個可展開的區塊（無資料）
    foreach ($departments as $i => $dept) {
        $id = "details_$i";
        echo "<div class='section'>";
        echo "<h3>$dept</h3>";
        echo "<button onclick=\"toggleDetails('$id')\">點我查看詳細數據</button>";
        echo "<div class='details' id='$id'>";
        echo "<p>目前尚無資料（未接資料庫）。</p>";
        echo "</div></div>";
    }

    echo "</body></html>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>資料庫連線失敗：{$e->getMessage()}</p>";
}
?>
