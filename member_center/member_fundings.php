<?php
session_start();
require_once '../db_connection.php';

$user_id = $_SESSION['user_id'] ?? 0;

// 撈出使用者所有捐款資料 + 專案狀態
$sql = "SELECT d.donation_amount, d.donation_time, d.project_id, 
               p.title, p.end_date, p.funding_goal,
               (SELECT SUM(dr.donation_amount) 
                FROM donation_record dr 
                WHERE dr.project_id = d.project_id) AS total_donated
        FROM donation_record d
        JOIN fundraising_projects p ON d.project_id = p.project_id
        WHERE d.user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 加上 status 分類
foreach ($records as &$r) {
    $now = date('Y-m-d');
    $goal = $r['funding_goal'];
    $total = $r['total_donated'];
    $end = $r['end_date'];

    if ($now < $end) {
        $r['status'] = 'ongoing';
    } elseif ($total >= $goal) {
        $r['status'] = 'achieved';
    } else {
        $r['status'] = 'failed';
    }

    $r['date'] = date('Y.m.d', strtotime($r['donation_time']));
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>孵仁 - 我的募資紀錄</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../css/member_fundings.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="donation-wrapper">
        <!-- Tabs -->
        <div class="donation-tabs">
            <button class="tab active" data-tab="ongoing">進行中募資</button>
            <button class="tab" data-tab="achieved">已達標募資</button>
            <button class="tab" data-tab="failed">未達標募資</button>
        </div>

        <div id="refundNotice" class="refund-notice" style="display: none;">
            未達標募資，請留意後續退款作業。
        </div>

        <!-- Search -->
        <div class="donation-search">
            <input type="text" id="searchInput" placeholder="搜尋募資紀錄" />
            <button onclick="renderDonations()"><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>

        <!-- 卡片容器 -->
        <div id="donationList"></div>

        <!-- Template -->
        <template id="donation-card-template">
            <div class="donation-card">
                <div class="card-title">{{title}}</div>
                <div class="card-amount">捐款 {{amount}} 元</div>
                <div class="card-date">日期: {{date}}</div>
                <a class="card-link" href="../funding_detail.php?project_id={{project_id}}" target="_blank">
                    <div class="card-detail">募資詳細頁面</div>
                </a>
            </div>
        </template>
    </div>

    <script>
        const data = <?= json_encode($records, JSON_UNESCAPED_UNICODE) ?>;
        const container = document.getElementById('donationList');
        const template = document.getElementById('donation-card-template').innerHTML;

        // 設定 tab 切換
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                renderDonations();
            });
        });

        function renderDonations() {
            const keyword = document.getElementById('searchInput').value.toLowerCase();
            const activeTab = document.querySelector('.tab.active').dataset.tab;

            const notice = document.getElementById('refundNotice');
            notice.style.display = (activeTab === 'failed') ? 'block' : 'none';

            container.innerHTML = '';

            let hasResult = false;

            data.forEach(item => {
                const matchesStatus = item.status === activeTab;
                const matchesKeyword = item.title.toLowerCase().includes(keyword);

                if (matchesStatus && matchesKeyword) {
                    const card = template
                        .replace('{{title}}', item.title)
                        .replace('{{amount}}', item.donation_amount)
                        .replace('{{date}}', item.date)
                        .replace('{{project_id}}', item.project_id);
                    container.insertAdjacentHTML('beforeend', card);
                    hasResult = true;
                }
            });

            if (!hasResult) {
                container.innerHTML = '<div class="no-result">查無符合條件的募資紀錄</div>';
            }
        }

        // 初次渲染
        renderDonations();
    </script>
</body>
</html>
