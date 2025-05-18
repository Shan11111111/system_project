<?php
// 資料庫連線設定
$host = 'localhost';
$dbname = 'system_project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 取得所有建言
    $stmt = $pdo->query("SELECT a.advice_id, a.advice_title, a.advice_content, a.category, a.announce_date, a.advice_state, u.department
        FROM advice a
        LEFT JOIN suggestion_assignments sa ON a.advice_id = sa.advice_id
        LEFT JOIN users u ON sa.office_id = u.user_id");
    $advice_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 取得建言狀態統計
    $stmt = $pdo->query("SELECT advice_state, COUNT(*) as count FROM advice GROUP BY advice_state");
    $advice_states = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 取得所有募資提案
    $stmt = $pdo->query("SELECT id, proposal_title, proposal_content, amount, status, department, date FROM fundraising");
    $fundraising_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 取得募資狀態統計
    $stmt = $pdo->query("SELECT status, COUNT(*) as count FROM fundraising GROUP BY status");
    $fundraising_states = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .chart-container { width: 100%; max-width: 600px; margin: 20px auto; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<h2>建言與募資提案報告</h2>

<div class='section'>
    <h3>建言處理狀態統計</h3>
    <div class='chart-container'>
        <canvas id='adviceStateChart'></canvas>
    </div>
</div>
<div class='section'>
    <h3>所有建言列表</h3>
    <table>
        <tr>
            <th>建言編號</th>
            <th>標題</th>
            <th>內容</th>
            <th>分類</th>
            <th>日期</th>
            <th>狀態</th>
            <th>部門</th>
        </tr>
HTML;
    foreach ($advice_list as $advice) {
        echo "<tr>
            <td>{$advice['advice_id']}</td>
            <td>{$advice['advice_title']}</td>
            <td>" . htmlspecialchars(mb_substr($advice['advice_content'], 0, 50)) . "...</td>
            <td>{$advice['category']}</td>
            <td>{$advice['announce_date']}</td>
            <td>{$advice['advice_state']}</td>
            <td>{$advice['department']}</td>
        </tr>";
    }
    echo "</table></div>";

    // 募資狀態圖表
    echo "<div class='section'>
    <h3>募資處理狀態統計</h3>
    <div class='chart-container'>
        <canvas id='fundStateChart'></canvas>
    </div>
    </div>";

    // 募資提案表格
    echo "<div class='section'>
    <h3>所有募資提案列表</h3>
    <table>
        <tr>
            <th>募資編號</th>
            <th>標題</th>
            <th>內容</th>
            <th>金額</th>
            <th>狀態</th>
            <th>部門</th>
            <th>日期</th>
        </tr>";
    foreach ($fundraising_list as $fund) {
        echo "<tr>
            <td>{$fund['id']}</td>
            <td>{$fund['proposal_title']}</td>
            <td>" . htmlspecialchars(mb_substr($fund['proposal_content'], 0, 50)) . "...</td>
            <td>{$fund['amount']}</td>
            <td>{$fund['status']}</td>
            <td>{$fund['department']}</td>
            <td>{$fund['date']}</td>
        </tr>";
    }
    echo "</table></div>";

    // 狀態圖表資料
    $adviceStateLabels = [];
    $adviceStateData = [];
    foreach ($advice_states as $state) {
        $adviceStateLabels[] = $state['advice_state'];
        $adviceStateData[] = $state['count'];
    }
    $fundStateLabels = [];
    $fundStateData = [];
    foreach ($fundraising_states as $state) {
        $fundStateLabels[] = $state['status'];
        $fundStateData[] = $state['count'];
    }
    echo "<script>
        // 建言狀態圖表
        const adviceStateCtx = document.getElementById('adviceStateChart');
        new Chart(adviceStateCtx, {
            type: 'bar',
            data: {
                labels: " . json_encode($adviceStateLabels) . ",
                datasets: [{
                    label: '建言狀態',
                    data: " . json_encode($adviceStateData) . ",
                    backgroundColor: [
                        '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF6384'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: '建言處理狀態'
                    }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
        // 募資狀態圖表
        const fundStateCtx = document.getElementById('fundStateChart');
        new Chart(fundStateCtx, {
            type: 'bar',
            data: {
                labels: " . json_encode($fundStateLabels) . ",
                datasets: [{
                    label: '募資狀態',
                    data: " . json_encode($fundStateData) . ",
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: '募資提案處理狀態'
                    }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>";
    echo "</body></html>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>資料庫連線失敗：{$e->getMessage()}</p>";
}
?>