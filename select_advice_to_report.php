<?php
require_once("db_connection.php");

$threshold = 1;

$sql = "SELECT advice_id, advice_title, agree, advice_state 
        FROM advice 
        WHERE agree >= ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$threshold]);
$adviceList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>建言狀態回報</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/advice_select.css">
</head>
<body>
    <div class="container">
        <h1>建言狀態回報</h1>
        <p class="subtitle">選擇欲回報進度的建言</p>

        <!-- 美化 Tabs -->
        <div class="tabs-container">
            <ul class="tabs" id="tabs">
                <li class="tab active" data-tab="notRepliedTab"> 未處理建言</li>
                <li class="tab" data-tab="repliedTab">已回覆建言</li>
            </ul>
            <div class="tab-underline"></div>
        </div>

        <!-- Tabs 對應內容 -->
        <div class="tab-content" id="notRepliedTab">
            <ul id="notRepliedList" class="advice-list"></ul>
        </div>
        <div class="tab-content" id="repliedTab" style="display: none;">
            <ul id="repliedList" class="advice-list"></ul>
        </div>
    </div>

    <!-- Template -->
    <template id="adviceItemTemplate">
        <li class="advice-item">
            <div class="advice-title">
                <strong class="title"></strong>
                <span class="tag state"></span>
            </div>
            <div class="advice-meta">
                附議人數：<span class="agree"></span>
                <a class="report-link" href="#">回報狀態</a>
            </div>
        </li>
    </template>

    <script>
        const adviceData = <?= json_encode($adviceList, JSON_UNESCAPED_UNICODE) ?>;
        const template = document.getElementById('adviceItemTemplate');
        const notRepliedList = document.getElementById('notRepliedList');
        const repliedList = document.getElementById('repliedList');

        adviceData.forEach(advice => {
            const clone = template.content.cloneNode(true);
            clone.querySelector('.title').textContent = advice.advice_title;
            clone.querySelector('.agree').textContent = advice.agree;
            clone.querySelector('.state').textContent = advice.advice_state;
            clone.querySelector('.report-link').href = 'report_advice_progress.php?advice_id=' + advice.advice_id;

            if (advice.advice_state === '已回覆') {
                repliedList.appendChild(clone);
            } else {
                notRepliedList.appendChild(clone);
            }
        });

        // Tab 切換 + 底線動畫
        const tabs = document.querySelectorAll(".tab");
        const underline = document.querySelector(".tab-underline");
        const tabContents = document.querySelectorAll(".tab-content");

        function updateUnderline(target) {
            const rect = target.getBoundingClientRect();
            const containerRect = target.parentElement.getBoundingClientRect();
            underline.style.left = `${rect.left - containerRect.left}px`;
            underline.style.width = `${rect.width}px`;
        }

        tabs.forEach(tab => {
            tab.addEventListener("click", () => {
                tabs.forEach(t => t.classList.remove("active"));
                tab.classList.add("active");
                updateUnderline(tab);

                const targetId = tab.dataset.tab;
                tabContents.forEach(div => div.style.display = div.id === targetId ? "block" : "none");
            });
        });

        window.addEventListener("load", () => {
            const activeTab = document.querySelector(".tab.active");
            if (activeTab) updateUnderline(activeTab);
        });

        window.addEventListener("resize", () => {
            const activeTab = document.querySelector(".tab.active");
            if (activeTab) updateUnderline(activeTab);
        });
    </script>
</body>
</html>
