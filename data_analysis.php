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

    // 額外列出所有建言狀態
    $allAdviceStates = $pdo->query("SELECT advice_state, COUNT(*) as count FROM advice GROUP BY advice_state")->fetchAll(PDO::FETCH_ASSOC);

    // 募資統計
    $donateStats = $pdo->query("SELECT SUM(donate_amount) AS total_donated FROM donate")->fetch(PDO::FETCH_ASSOC);
    $totalDonated = $donateStats['total_donated'] ?? 0;

} catch (PDOException $e) {
    echo "<p style='color: red;'>資料庫連線失敗：{$e->getMessage()}</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>建言與募資統計分析</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
            background: #FFF4E2;
            color: #333;
        }
        h2, h3 {
            color: #7A4E21;
        }
        button {
            background-color: #7A4E21;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            margin: 10px 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #5a3c17;
        }
        .chart-container {
            max-width: 400px;
            margin-top: 20px;
        }
        .hidden {
            display: none;
        }
        ul {
            line-height: 1.6;
        }
        hr {
            margin: 40px 0;
            border: none;
            border-top: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <h2>建言與募資統計分析</h2>

    <h3>📌 建言統計</h3>
    <p>建言總數：<?= $totalAdvice ?></p>
    <p>完成率（已回覆 / 總建言）：<?= $completionRate ?>%</p>

    <button onclick="toggleStats()">顯示／隱藏建言狀態圖表</button>

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

    <hr>
    <h3>📌 所有建言狀態（動態統計）</h3>
    <ul>
        <?php foreach ($allAdviceStates as $row): ?>
            <li><?= htmlspecialchars($row['advice_state']) ?>：<?= $row['count'] ?> 筆</li>
        <?php endforeach; ?>
    </ul>

    <hr>
    <h3>💰 募資統計</h3>
    <p>總募得金額：<?= number_format($totalDonated) ?> 元</p>

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
                    backgroundColor: ['#E74C3C', '#FCD34D', '#3CB371'],
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
</body>
</html>
