<?php
// 資料庫連線設定
$host = 'localhost';
$dbname = 'system_project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 建言狀態統計
    $stmt = $pdo->query("SELECT advice_state, COUNT(*) as count FROM advice GROUP BY advice_state");
    $statusData = ['未處理' => 0, '已分派' => 0, '已回覆' => 0];
    $totalAdvice = 0;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $status = $row['advice_state'];
        $count = (int)$row['count'];
        if (isset($statusData[$status])) {
            $statusData[$status] = $count;
        }
        $totalAdvice += $count;
    }

    $completed = $statusData['已回覆'];
    $completionRate = $totalAdvice > 0 ? round($completed / $totalAdvice * 100, 2) : 0;

    // 捐款總金額（來自 donate）
    $stmt = $pdo->query("SELECT SUM(donate_amount) FROM donate");
    $totalDonation = $stmt->fetchColumn() ?? 0;

    // 額外列出所有建言狀態
    $stmt = $pdo->query("SELECT advice_state, COUNT(*) as count FROM advice GROUP BY advice_state");
    $allAdviceStates = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "<p style='color: red;'>資料庫連線失敗：{$e->getMessage()}</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>建言與捐款統計分析</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial; padding: 20px; background: #f8f8f8; color: #333; }
        h2 { color: #2c3e50; }
        h3 { color: #34495e; margin-top: 40px; }
        .hidden { display: none; }
        .chart-container { max-width: 400px; margin-top: 20px; }
        button {
            padding: 10px 20px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            background: #2980b9;
        }
        ul { line-height: 1.6; }
        hr { margin: 40px 0; border: none; border-top: 1px solid #ccc; }
    </style>
</head>
<body>
    <h2>建言與捐款統計分析</h2>

    <!-- 建言資訊 -->
    <p>📌 建言總數：<?= $totalAdvice ?></p>
    <p>✅ 完成率（已回覆 / 總建言）：<?= $completionRate ?>%</p>

    <!-- 捐款資訊 -->
    <p>💰 捐款總金額：<?= number_format($totalDonation) ?> 元</p>

    <!-- 圖表按鈕 -->
    <button onclick="toggleStats()">顯示／隱藏建言狀態圖表</button>

    <!-- 圖表區塊 -->
    <div id="statusStats" class="hidden">
        <ul>
            <li>未處理：<?= $statusData['未處理'] ?></li>
            <li>已分派：<?= $statusData['已分派'] ?></li>
            <li>已回覆：<?= $statusData['已回覆'] ?></li>
        </ul>

        <div class="chart-container">
            <canvas id="statusChart"></canvas>
        </div>
    </div>

    <!-- 額外狀態列出 -->
    <hr>
    <h3>📌 所有建言狀態（動態統計）</h3>
    <ul>
        <?php foreach ($allAdviceStates as $row): ?>
            <li><?= htmlspecialchars($row['advice_state']) ?>：<?= $row['count'] ?> 筆</li>
        <?php endforeach; ?>
    </ul>

    <script>
        function toggleStats() {
            const stats = document.getElementById('statusStats');
            stats.classList.toggle('hidden');
        }

        const ctx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['未處理', '已分派', '已回覆'],
                datasets: [{
                    label: '建言狀態統計',
                    data: [
                        <?= $statusData['未處理'] ?>,
                        <?= $statusData['已分派'] ?>,
                        <?= $statusData['已回覆'] ?>
                    ],
                    backgroundColor: ['#e74c3c', '#f39c12', '#2ecc71'],
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
</body>
</html>
