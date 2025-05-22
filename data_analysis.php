<?php
// 資料庫連線設定
$host = 'localhost';
$dbname = 'system_project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 取得各狀態數量
    $stmt = $pdo->query("SELECT advice_state, COUNT(*) as count FROM advice GROUP BY advice_state");
    $statusData = ['未處理' => 0, '已分派' => 0, '已回覆' => 0];
    $total = 0;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $status = $row['advice_state'];
        $count = (int)$row['count'];
        if (isset($statusData[$status])) {
            $statusData[$status] = $count;
            $total += $count;
        }
    }

    $completed = $statusData['已回覆'];
    $completionRate = $total > 0 ? round($completed / $total * 100, 2) : 0;
} catch (PDOException $e) {
    echo "<p style='color: red;'>資料庫連線失敗：{$e->getMessage()}</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>建言總體數據分析</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial; padding: 20px; background: #f8f8f8; color: #333; }
        h2 { color: #2c3e50; }
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
    </style>
</head>
<body>
    <h2>建言總體數據分析</h2>

    <p>建言總數：<?= $total ?></p>
    <p>完成率：<?= $completionRate ?>%</p>

    <button onclick="toggleStats()">顯示／隱藏狀態圖表</button>

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
