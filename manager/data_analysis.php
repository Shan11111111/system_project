<?php
// 資料庫連線設定
$host = 'localhost';
$dbname = 'system_project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 查詢全部部門的資料
    $stmt = $pdo->query("SELECT * FROM department_data");
    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
<h2>建言統計報告（資料庫版本）</h2>
HTML;

    foreach ($departments as $i => $dept) {
        $id = "details_$i";
        $completionRate = $dept['total'] > 0 ? round($dept['completed'] / $dept['total'] * 100, 2) : 0;
        echo "<div class='section'>";
        echo "<h3>{$dept['department']}</h3>";
        echo "<button onclick=\"toggleDetails('$id')\">點我查看詳細數據</button>";
        echo "<div class='details' id='$id'>";
        echo "<p>建言總數：{$dept['total']}</p>";
        echo "<p>完成率：$completionRate%</p>";
        echo "<p>平均處理時間：{$dept['avg_days']} 天</p>";
        echo "<p>平均滿意度：{$dept['avg_satisfaction']} / 5 ⭐</p>";
        echo "<p>最新回覆摘要：</p>";
        echo "<ul>";
        for ($j = 1; $j <= 3; $j++) {
            $title = htmlspecialchars($dept["latest{$j}_title"]);
            $res = htmlspecialchars(mb_substr(strip_tags($dept["latest{$j}_response"]), 0, 50, 'UTF-8'));
            if ($title && $res) {
                echo "<li><strong>$title</strong>：$res...</li>";
            }
        }
        echo "</ul></div></div>";
    }

    echo "</body></html>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>資料庫連線失敗：{$e->getMessage()}</p>";
}
?>
