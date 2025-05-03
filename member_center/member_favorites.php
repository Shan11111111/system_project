<?php session_start(); ?>
<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>孵仁</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../css/member_favorites.css">
    <!-- Swiper -->
    <link rel="stylesheet" href="https://unpkg.com/swiper@11/swiper-bundle.min.css">

    <!-- cdn link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- 引入 SweetAlert2 :美觀彈出未登入警告圖示-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <?php
    include '../db_connection.php';
    ?>

    <div>
        <template id="suggestion-template">
            <img src="{{imgSrc}}" alt="建言圖">
            <div class="suggestion-content">
                <div class="suggestion-title">{{title}}</div>
                <div class="suggestion-meta">
                    <div class="data">
                        <span class="status-tag {{statusClass}}">{{statusText}}</span>
                        <span>附議數：{{comments}}</span>
                        <span><i class="fa-solid fa-comment"></i>：{{commentCount}}</span>
                        <span>分類: {{category}}</span>
                    </div>
                    <div class="date">
                        <i class="fa-solid fa-clock"></i>
                        <span>{{deadline}}</span>
                        <span>發布日：{{publishDate}}</span>
                    </div>
                </div>
            </div>
        </template>

        <script>
            const categoryMap = {
                "all": "全部分類",
                "equipment": "設施改善",
                "academic": "學術發展",
                "club": "社團活動",
                "welfare": "公益關懷",
                "environment": "環保永續",
                "other": "其他"
            };

            // 全域變數
            let currentCategory = 'all';
            let currentKeyword = '';
            let currentSort = 'new';
            let currentTab = 'active'; // active 或 ended
            let data = [];
            let rawData = [];
            let fetchedOnce = false; // ⭐新增一個旗子

            let currentPage = 1;
            const itemsPerPage = 10;


            function fetchData() {
                const url = `../advice_function/dealwith_advice_search.php?category=${currentCategory}&keyword=${encodeURIComponent(currentKeyword)}&sort=${currentSort}`;

                fetch(url)
                    .then(response => response.json())
                    .then(json => {
                        if (json.no_result) {
                            data = [];
                            if (!fetchedOnce) {
                                rawData = []; // ⭐只在第一次沒有資料時清空 rawData
                            }
                            renderSuggestions();
                            renderHighlight();
                            renderPagination(0);
                            return;
                        }
                        if (!fetchedOnce) {
                            rawData = json; // ⭐只在第一次 fetch 成功時存 rawData
                            fetchedOnce = true; // ⭐之後不再覆蓋 rawData
                        }
                        data = json; // 一直更新 data
                        renderSuggestions();
                        renderHighlight();
                    })
                    .catch(error => {
                        console.error("載入失敗：", error);
                    });
            }

            function renderSuggestions() {
                const list = document.getElementById('suggestion-list');
                list.innerHTML = '';

                const filtered = data.filter(item => {
                    if (currentTab === 'active') {
                        return item.has_response === false && item.status === 'active';
                    } else if (currentTab === 'ended') {
                        return item.has_response === false && (item.status === 'ended-passed' || item.status === 'ended-notpassed');
                    } else if (currentTab === 'responed') {
                        return item.has_response === true;
                    }
                    return false;
                });

                // 查無結果
                if (filtered.length === 0) {
                    const noResult = document.createElement('div');
                    noResult.className = 'no-result';
                    noResult.innerHTML = '<p>查無結果</p>';
                    list.appendChild(noResult);
                    renderPagination(0); // 分頁清空
                    return; // 不再往下畫建言卡片
                }


                const paginated = filtered.slice((currentPage - 1) * itemsPerPage, currentPage * itemsPerPage);

                paginated.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'suggestion';
                    div.onclick = () => {
                        window.location.href = `advice_detail.php?advice_id=${item.advice_id}`;
                    };

                    const imagePath = item.file_path || 'uploads/homepage.png';
                    const publishDate = item.announce_date || '未知';
                    const categoryText = categoryMap[item.category] || item.category || '無';
                    const remainingDays = Math.max(0, 30 - item.days_elapsed);

                    let template = '';
                    if (currentTab === 'ended') {
                        template = document.getElementById('suggestion-ended-template').innerHTML
                            .replace('{{imgSrc}}', imagePath)
                            .replace('{{title}}', item.advice_title)
                            .replace('{{comments}}', item.support_count)
                            .replace('{{statusClass}}', item.status === 'ended-passed' ? 'status-passed' : 'status-failed')
                            .replace('{{statusText}}', item.status === 'ended-passed' ? '已達標' : '未達標')
                            .replace('{{publishDate}}', publishDate);
                    } else if (currentTab === 'responed') {
                        template = document.getElementById('suggestion-responed-template').innerHTML
                            .replace('{{imgSrc}}', imagePath)
                            .replace('{{title}}', item.advice_title)
                            .replace('{{comments}}', item.support_count)
                            .replace('{{commentCount}}', item.comment_count)
                            .replace('{{category}}', categoryText)
                            .replace('{{publishDate}}', publishDate);
                    } else {
                        template = document.getElementById('suggestion-active-template').innerHTML
                            .replace('{{imgSrc}}', imagePath)
                            .replace('{{title}}', item.advice_title)
                            .replace('{{comments}}', item.support_count)
                            .replace('{{commentCount}}', item.comment_count)
                            .replace('{{category}}', categoryText)
                            .replace('{{deadline}}', `剩 ${remainingDays} 天`)
                            .replace('{{publishDate}}', publishDate);
                    }

                    div.innerHTML = template;
                    list.appendChild(div);
                });

                renderPagination(filtered.length);
            }

            function renderPagination(totalItems) {
                const pagination = document.getElementById('pagination');
                pagination.innerHTML = '';

                const totalPages = Math.ceil(totalItems / itemsPerPage);
                if (totalPages <= 1) return;

                if (currentPage > 1) {
                    const prev = document.createElement("button");
                    prev.textContent = "←";
                    prev.onclick = () => {
                        currentPage--;
                        renderSuggestions();
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    };
                    pagination.appendChild(prev);
                }

                for (let i = 1; i <= totalPages; i++) {
                    const pageBtn = document.createElement("button");
                    pageBtn.textContent = i;
                    if (i === currentPage) {
                        pageBtn.disabled = true;
                        pageBtn.classList.add("active");
                    }
                    pageBtn.onclick = () => {
                        currentPage = i;
                        renderSuggestions();
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    };
                    pagination.appendChild(pageBtn);
                }

                if (currentPage < totalPages) {
                    const next = document.createElement("button");
                    next.textContent = "→";
                    next.onclick = () => {
                        currentPage++;
                        renderSuggestions();
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    };
                    pagination.appendChild(next);
                }
            }
        </script>




</body>

</html>