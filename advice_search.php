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

            <div class="highlight_btn pulse " id="highlight-action">🐣 點我 +1 力挺！</div>
        </div>
        <div class="advice_space">
            <div class="tabs">
                <div class="tab active" data-tab="active" onclick="switchTab('active')">進行中</div>
                <div class="tab" data-tab="ended" onclick="switchTab('ended')">未受理</div>
                <div class="tab" data-tab="responed" onclick="switchTab('responed')">已回覆</div>
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

            <div id="suggestion-list"></div>
            <div class="pagination" id="pagination"></div>
        </div>
    </div>

    <footer class="footer">
        <div class="logo_space">
            <img src="img/logo.png" style="width: 150px;">
        </div>
        <div class="help_info">

        </div>
        <div class="help">
            <div class="help_title">幫助</div>
            <hr style="width: 150px;">
            <div class="help_content">
                <div>常見問題</div>
                <div>使用條款</div>
                <div>隱私條款</div>
            </div>
        </div>
        <div class="footer_info">
            <div class="info_title">相關資訊</div>
            <hr>

            <div class="info_content">
                <div class="school_info">
                    <div>關於我們</div>
                    <div>學校處室</div>
                    <div><a href="suggestion_box.php" style="color: black; text-decoration: none;">意見箱</a></div>
                </div>
                <div class="connection">
                    <div>242新北市新莊區中正路510號.</div>
                    <div>電話:(02)2905-2000</div>
                </div>
            </div>

        </div>

    </footer>

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
                <span>附議數：{{comments}}</span>
                <span>發布日：{{publishDate}}</span>
            </div>
        </div>
    </template>

    <template id="suggestion-responed-template">
        <img src="{{imgSrc}}" alt="建言圖">
        <div class="suggestion-content">
            <div class="suggestion-title">{{title}}</div>
            <div class="suggestion-meta">
                <span class="badge beef">已回覆</span>
                <span>附議數：{{comments}}</span>
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
        let currentCategory = 'all';
        let currentKeyword = '';
        let currentSort = 'new';
        let currentTab = 'active'; // active 或 ended
        let data = [];
        let rawData = [];
        let fetchedOnce = false; // ⭐新增一個旗子

        let currentPage = 1;
        const itemsPerPage = 10;

        // 監聽
        document.getElementById('category').addEventListener('change', function() {
            currentCategory = this.value;
            currentPage = 1;
            fetchData();
        });

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

        function switchTab(tab) {
            currentTab = tab;
            currentPage = 1;

            // 先移除所有 tab 的 active 樣式
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));

            // 再找到 data-tab 為 tab 的項目加上 active
            const selected = document.querySelector(`.tab[data-tab="${tab}"]`);
            if (selected) selected.classList.add('active');
            fetchData(); // ✅ 每次切 tab 也重新撈最新資料

        }

        document.getElementById('search').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // 防止表單送出（雖然你沒有 form，但保險）
                search(); // 呼叫你的搜尋函式
            }
        });

        function fetchData() {
            const url = `advice_function/dealwith_advice_search.php?category=${currentCategory}&keyword=${encodeURIComponent(currentKeyword)}&sort=${currentSort}`;

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

        function renderHighlight() {
            const target = rawData
                .filter(item => item.status === 'active' && item.support_count < 3)
                .sort((a, b) => {
                    if (b.support_count !== a.support_count) {
                        return b.support_count - a.support_count;
                    }
                    return new Date(a.announce_date) - new Date(b.announce_date);
                })[0]; // 取最接近達標的一個

            if (target) {
                const remain = Math.max(0, 3 - target.support_count);
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

        // 手機版漢堡選單
        document.getElementById('mobile-menu-toggle').addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('active');
        });

        // 手機版下拉展開
        document.querySelectorAll('.mobile-menu .dropdown .dropbtn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                btn.parentElement.classList.toggle('active');
            });
        });

        // 頁面初始化
        fetchData();
    </script>

</body>

</html>