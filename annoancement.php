<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>孵仁公告</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/anno.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <nav class="navbar">
        <div class="nav-container">
            <!-- LOGO -->
            <a href="homepage.php">
                <div class="logo">
                    <img src="img/logo.png" style="width: 90px;">
                </div>
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
                                            // 禁用滾动
                                            document.body.style.overflow = 'hidden';

                                        },
                                        didClose: () => {
                                            // 恢復滾動
                                            document.body.style.overflow = '';
                                            // 恢復滾動位置

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
                    <a class="nav-item" href="<?php if ($_SESSION['level'] == 'student' || $_SESSION['level'] == 'teacher') {
                                                    echo 'member_center.php';
                                                } else if ($_SESSION['level'] == 'office') {
                                                    echo 'funding/office_assignments.php';
                                                } else if ($_SESSION['level'] == 'manager') {
                                                    echo 'manager/advice_manager.php';
                                                } ?>"><?php echo $_SESSION['user_id'] ?>會員專區</a>

                    <a href="javascript:void(0);" class="nav-item" id="logout-link">登出</a>
                    <script>
                        document.getElementById('logout-link').addEventListener('click', function() {
                            // 彈出確認視窗
                            const confirmLogout = confirm("確定要登出嗎？");
                            if (confirmLogout) {
                                // 如果用戶選擇確定，導向登出頁面
                                window.location.href = "logout.php";
                            }
                            // 如果用戶選擇取消，什麼都不做
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
                                        // 恢復滾动
                                        document.body.style.overflow = '';
                                        // 恢復滾動位置
                                        window.scrollTo(0, scrollTop);
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
                    <a href="#">已結束募資</a>
                </div>
            </div>
            <?php if (isset($_SESSION['user_id'])) { ?>
                <a class="nav-item"><?php echo $_SESSION['user_id'] ?>會員專區</a>
                <a class="nav-item" id="logout-link-mobile">登出</a>
                <script>
                    document.getElementById('logout-link-mobile').addEventListener('click', function() {
                        // 彈出確認視窗
                        const confirmLogout = confirm("確定要登出嗎？");
                        if (confirmLogout) {
                            // 如果用戶選擇確定，導向登出頁面
                            window.location.href = "logout.php";
                        }
                        // 如果用戶選擇取消，什麼都不做
                    });
                </script>
            <?php } else { ?>
                <a href="login.php" class="nav-item">登入</a>
                <a href="register.php" class="nav-item">註冊</a>
            <?php } ?>

        </div>
    </nav>

    <div class="container">
        <h2>公告</h2>
        <div class="anno_space">
            <div class="tabs">
                <div class="tab active" data-tab="all" onclick="switchTab('all')">全部</div>
                <div class="tab" data-tab="advice" onclick="switchTab('advice')">建言</div>
                <div class="tab" data-tab="fund" onclick="switchTab('fund')">募資</div>
                <div class="tab" data-tab="system" onclick="switchTab('system')">系統</div>
            </div>
            <hr />
            <div class="filter-bar d-flex justify-content-between mb-3">
                <div>
                    <input type="text" id="search" placeholder="請輸入關鍵字" />
                    <button onclick="search()"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <div class="sort-wrapper">
                    <button class="sort" id="sortBtn" onclick="toggleSortMenu()">
                        <span id="sortLabel">排序</span> <i class="fa-solid fa-filter"></i>
                    </button>
                    <div id="sortMenu" class="sort-menu" style="display: none;">
                        <div onclick="setSort('new')">最新</div>
                        <div onclick="setSort('old')">最舊</div>
                    </div>
                </div>
            </div>
            <div id="anno-list"></div>
            <div class="pagination" id="pagination"></div>
        </div>
    </div>

    <!-- 公告卡片樣板 -->
    <template id="anno-template">
        <div class="anno-card">
            <div class="anno-content">
                <div class="anno-title">{{title}}</div>
                <div class="anno-meta">
                    <span class="badge type-label"></span> <!-- 類型放這 -->
                    <span>發布單位：{{author}}</span>
                    <span>發布日：{{publishDate}}</span>
                </div>
            </div>
        </div>
    </template>
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
                    <div>意見箱</div>
                </div>
                <div class="connection">
                    <div>242新北市新莊區中正路510號.</div>
                    <div>電話:(02)2905-2000</div>
                </div>
            </div>

        </div>

    </footer>

    <script>
        let currentKeyword = '';
        let currentSort = 'new';
        let currentCategory = 'all';
        let data = [];
        let currentPage = 1;
        const itemsPerPage = 10;

        function switchTab(category) {
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            document.querySelector(`.tab[data-tab="${category}"]`).classList.add('active');
            currentCategory = category;
            currentPage = 1;
            fetchData();
        }

        function search() {
            currentKeyword = document.getElementById('search').value.trim();
            currentPage = 1;
            fetchData();
        }

        function toggleSortMenu() {
            const menu = document.getElementById('sortMenu');
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        }

        function setSort(sortType) {
            currentSort = sortType;
            document.getElementById('sortLabel').textContent = sortType === 'old' ? '最舊' : '最新';
            document.getElementById('sortMenu').style.display = 'none';
            fetchData();
        }

        document.getElementById('search').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') search();
        });

        function fetchData() {
            const url = `anno_search/anno_fetch.php?keyword=${encodeURIComponent(currentKeyword)}&sort=${currentSort}&category=${encodeURIComponent(currentCategory)}`;

            fetch(url)
                .then(res => res.json())
                .then(json => {
                    data = json.no_result ? [] : json;
                    renderList();
                    renderPagination(data.length);
                })
                .catch(err => {
                    console.error("資料載入失敗：", err);
                });
        }

        function renderList() {
            const list = document.getElementById('anno-list');
            list.innerHTML = '';
            const paginated = data.slice((currentPage - 1) * itemsPerPage, currentPage * itemsPerPage);
            if (paginated.length === 0) {
                list.innerHTML = '<p class="text-muted">查無公告。</p>';
                return;
            }

            const template = document.getElementById('anno-template').innerHTML;
            paginated.forEach(item => {
                const div = document.createElement('div');
                div.innerHTML = template
                    .replace('{{title}}', item.title)
                    .replace('{{author}}', item.author)
                    .replace('{{publishDate}}', item.update_at);

                // 類別標籤處理：塞入文字與顏色 class
                const badge = div.querySelector('.type-label');
                badge.textContent = item.category;
                const categoryMap = {
                    '建言': 'advice',
                    '募資': 'fund',
                    '系統': 'system'
                };

                badge.classList.remove('type-advice', 'type-fund', 'type-system'); // 先清除
                if (categoryMap[item.category]) {
                    badge.classList.add(`type-${categoryMap[item.category]}`);
                }
                div.classList.add('anno-item');
                div.onclick = () => {
                    window.location.href = `announcement_detail.php?id=${item.announcement_id}`;
                };
                list.appendChild(div);
            });
        }

        function renderPagination(totalItems) {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            if (totalPages <= 1) return;

            const createBtn = (text, page) => {
                const btn = document.createElement('button');
                btn.textContent = text;
                if (page === currentPage) btn.disabled = true;
                btn.onclick = () => {
                    currentPage = page;
                    renderList();
                    renderPagination(totalItems);
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                };
                return btn;
            };

            if (currentPage > 1) pagination.appendChild(createBtn('←', currentPage - 1));
            for (let i = 1; i <= totalPages; i++) pagination.appendChild(createBtn(i, i));
            if (currentPage < totalPages) pagination.appendChild(createBtn('→', currentPage + 1));
        }

        fetchData();
    </script>
</body>

</html>