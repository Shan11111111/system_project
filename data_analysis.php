<?php
// 建立資料庫連接
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "system_project";
<?php
$host = 'localhost';
$dbname = 'school_system';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $departments = ['教務處', '學務處', '總務處', '輔導室', '資訊中心', '體育組', '人事室', '圖書館'];

    echo "<h2>建言統計報告（含加值功能）</h2>";

    foreach ($departments as $dept) {
        echo "<h3>$dept</h3>";

        // 1. 狀態數量統計
        $stmt = $pdo->prepare("
            SELECT status, COUNT(*) AS count 
            FROM suggestions 
            WHERE department = ? 
            GROUP BY status
        ");
        $stmt->execute([$dept]);
        $statusData = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $total = array_sum($statusData);
        $completed = $statusData['已完成'] ?? 0;
        $completionRate = $total > 0 ? round($completed / $total * 100, 2) : 0;

        echo "<p>建言總數：$total</p>";
        echo "<p>完成率：$completionRate%</p>";
        echo "<ul>
            <li>未處理：" . ($statusData['未處理'] ?? 0) . "</li>
            <li>處理中：" . ($statusData['處理中'] ?? 0) . "</li>
            <li>已完成：" . ($statusData['已完成'] ?? 0) . "</li>
        </ul>";

        // 2. 平均處理時間（天）
        $stmt = $pdo->prepare("
            SELECT AVG(DATEDIFF(resolved_at, created_at)) AS avg_days 
            FROM suggestions 
            WHERE department = ? AND status = '已完成' AND resolved_at IS NOT NULL
        ");
        $stmt->execute([$dept]);
        $avgDays = round($stmt->fetchColumn(), 1);
        echo "<p>平均處理時間：" . ($avgDays ? "$avgDays 天" : "無") . "</p>";

        // 3. 平均滿意度（1~5）
        $stmt = $pdo->prepare("
            SELECT AVG(satisfaction) AS avg_satisfaction 
            FROM suggestions 
            WHERE department = ? AND satisfaction IS NOT NULL
        ");
        $stmt->execute([$dept]);
        $avgSatisfaction = round($stmt->fetchColumn(), 1);
        echo "<p>平均滿意度：" . ($avgSatisfaction ? "$avgSatisfaction / 5 ⭐" : "無回饋") . "</p>";

        // 4. 最新3則回覆摘要
        $stmt = $pdo->prepare("
            SELECT title, response 
            FROM suggestions 
            WHERE department = ? AND response IS NOT NULL AND response != '' 
            ORDER BY resolved_at DESC 
            LIMIT 3
        ");
        $stmt->execute([$dept]);
        $replies = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($replies) > 0) {
            echo "<p>最新回覆摘要：</p><ul>";
            foreach ($replies as $reply) {
                $short = mb_substr(strip_tags($reply['response']), 0, 50);
                echo "<li><strong>{$reply['title']}</strong>：$short...</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>尚無回覆內容。</p>";
        }

        echo "<hr>";
    }

} catch (PDOException $e) {
    echo "資料庫連線失敗：" . $e->getMessage();
}
?>
