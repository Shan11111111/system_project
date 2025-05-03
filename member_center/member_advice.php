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
    <link rel="stylesheet" href="../css/member_function.css">
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
    <div class="advice_space">
        <div class="filter-bar">
            <div class="search_text">
                <input type="text" id="search" placeholder="請輸入關鍵字" />
                <button onclick="search()"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
            <div class="sort-wrapper">
                <button class="sort" id="sortBtn" onclick="toggleSortMenu()">
                    <span id="sortLabel">排序</span> <i class="fa-solid fa-filter"></i>
                </button>
                <div id="sortMenu" class="sort-menu">
                    <div onclick="setSort('hot')" data-sort="hot">最熱門</div>
                    <div onclick="setSort('new')" data-sort="new">最新</div>
                    <div onclick="setSort('deadline')" data-sort="deadline">最舊</div>
                </div>
            </div>
        </div>
        <hr style=" border-color:black;" />

        <div id="suggestion-list"></div>
        <div class="pagination" id="pagination"></div>
    </div>

    <template id="suggestion-template">
        <img src="{{imgSrc}}" alt="建言圖">
        <div class="suggestion-content">
            <div class="suggestion-title">{{title}}</div>
            <div class="suggestion-meta">
                <span class="badge type-label">{{typeText}}</span>
                <span>附議數：{{comments}}</span>
                {{commentHTML}}
                {{categoryHTML}}
                {{deadlineHTML}}
                <span>發布日：{{publishDate}}</span>
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
        let currentKeyword = '';
        let currentSort = 'new';
        let data = [];

        let currentPage = 1;
        const itemsPerPage = 10;

        // 監聽

        function search() {
            currentKeyword = document.getElementById('search').value.trim();
            currentPage = 1;
            fetchData();
        }

        function toggleSortMenu() {
            document.getElementById('sortMenu').classList.toggle('show');
        }

        function setSort(sortType) {
            currentSort = sortType;
            currentPage = 1;
            document.getElementById('sortLabel').textContent = (sortType === 'hot') ? '最熱門' : (sortType === 'deadline') ? '最舊' : '最新';
            document.getElementById('sortMenu').classList.remove('show');
            fetchData();
        }

        document.getElementById('search').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // 防止表單送出（雖然你沒有 form，但保險）
                search(); // 呼叫你的搜尋函式
            }
        });

        function fetchData() {
            const url = `mem_function.php?keyword=${encodeURIComponent(currentKeyword)}&sort=${currentSort}`;

            fetch(url)
                .then(response => response.json())
                .then(json => {
                    if (json.no_result) {
                        data = [];
                        renderSuggestions();
                        renderPagination(0);
                        return;
                    }

                    data = json;
                    renderSuggestions();
                    renderPagination(data.length);
                })
                .catch(error => {
                    console.error("載入失敗：", error);
                });
        }

        function renderSuggestions() {
            const list = document.getElementById('suggestion-list');
            list.innerHTML = '';

            if (data.length === 0) {
                const noResult = document.createElement('div');
                noResult.className = 'no-result';
                noResult.innerHTML = '<p>你未提出過建言</p>';
                list.appendChild(noResult);
                renderPagination(0);
                return;
            }

            const paginated = data.slice((currentPage - 1) * itemsPerPage, currentPage * itemsPerPage);

            paginated.forEach(item => {
                const div = document.createElement('div');
                div.className = 'suggestion';
                div.onclick = () => {
                    window.location.href = `advice_detail.php?advice_id=${item.advice_id}`;
                };

                const imagePath = item.file_path ? `../${item.file_path}` : '../uploads/homepage.png';
                const publishDate = item.announce_date || '未知';
                const categoryText = categoryMap[item.category] || item.category || '無';
                const typeTextMap = {
                    'active': '進行中',
                    'passed': '已達標',
                    'expired': '未達標',
                    'responed': '已回覆'
                };
                const typeText = typeTextMap[item.status] || '';

                const remainingDays = Math.max(0, 30 - item.days_elapsed);


                const commentHTML = item.comment_count !== undefined ? `<span><i class="fa-solid fa-comment"></i>：${item.comment_count}</span>` : '';
                const categoryHTML = item.category ? `<span>分類: ${categoryText}</span>` : '';
                const deadlineHTML = item.status === 'active' ? `<span>剩 ${remainingDays} 天</span>` : '';

                let template = document.getElementById('suggestion-template').innerHTML
                    .replace('{{imgSrc}}', imagePath)
                    .replace('{{title}}', item.advice_title)
                    .replace('{{comments}}', item.support_count)
                    .replace('{{commentHTML}}', commentHTML)
                    .replace('{{categoryHTML}}', categoryHTML)
                    .replace('{{deadlineHTML}}', deadlineHTML)
                    .replace('{{publishDate}}', publishDate)
                    .replace('{{typeText}}', typeText);



                div.innerHTML = template;
                list.appendChild(div);
                // 塞進 HTML 後再處理 type-label badge 的顏色
                const badge = div.querySelector('.type-label');
                badge.classList.remove('badge-active', 'badge-ended', 'badge-responed', 'badge-passed', 'badge-expired');
                switch (item.status) {
                    case 'active':
                        badge.classList.add('badge-active');
                        break;
                    case 'passed':
                        badge.classList.add('badge-passed');
                        break;
                    case 'expired':
                        badge.classList.add('badge-expired');
                        break;
                    case 'responed':
                        badge.classList.add('badge-responed');
                        break;
                }


            });

            renderPagination(data.length);
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
        fetchData(); // ✅ 網頁載入就先自動抓資料


        window.addEventListener('load', function() {
            const height = document.body.scrollHeight;
            if (window.parent) {
                const iframe = window.parent.document.querySelector('iframe[name="memberContent"]');
                if (iframe) {
                    iframe.style.height = height + 'px';
                }
            }
        });
    </script>


</body>

</html>