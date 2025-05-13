<?php
// 建立 PDO 資料庫連線
$host = 'localhost';
$dbname = 'system_project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h2>📊 建言統計報告（含加值功能）</h2>";

    // 🔍 自動抓取所有出現過的部門
    $stmt = $pdo->query("SELECT DISTINCT department FROM suggestions ORDER BY department");
    $departments = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($departments)) {
        echo "<p>⚠️ 尚無任何部門資料</p>";
        exit;
    }

    foreach ($departments as $dept) {
        echo "<h3>📌 $dept</h3>";

        // 1️⃣ 狀態數量統計
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

        echo "<p>總建言數：<strong>$total</strong></p>";
        echo "<p>完成率：<strong>$completionRate%</strong></p>";
        echo "<ul>
            <li>未處理：" . ($statusData['未處理'] ?? 0) . "</li>
            <li>處理中：" . ($statusData['處理中'] ?? 0) . "</li>
            <li>已完成：" . ($statusData['已完成'] ?? 0) . "</li>
        </ul>";

        // 2️⃣ 平均處理時間（天）
        $stmt = $pdo->prepare("
            SELECT AVG(DATEDIFF(resolved_at, created_at)) AS avg_days 
            FROM suggestions 
            WHERE department = ? AND status = '已完成' AND resolved_at IS NOT NULL
        ");
        $stmt->execute([$dept]);
        $avgDays = round($stmt->fetchColumn(), 1);
        echo "<p>平均處理時間：" . ($avgDays ? "$avgDays 天" : "無") . "</p>";

        // 3️⃣ 平均滿意度（1~5）
        $stmt = $pdo->prepare("
            SELECT AVG(satisfaction) AS avg_satisfaction 
            FROM suggestions 
            WHERE department = ? AND satisfaction IS NOT NULL
        ");
        $stmt->execute([$dept]);
        $avgSatisfaction = round($stmt->fetchColumn(), 1);
        echo "<p>平均滿意度：" . ($avgSatisfaction ? "$avgSatisfaction / 5 ⭐" : "無回饋") . "</p>";

        // 4️⃣ 最新回覆摘要
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
    echo "<p style='color:red;'>❌ 資料庫連線失敗：" . $e->getMessage() . "</p>";
}
?>
