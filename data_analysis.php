<?php
// 資料庫連線設定
$host = 'localhost';
$dbname = 'system_project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 查詢所有狀態統計
    $stmt = $pdo->query("SELECT status, COUNT(*) AS count FROM suggestions GROUP BY status");
    $statusData = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    $total = array_sum($statusData);
    $completed = $statusData['已完成'] ?? 0;
    $completionRate = $total > 0 ? round($completed / $total * 100, 2) : 0;

} catch (PDOException $e) {
    die("<p style='color:red;'>資料庫連線錯誤：" . $e->getMessage() . "</p>");
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
            <li>未處理：<?= $statusData['未處理'] ?? 0 ?></li>
            <li>處理中：<?= $statusData['處理中'] ?? 0 ?></li>
            <li>已完成：<?= $statusData['已完成'] ?? 0 ?></li>
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

        // 繪製 Chart.js 圓餅圖
        const ctx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['未處理', '處理中', '已完成'],
                datasets: [{
                    label: '狀態統計',
                    data: [
                        <?= $statusData['未處理'] ?? 0 ?>,
                        <?= $statusData['處理中'] ?? 0 ?>,
                        <?= $statusData['已完成'] ?? 0 ?>
                    ],
                    backgroundColor: ['#e74c3c', '#f1c40f', '#2ecc71'],
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
</body>
</html>
