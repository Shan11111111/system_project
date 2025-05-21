<?php
// 資料庫連線設定
$host = 'localhost';
$dbname = 'system_project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 取得建言統計資料（依分類）
    $stmt = $pdo->query("SELECT category, COUNT(*) as count FROM advice GROUP BY category");
    $adviceCategoryStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 取得募資目標金額及已募金額資料
    $stmt = $pdo->query("SELECT title, funding_goal as amount, 
        (SELECT IFNULL(SUM(donation_amount),0) FROM donation_record WHERE project_id = fp.project_id) as donated_amount 
        FROM fundraising_projects fp");
    $fundraisingStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 取得捐款總金額統計
    $stmt = $pdo->query("SELECT SUM(donation_amount) as total_donations FROM donation_record");
    $total_donations = $stmt->fetch(PDO::FETCH_ASSOC)['total_donations'] ?? 0;

    // 籌資標題、目標、已募
    $fundTitles = [];
    $fundGoals = [];
    $fundDonated = [];
    foreach ($fundraisingStats as $fund) {
        $fundTitles[] = $fund['title'];
        $fundGoals[] = (float)$fund['amount'];
        $fundDonated[] = (float)$fund['donated_amount'];
    }

    // HTML 開始
    echo <<<HTML
<!DOCTYPE html>
<html lang='zh-Hant'>
<head>
    <meta charset='UTF-8'>
    <title>建言與募資報告</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f8f8f8; color: #333; }
        h2 { color: #2c3e50; }
        .section { margin-bottom: 40px; border-bottom: 1px solid #ccc; padding-bottom: 20px; }
        .chart-container { width: 100%; max-width: 700px; margin: 30px auto; background:#fff; border-radius:8px; box-shadow:0 2px 6px #0001; padding:24px;}
        .stats-box { 
            background: #fff; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            padding: 15px; 
            margin: 20px 0; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<h2>建言與募資提案報告</h2>

<div class='stats-box'>
    <h3>捐款總金額統計</h3>
    <p>總捐款金額: NT$ " . number_format($total_donations) . "</p>
</div>

<div class='section'>
    <h3>建言分類分布圖</h3>
    <div class='chart-container'>
        <canvas id='adviceCategoryChart'></canvas>
    </div>
</div>

<div class='section'>
    <h3>各募資提案目標與已募金額圖</h3>
    <div class='chart-container'>
        <canvas id='fundraisingChart'></canvas>
    </div>
</div>

<script>
    // 建言分類資料
    const adviceCategoryLabels = <?php echo json_encode(array_column($adviceCategoryStats, 'category'), JSON_UNESCAPED_UNICODE); ?>;
    const adviceCategoryData = <?php echo json_encode(array_column($adviceCategoryStats, 'count')); ?>;

    new Chart(document.getElementById('adviceCategoryChart'), {
        type: 'pie',
        data: {
            labels: adviceCategoryLabels,
            datasets: [{
                data: adviceCategoryData,
                backgroundColor: [
                    '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF6384', '#8DD17E', '#FFD700'
                ]
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: '建言分類統計'
                }
            }
        }
    });

    // 募資提案目標 vs 募得
    const fundTitles = <?php echo json_encode($fundTitles, JSON_UNESCAPED_UNICODE); ?>;
    const fundGoals = <?php echo json_encode($fundGoals); ?>;
    const fundDonated = <?php echo json_encode($fundDonated); ?>;

    new Chart(document.getElementById('fundraisingChart'), {
        type: 'bar',
        data: {
            labels: fundTitles,
            datasets: [
                {
                    label: '目標金額',
                    data: fundGoals,
                    backgroundColor: '#36A2EB'
                },
                {
                    label: '已募得金額',
                    data: fundDonated,
                    backgroundColor: '#4CAF50'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: '募資提案目標與已募金額'
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
</body>
</html>
HTML;

} catch (PDOException $e) {
    echo "<p style='color: red;'>資料庫連線失敗：{$e->getMessage()}</p>";
}
?>