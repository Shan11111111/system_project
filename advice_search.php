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

    <!--navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <!-- LOGO -->
            <div class="logo">
                <img src="img/logo.png" style="width: 90px;">
            </div>
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
                                            // 恢復滾動位置

                                        }
                                    });
                                }
                            </script>
                        <?php } ?>

                        <a href="advice_search.php">最新建言</a><!--之後要設(不知道是前端還後端)-->
                        <a href="advice_search.php">熱門建言</a>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="dropbtn">募資</button>
                    <div class="dropdown-content">
                        <a href="#">進行中計畫</a>
                        <a href="#">成功案例</a>
                    </div>
                </div>
            </div>

            <div class="nav-right desktop-menu">
                <?php if (isset($_SESSION['user_id'])) { ?>
                    <a class="nav-item"><?php echo $_SESSION['user_id'] ?>會員專區</a>
                    <a href="javascript:void(0);" class="nav-item" id="logout-link">登出</a>
                    <script>
                        document.getElementById('logout-link').addEventListener('click', function () {
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
                                        // 恢復滾動
                                        document.body.style.overflow = '';
                                        // 恢復滾動位置
                                        window.scrollTo(0, scrollTop);
                                    }
                                });
                            }
                        </script>
                    <?php } ?>

                    <a href="advice_search.php">最新建言</a>
                    <a href="advice_search.php">熱門建言</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="dropbtn">募資</button>
                <div class="dropdown-content">
                    <a href="#">進行中計畫</a>
                    <a href="#">成功案例</a>
                </div>
            </div>

            <?php if (isset($_SESSION['user_id'])) { ?>
                <a class="nav-item"><?php echo $_SESSION['user_id'] ?>會員專區</a>
                <a class="nav-item" id="logout-link-mobile">登出</a>
                <script>
                    document.getElementById('logout-link-mobile').addEventListener('click', function () {
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
        <!-- 快要達標 -->
        <div class="highlight">
            <div class="highlight_content">快要達標的建言</div>
            <div class="highlight_btn">去覆議</div>
        </div>
        <div class="highlight_title">
            <center>
                <p>快要達標的建言，還剩php人</p>
            </center>
        </div>
        <div class="advice_space">
            <!-- Tabs -->
            <div class="tabs">
                <div class="tab active" onclick="switchTab('active')">進行中</div>
                <div class="tab" onclick="switchTab('ended')">已結束</div>
            </div>
            <hr style="width=70%; border-color:black;">

            <!-- 選單 + 搜尋 -->
            <div class="filter-bar">
                <div class="search_text">
                    <select id="category">
                        <option value="">全部分類</option>
                        <option value="設施改善">設施改善</option>
                        <option value="學術發展">學術發展</option>
                        <option value="社團活動">社團活動</option>
                        <option value="公益活動">公益活動</option>
                        <option value="環保永續">環保永續</option>
                        <option value="其他">其他</option>
                    </select>

                    <input type="text" id="search" placeholder="請輸入關鍵字">
                    <button onclick="search()"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <div class="search_sort">
                    <button onclick="toggleSort('hot', this)">HOT <i class="fa-solid fa-caret-down"></i></button>
                    <button onclick="toggleSort('new', this)">NEW <i class="fa-solid fa-caret-down"></i></button>
                </div>

            </div>




            <!-- 新的建言表展示 -->

            <?php
            // Step 1: 連接資料庫
            $link = mysqli_connect('localhost', 'root');
            mysqli_select_db($link, "system_project");

            // Step 2: 處理搜尋功能
            $search = isset($_GET['search']) ? mysqli_real_escape_string($link, $_GET['search']) : '';
            $category = isset($_GET['category']) ? mysqli_real_escape_string($link, $_GET['category']) : '';

            ?>
            <!-- 建言列表 -->
            <div id="suggestion-list"></div>
            <div class="filter-bar">
                <form method="GET" action="">
                    <pre>





                    </pre>
                    
                    
                    <div class="tabs">
                        <div
                            class="tab <?php echo (!isset($_GET['status']) || $_GET['status'] == 'active') ? 'active' : ''; ?>">
                            <a href="?status=active">進行中</a>
                        </div>
                        <div
                            class="tab <?php echo (isset($_GET['status']) && $_GET['status'] == 'ended') ? 'active' : ''; ?>">
                            <a href="?status=ended">已結束</a>
                        </div>
                    </div>
                    <select id="category" name="category">
                        <option value=""><?php echo $category ? htmlspecialchars($category) : '全部分類'; ?></option>
                        <option value="設施改善">設施改善</option>
                        <option value="學術發展">學術發展</option>
                        <option value="社團活動">社團活動</option>
                        <option value="公益活動">公益活動</option>
                        <option value="環保永續">環保永續</option>
                        <option value="其他">其他</option>
                    </select>
                    <input type="text" name="search" placeholder="搜尋公告"
                        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>


                </form>
            </div>

            <?php

            $status = isset($_GET['status']) ? $_GET['status'] : 'active';

            // Step 3: 查詢公告資料，根據搜尋關鍵字來篩選標題或內容
            $sql = "SELECT a.advice_id,user_id,advice_title,advice_content,agree,category,advice_state,announce_date,img_data FROM advice a LEFT JOIN advice_image ai ON a.advice_id = ai.advice_id";
            $whereClauses = [];
            if ($search) {
                $whereClauses[] = "(advice_title LIKE '%$search%' OR advice_content LIKE '%$search%')";
            }
            if ($category) {
                $whereClauses[] = "category = '$category'";
            }
            if (count($whereClauses) > 0) {
                $sql .= " WHERE " . implode(" AND ", $whereClauses);
            }

            $result = mysqli_query($link, $sql);

            // Step 4: 顯示公告
            while ($row = mysqli_fetch_assoc($result)) {
                $advice_id = $row['advice_id'];
                $comment = 0; // 如果有評論，這裡可以改為從資料庫讀取
                $remainingDays = ceil((strtotime($row['announce_date'] . ' +30 days') - time()) / 86400);
                $remainingDays = max(0, $remainingDays); // 確保不會顯示負天數
            
                // 根據 status 過濾
                if (($status == "active" && $remainingDays > 0) || ($status == "ended" && $remainingDays == 0)) {
                    echo '<div class="suggestion" onclick="location.href=\'advice_detail.php?advice_id=' . htmlspecialchars($row['advice_id']) . '\'">
                        <img src="img/homepage.png" alt="建言圖">
                        <div class="suggestion-content">
                            <div class="suggestion-title">' . htmlspecialchars($row['advice_title']) . '</div>
                            <div class="suggestion-meta">
                                <div class="data">
                                    <span>附議數：' . (isset($row['agree']) ? $row['agree'] : 0) . '</span>
                                    <span><i class="fa-solid fa-comment"></i>：' . $comment . '</span>
                                </div>
                                <div class="date">
                                    <i class="fa-solid fa-clock"></i>
                                    <span>' . $remainingDays . '</span>
                                    <span>發布日：' . htmlspecialchars($row['announce_date']) . '</span>
                                </div>
                            </div>
                        </div>';

                    // 顯示標籤
                    $tags = explode(' ', $row['category']);
                    foreach ($tags as $tag) {
                        if (!empty($tag)) {
                            echo '<span class="tag">' . htmlspecialchars($tag) . '</span>';
                        }
                    }
                }






                echo '</div>';
            }
            ?>






            <!-- 分頁 -->
            <div class="pagination" id="pagination"></div>
        </div>
    </div>
    <div class="footer">footer</div>

    <script>
        // 點擊漢堡切換 menu
        document.getElementById('mobile-menu-toggle').addEventListener('click', function () {
            document.getElementById('mobile-menu').classList.toggle('active');
        });

        // 手機 dropdown 點擊展開
        document.querySelectorAll('.mobile-menu .dropdown .dropbtn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault(); // 防止跳頁
                const parent = btn.parentElement;
                parent.classList.toggle('active');
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            let currentTab = 'active'; // 預設顯示進行中的建議
            let currentPage = 1; // 預設顯示第1頁
            const itemsPerPage = 10; // 每頁顯示的項目數

            function switchTab(tab) {
                currentTab = tab; // 更新當前選擇的狀態
                currentPage = 1;  // 切換時回到第1頁
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab')[tab === 'active' ? 0 : 1].classList.add('active');
                renderSuggestions(); // 重新渲染建議列表
            }

            // 渲染建議列表
            function renderSuggestions() {
                const list = document.getElementById('suggestion-list');
                list.innerHTML = ''; // 清空現有的列表

                // 從後端獲取資料，傳送當前頁數和狀態
                fetch(`advice_get.php?page=${currentPage}&status=${currentTab}`)
                    .then(response => response.json())
                    .then(data => {
                        const suggestions = data.suggestions; // 直接使用從後端返回的資料
                        if (suggestions.length === 0) {
                            list.innerHTML = `<div class="no-data">目前沒有資料顯示</div>`;
                        } else {
                            // 渲染每一條建議
                            suggestions.forEach(item => {
                                const div = document.createElement('div');
                                div.className = 'suggestion';
                                div.onclick = () => {
                                    window.location.href = `advice_detail.php?id=${item.id}`;
                                };

                                // 如果是進行中的建議
                                if (currentTab === 'active') {
                                    div.innerHTML = `
                                <img src="${item.images || 'img/homepage.png'}"  alt="建言圖">
                                <div class="suggestion-content">
                                    <div class="suggestion-title">${item.title}</div>
                                    <div class="suggestion-meta">
                                        <div class="data">
                                            <span>附議數：${item.agree}</span>
                                            <span><i class="fa-solid fa-comment"></i>：${Math.floor(item.comments / 2)}</span>
                                        </div>
                                        <div class="date">
                                            <i class="fa-solid fa-clock"></i>
                                            <span>${item.deadline}</span>
                                            <span>發布日：${item.publishDate}</span>
                                        </div>
                                    </div>
                                </div>
                            `;
                                } else { // 如果是已結束的建議
                                    div.innerHTML = `
                                <img src="${item.images || 'img/homepage.png'}"  alt="建言圖">
                                <div class="suggestion-content">
                                    <div class="suggestion-title">${item.title}</div>
                                    <div class="suggestion-meta">
                                        <span class="suggestion-status ${item.passed ? 'status-passed' : 'status-failed'}">
                                            ${item.passed ? '通過' : '未通過'}
                                        </span>
                                        <span>發布日：${item.publishDate}</span>
                                    </div>
                                </div>
                            `;
                                }

                                list.appendChild(div);
                            });
                        }

                        renderPagination(data.totalPages); // 渲染分頁
                    })
                    .catch(error => {
                        console.error('Error fetching suggestions:', error);
                        const list = document.getElementById('suggestion-list');
                        list.innerHTML = `<div class="no-data">載入資料時發生錯誤</div>`;
                    });
            }




            // 渲染分頁功能
            function renderPagination(totalPages) {
                const pagination = document.getElementById('pagination');
                pagination.innerHTML = ''; // 清空現有的分頁
                for (let i = 1; i <= totalPages; i++) {
                    const pageButton = document.createElement('button');
                    pageButton.textContent = i;
                    pageButton.onclick = () => {
                        currentPage = i;
                        renderSuggestions(); // 重新渲染列表
                    };
                    pagination.appendChild(pageButton);
                }
            }

            // 初始化：綁定 tab 按鈕的事件
            document.querySelectorAll('.tab').forEach(tab => {
                tab.addEventListener('click', function () {
                    switchTab(this.innerText === '進行中' ? 'active' : 'ended'); // 根據 tab 的文字切換
                });
            });

            // 初次載入時渲染建議
            renderSuggestions();
        });





        //控制換頁(後端應該不用動這邊)
        function renderPagination(totalItems) {
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            if (currentPage > 1) {
                const prev = document.createElement('button');
                prev.textContent = '上一頁';
                prev.onclick = () => { currentPage--; renderSuggestions(); };
                pagination.appendChild(prev);
            }

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                if (i === currentPage) btn.disabled = true;
                btn.onclick = () => { currentPage = i; renderSuggestions(); };
                pagination.appendChild(btn);
            }

            if (currentPage < totalPages) {
                const next = document.createElement('button');
                next.textContent = '下一頁';
                next.onclick = () => { currentPage++; renderSuggestions(); };
                pagination.appendChild(next);
            }
        }


        // 初始化排序狀態
        let currentSort = {
            hot: 'desc',  // 默认按 "HOT" 降序排列
            new: 'desc'   // 默认按 "NEW" 降序排列
        };

        // 切換排序條件
        function toggleSort(type, button) {
            currentSort[type] = (currentSort[type] === 'desc') ? 'asc' : 'desc';
            updateArrow(button, currentSort[type]);
            search();
        }

        // 更新排序箭頭的顯示
        function updateArrow(button, direction) {
            const icon = button.querySelector('i');
            if (direction === 'asc') {
                icon.classList.remove('fa-caret-down');
                icon.classList.add('fa-caret-up');
            } else {
                icon.classList.remove('fa-caret-up');
                icon.classList.add('fa-caret-down');
            }
        }

        function search() {
            const category = document.getElementById("category").value.trim();
            const keyword = document.getElementById("search").value.trim();
            const sortHot = currentSort?.hot === 'asc' ? 'asc' : 'desc'; // 確保值為 asc 或 desc
            const sortNew = currentSort?.new === 'asc' ? 'asc' : 'desc';

            // 根據篩選條件過濾資料
            const filteredSuggestions = suggestions.filter(suggestion => {
                const matchesCategory = category === "" || suggestion.category.includes(category);
                const matchesKeyword = keyword === "" || suggestion.title.toLowerCase().includes(keyword.toLowerCase());
                const matchesSortHot = sortHot === 'asc' ? suggestion.hot : !suggestion.hot;
                const matchesSortNew = sortNew === 'asc' ? suggestion.new : !suggestion.new;

                return matchesCategory && matchesKeyword && matchesSortHot && matchesSortNew;
            });

            renderSuggestions(filteredSuggestions); // 渲染篩選後的結果
        }





        // 請求 '進行中或未處理' 建言
        fetch(`advice_get.php?page=1&status=active&sort=new`)
            .then(response => response.json())
            .then(data => {
                // 顯示進行中的建言
            });

        // 請求 '已結束' 建言
        fetch(`advice_get.php?page=1&status=ended&sort=new`)
            .then(response => response.json())
            .then(data => {
                // 顯示已結束的建言
            });







    </script>
</body>

</html>