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
    <link rel="stylesheet" href="css/advice_search.css">
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
    include 'db_connection.php';
    ?>
    <!--navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <!-- LOGO -->
            <a href="homepage.php" class="logo">
                <img src="img/logo.png" style="width: 90px;">
            </a>
            <!-- 漢堡按鈕 -->
            <div class="menu-toggle" id="mobile-menu-toggle">☰</div>

            <!-- 桌面版選單 -->
            <div class="nav-center desktop-menu">
                <div class="dropdown">
                    <button class="dropbtn">建言</button>
                    <div class="dropdown-content">
                        <?php if (isset($_SESSION['user_id'])) { ?>
                            <a href="submitadvice.php">提交建言</a>
                        <?php } else { ?>
                            <a href="javascript:void(0);" onclick="showLoginAlert()">提交建言</a>
                            <script>
                                function showLoginAlert() {
                                    Swal.fire({
                                        icon: 'warning', // 圖示類型
                                        title: '請先登入',
                                        text: '發布建言為學生與教職人員專屬功能！',
                                        confirmButtonText: '確定',
                                        confirmButtonColor: '#3085d6',
                                        focusConfirm: false, // 禁用自動聚焦
                                        didOpen: () => {
                                            // 禁用滾動
                                            document.body.style.overflow = 'hidden';
                                        },
                                        didClose: () => {
                                            // 恢復滾動
                                            document.body.style.overflow = '';
                                        }
                                    });
                                }
                            </script>
                        <?php } ?>

                        <a href="advice_search.php">建言瀏覽</a>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="dropbtn">募資</button>
                    <div class="dropdown-content">
                        <a href="ongoing_funding_search.php">進行中募資</a>
                        <a href="due_funding_search.php">已結束募資</a>
                    </div>
                </div>
            </div>

            <div class="nav-right desktop-menu">
                <?php if (isset($_SESSION['user_id'])) { ?>
                    <a class="nav-item"><?php echo $_SESSION['user_id'] ?>會員專區</a>
                    <a href="javascript:void(0);" class="nav-item" id="logout-link">登出</a>
                    <script>
                        document.getElementById('logout-link').addEventListener('click', function() {
                            const confirmLogout = confirm("確定要登出嗎？");
                            if (confirmLogout) {
                                window.location.href = "logout.php";
                            }
                        });
                    </script>
                <?php } else { ?>
                    <a href="login.php" class="nav-item">登入</a>
                    <a href="register.php" class="nav-item">註冊</a>
                <?php } ?>
            </div>
        </div>

        <!-- 手機版選單 -->
        <div class="mobile-menu" id="mobile-menu">
            <div class="dropdown">
                <button class="dropbtn">建言</button>
                <div class="dropdown-content">
                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <a href="submitadvice.php">提交建言</a>
                    <?php } else { ?>
                        <a href="javascript:void(0);" onclick="showLoginAlert()">提交建言</a>
                    <?php } ?>

                    <a href="advice_search.php">最新建言</a>
                    <a href="advice_search.php">熱門建言</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="dropbtn">募資</button>
                <div class="dropdown-content">
                    <a href="ongoing_funding_search.php">進行中募資</a>
                    <a href="due_funding_search.php">已結束募資</a>
                </div>
            </div>

            <?php if (isset($_SESSION['user_id'])) { ?>
                <a class="nav-item"><?php echo $_SESSION['user_id'] ?>會員專區</a>
                <a class="nav-item" id="logout-link-mobile">登出</a>
                <script>
                    document.getElementById('logout-link-mobile').addEventListener('click', function() {
                        const confirmLogout = confirm("確定要登出嗎？");
                        if (confirmLogout) {
                            window.location.href = "logout.php";
                        }
                    });
                </script>
            <?php } else { ?>
                <a href="login.php" class="nav-item">登入</a>
                <a href="register.php" class="nav-item">註冊</a>
            <?php } ?>
        </div>
    </nav>

    <div class="container">
        <div class="highlight-advice">
            <div class="highlight">
                <div class="highlight_content" id="highlight-title">快要達標的建言：</div>
                <div id="highlight-count"></div>
            </div>
            <div class="highlight_btn pulse" id="highlight-action">🐣 點我 +1 力挺！</div>
        </div>
        <div class="advice_space">
            <div class="tabs">
                <div class="tab active" onclick="switchTab('active')">進行中</div>
                <div class="tab" onclick="switchTab('ended')">已結束</div>
            </div>
            <hr style=" border-color:black;" />
            <div class="filter-bar">
                <div class="search_text">
                    <!-- 修正後的下拉選單 -->
                    <select id="category">
                        <option value="all">全部分類</option>
                        <option value="equipment">設施改善</option>
                        <option value="academic">學術發展</option>
                        <option value="club">社團活動</option>
                        <option value="welfare">公益關懷</option>
                        <option value="environment">環保永續</option>
                        <option value="other">其他</option>
                    </select>
                    <input type="text" id="search" placeholder="請輸入關鍵字" />
                    <button onclick="search()"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <div class="search_sort">
                    <button id="hotBtn" onclick="toggleArrow(this)">HOT<i class="fa-solid fa-caret-up"></i></button>
                    <button id="newBtn" onclick="toggleArrow(this)">NEW<i class="fa-solid fa-caret-up"></i></button>
                </div>
            </div>

            <div id="suggestion-list"></div>
            <div class="pagination" id="pagination"></div>
        </div>
    </div>

    <div class="footer">footer</div>

    <!-- Templates -->
    <template id="suggestion-active-template">
        <img src="{{imgSrc}}" alt="建言圖">
        <div class="suggestion-content">
            <div class="suggestion-title">{{title}}</div>
            <div class="suggestion-meta">
                <div class="data">
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

    <template id="suggestion-ended-template">
        <img src="{{imgSrc}}" alt="建言圖">
        <div class="suggestion-content">
            <div class="suggestion-title">{{title}}</div>
            <div class="suggestion-meta">
                <span class="suggestion-status {{statusClass}}">{{statusText}}</span>
                <span>發布日：{{publishDate}}</span>
            </div>
        </div>
    </template>

    <script>
        // 全域變數：搜尋、排序狀態

        let currentCategory = 'all'; // 下拉預設：全部
        let currentKeyword = ''; // 關鍵字
        let currentSort = 'new'; // 'hot' / 'new'
        let currentOrder = 'desc'; // 'asc' / 'desc'
        let currentTab = 'active'; // 'active' / 'ended'

        // 分頁使用
        let data = [];
        let currentPage = 1;
        const itemsPerPage = 10;

        // 頁面載入
        document.addEventListener('DOMContentLoaded', () => {
            // 預設：NEW desc
            fetchData();
        });

        // 監聽分類下拉選單 (不用再按按鈕就自動刷新)
        document.getElementById('category').addEventListener('change', function() {
            currentCategory = this.value;
            currentPage = 1;
            fetchData();
        });

        // 關鍵字搜尋
        function search() {
            currentKeyword = document.getElementById('search').value.trim();
            currentPage = 1;
            fetchData();
        }

        // HOT / NEW 按鈕
        function toggleArrow(btn) {
            const btnText = btn.textContent.replace(/(\s|<i.*<\/i>)/g, '').toLowerCase();
            // 可能是 'hot' / 'new'
            const icon = btn.querySelector("i");

            // 若按下的跟 currentSort 相同，就切換 asc / desc
            // 若不同，就更換排序欄位並預設 desc
            if ((btnText === 'hot' && currentSort === 'hot') ||
                (btnText === 'new' && currentSort === 'new')) {
                currentOrder = (currentOrder === 'desc') ? 'asc' : 'desc';
            } else {
                currentSort = btnText;
                currentOrder = 'desc';
            }

            // 視覺：箭頭方向
            icon.classList.toggle("fa-caret-up");
            icon.classList.toggle("fa-caret-down");

            currentPage = 1;
            fetchData();
        }

        // 拉取資料 (後端 dealwith_advice_date.php)
        function fetchData() {
            // 組裝 URL (GET 參數)
            const url = `advice_function/dealwith_advice_date.php?category=${currentCategory}&keyword=${encodeURIComponent(currentKeyword)}&sort=${currentSort}&order=${currentOrder}`;
            console.log("[fetchData] URL:", url);

            fetch(url)
                .then(response => response.json())
                .then(json => {
                    console.log("取得建言資料：", json);
                    data = json;
                    renderSuggestions();
                    renderHighlight(); // 更新快達標區塊
                })
                .catch(error => {
                    console.error("載入建言資料失敗：", error);
                });
        }

        // 切換「進行中 / 已結束」標籤
        function switchTab(tab) {
            currentTab = tab;
            currentPage = 1;
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            if (tab === 'active') {
                document.querySelectorAll('.tab')[0].classList.add('active');
            } else {
                document.querySelectorAll('.tab')[1].classList.add('active');
            }
            renderSuggestions();
        }
        // 前端的「英文→中文」對照表
        const categoryMap = {
            "all": "全部分類", // 跟後端的 'all' 相對應，如果有需要映射可以寫，不需要就可省略
            "equipment": "設施改善",
            "academic": "學術發展",
            "club": "社團活動",
            "welfare": "公益關懷",
            "environment": "環保永續",
            "other": "其他"
        };

        // 顯示建言列表
        function renderSuggestions() {
            const list = document.getElementById('suggestion-list');
            list.innerHTML = '';

            // 根據「進行中/已結束」分流
            const filtered = data.filter(item => {
                if (currentTab === 'active') {
                    return item.status === 'active';
                } else {
                    return (item.status === 'ended-passed' || item.status === 'ended-notpassed');
                }
            });

            // 做分頁
            const paginated = filtered.slice((currentPage - 1) * itemsPerPage, currentPage * itemsPerPage);

            paginated.forEach(item => {
                const div = document.createElement('div');
                div.className = 'suggestion';
                div.onclick = () => {
                    window.location.href = `advice_detail.php?advice_id=${item.advice_id}`;

                };

                const imagePath = item.file_path || 'uploads/homepage.png';
                const remainingDays = Math.max(0, 30 - item.days_elapsed);
                const publishDate = item.announce_date || '未知';
                const categoryText = categoryMap[item.category] || item.category || '無';

                let template = '';
                if (currentTab === 'ended') {
                    template = document.getElementById('suggestion-ended-template').innerHTML
                        .replace('{{imgSrc}}', imagePath)
                        .replace('{{title}}', item.advice_title)
                        .replace('{{statusClass}}', item.status === 'ended-passed' ? 'status-passed' : 'status-failed')
                        .replace('{{statusText}}', item.status === 'ended-passed' ? '通過' : '未通過')
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

        // 分頁按鈕
        function renderPagination(totalItems) {
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            if (currentPage > 1) {
                const prev = document.createElement('button');
                prev.textContent = '上一頁';
                prev.onclick = () => {
                    currentPage--;
                    renderSuggestions();
                };
                pagination.appendChild(prev);
            }

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                if (i === currentPage) btn.disabled = true;
                btn.onclick = () => {
                    currentPage = i;
                    renderSuggestions();
                };
                pagination.appendChild(btn);
            }

            if (currentPage < totalPages) {
                const next = document.createElement('button');
                next.textContent = '下一頁';
                next.onclick = () => {
                    currentPage++;
                    renderSuggestions();
                };
                pagination.appendChild(next);
            }
        }

        // 「快要達標的建言」顯示
        function renderHighlight() {
            // 找出「active」且 support_count < 100 的最高附議
            const target = data
                .filter(item => item.status === 'active' && item.support_count < 100)
                .sort((a, b) => b.support_count - a.support_count)[0];

            if (target) {
                const remain = 10 - target.support_count;
                document.getElementById('highlight-title').textContent = `快要達標的建言：${target.advice_title}`;
                document.getElementById('highlight-count').textContent = `還差 ${remain} 人即可達成`;
                document.getElementById('highlight-action').style.display = 'inline-block';
                document.getElementById('highlight-action').onclick = () => {
                    window.location.href = `advice_detail.php?advice_id=${target.advice_id}`;
                };
            } else {
                document.getElementById('highlight-title').textContent = '目前沒有快要達標的建言';
                document.getElementById('highlight-count').textContent = '';
                document.getElementById('highlight-action').style.display = 'none';
            }
        }

        // 手機選單
        document.getElementById('mobile-menu-toggle').addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('active');
        });

        document.querySelectorAll('.mobile-menu .dropdown .dropbtn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                btn.parentElement.classList.toggle('active');
            });
        });
    </script>

</body>

</html>