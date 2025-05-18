<?php
// 資料庫連線設定
$host = 'localhost';
$dbname = 'system_project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 從 users 表獲取所有部門 (office 等級的使用者)
    $stmt = $pdo->query("SELECT DISTINCT department FROM users WHERE level = 'office' ORDER BY department");
    $departments = $stmt->fetchAll(PDO::FETCH_COLUMN);

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
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .stats { margin-top: 10px; }
        .chart-container { width: 100%; max-width: 600px; margin: 20px auto; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function toggleDetails(id) {
            var el = document.getElementById(id);
            el.style.display = el.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</head>
<body>
<h2>建言統計報告</h2>
HTML;

    // 每個部門顯示統計數據
    foreach ($departments as $i => $dept) {
        $id = "details_$i";
        
        // 查詢該部門的建言總數
        $stmt = $pdo->prepare("
            SELECT COUNT(*) 
            FROM advice a
            JOIN suggestion_assignments sa ON a.advice_id = sa.advice_id
            JOIN users u ON sa.office_id = u.user_id
            WHERE u.department = ?
        ");
        $stmt->execute([$dept]);
        $total = $stmt->fetchColumn();
        
        // 查詢該部門的建言分類統計
        $stmt = $pdo->prepare("
            SELECT a.category, COUNT(*) as count 
            FROM advice a
            JOIN suggestion_assignments sa ON a.advice_id = sa.advice_id
            JOIN users u ON sa.office_id = u.user_id
            WHERE u.department = ? 
            GROUP BY a.category
        ");
        $stmt->execute([$dept]);
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 查詢該部門的建言狀態統計
        $stmt = $pdo->prepare("
            SELECT a.advice_state, COUNT(*) as count 
            FROM advice a
            JOIN suggestion_assignments sa ON a.advice_id = sa.advice_id
            JOIN users u ON sa.office_id = u.user_id
            WHERE u.department = ? 
            GROUP BY a.advice_state
        ");
        $stmt->execute([$dept]);
        $states = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 查詢該部門的最新5條建言
        $stmt = $pdo->prepare("
            SELECT a.advice_title, a.advice_content, a.announce_date, a.advice_state
            FROM advice a
            JOIN suggestion_assignments sa ON a.advice_id = sa.advice_id
            JOIN users u ON sa.office_id = u.user_id
            WHERE u.department = ?
            ORDER BY a.announce_date DESC 
            LIMIT 5
        ");
        $stmt->execute([$dept]);
        $recent_suggestions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<div class='section'>";
        echo "<h3>$dept (共 $total 條建言)</h3>";
        echo "<button onclick=\"toggleDetails('$id')\">點我查看詳細數據</button>";
        echo "<div class='details' id='$id'>";
        
        // 顯示分類統計
        if (!empty($categories)) {
            echo "<div class='stats'>";
            echo "<h4>分類統計:</h4>";
            
            // 準備圖表數據
            $chartLabels = [];
            $chartData = [];
            foreach ($categories as $category) {
                $chartLabels[] = $category['category'];
                $chartData[] = $category['count'];
            }
            
            echo "<div class='chart-container'>";
            echo "<canvas id='categoryChart_$i'></canvas>";
            echo "</div>";
            
            echo "<script>
                const ctx_$i = document.getElementById('categoryChart_$i');
                new Chart(ctx_$i, {
                    type: 'pie',
                    data: {
                        labels: " . json_encode($chartLabels) . ",
                        datasets: [{
                            data: " . json_encode($chartData) . ",
                            backgroundColor: [
                                '#FF6384',
                                '#36A2EB',
                                '#FFCE56',
                                '#4BC0C0',
                                '#9966FF',
                                '#FF9F40'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'right',
                            },
                            title: {
                                display: true,
                                text: '建言分類統計'
                            }
                        }
                    }
                });
            </script>";
            echo "</div>";
        }
        
        // 顯示狀態統計
        if (!empty($states)) {
            echo "<div class='stats'>";
            echo "<h4>處理狀態統計:</h4>";
            
            $stateLabels = [];
            $stateData = [];
            foreach ($states as $state) {
                $stateLabels[] = $state['advice_state'];
                $stateData[] = $state['count'];
            }
            
            echo "<div class='chart-container'>";
            echo "<canvas id='stateChart_$i'></canvas>";
            echo "</div>";
            
            echo "<script>
                const stateCtx_$i = document.getElementById('stateChart_$i');
                new Chart(stateCtx_$i, {
                    type: 'bar',
                    data: {
                        labels: " . json_encode($stateLabels) . ",
                        datasets: [{
                            label: '處理狀態',
                            data: " . json_encode($stateData) . ",
                            backgroundColor: [
                                '#36A2EB',
                                '#FFCE56',
                                '#4BC0C0',
                                '#9966FF'
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
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>";
            echo "</div>";
        }
        
        // 顯示最新建言
        if (!empty($recent_suggestions)) {
            echo "<div class='recent'>";
            echo "<h4>最新5條建言:</h4>";
            echo "<table>";
            echo "<tr><th>標題</th><th>內容摘要</th><th>日期</th><th>狀態</th></tr>";
            foreach ($recent_suggestions as $suggestion) {
                echo "<tr>";
                echo "<td>{$suggestion['advice_title']}</td>";
                echo "<td>" . substr($suggestion['advice_content'], 0, 50) . "...</td>";
                echo "<td>{$suggestion['announce_date']}</td>";
                echo "<td>{$suggestion['advice_state']}</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        } else {
            echo "<p>目前尚無建言資料。</p>";
        }
        
        echo "</div></div>";
    }

    // 整體統計數據
    echo "<div class='section'>";
    echo "<h3>全校整體統計</h3>";
    
    // 全校建言總數
    $stmt = $pdo->query("SELECT COUNT(*) FROM advice");
    $total_advice = $stmt->fetchColumn();
    
    // 全校分類統計
    $stmt = $pdo->query("SELECT category, COUNT(*) as count FROM advice GROUP BY category");
    $all_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 全校狀態統計
    $stmt = $pdo->query("SELECT advice_state, COUNT(*) as count FROM advice GROUP BY advice_state");
    $all_states = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p>全校總建言數: $total_advice 條</p>";
    
    if (!empty($all_categories)) {
        echo "<div class='chart-container'>";
        echo "<canvas id='allCategoryChart'></canvas>";
        echo "</div>";
        
        $allChartLabels = [];
        $allChartData = [];
        foreach ($all_categories as $category) {
            $allChartLabels[] = $category['category'];
            $allChartData[] = $category['count'];
        }
        
        echo "<script>
            const allCategoryCtx = document.getElementById('allCategoryChart');
            new Chart(allCategoryCtx, {
                type: 'doughnut',
                data: {
                    labels: " . json_encode($allChartLabels) . ",
                    datasets: [{
                        data: " . json_encode($allChartData) . ",
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#FFCE56',
                            '#4BC0C0',
                            '#9966FF',
                            '#FF9F40'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: '全校建言分類統計'
                        }
                    }
                }
            });
        </script>";
    }
    
    if (!empty($all_states)) {
        echo "<div class='chart-container'>";
        echo "<canvas id='allStateChart'></canvas>";
        echo "</div>";
        
        $allStateLabels = [];
        $allStateData = [];
        foreach ($all_states as $state) {
            $allStateLabels[] = $state['advice_state'];
            $allStateData[] = $state['count'];
        }
        
        echo "<script>
            const allStateCtx = document.getElementById('allStateChart');
            new Chart(allStateCtx, {
                type: 'bar',
                data: {
                    labels: " . json_encode($allStateLabels) . ",
                    datasets: [{
                        label: '處理狀態',
                        data: " . json_encode($allStateData) . ",
                        backgroundColor: [
                            '#36A2EB',
                            '#FFCE56',
                            '#4BC0C0',
                            '#9966FF'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: '全校建言處理狀態'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>";
    }
    
    echo "</div>";

    echo "</body></html>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>資料庫連線失敗：{$e->getMessage()}</p>";
}
?>