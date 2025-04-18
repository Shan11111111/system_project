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
    <link rel="stylesheet" href="css/ongoing_funding_search.css">
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
                        <a href="#">已結束募資</a>
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
                    <a href="#">進行中計畫</a>
                    <a href="#">成功案例</a>
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

    <!--主內容-->
    <div class="container">
        <div class="filter-bar">
            <!-- 左邊：分類 -->
            <div class="category-select">
                <select id="category">
                    <option value="all">全部分類</option>
                    <option value="equipment">設施改善</option>
                    <option value="academic">學術發展</option>
                    <option value="club">社團活動</option>
                    <option value="welfare">公益關懷</option>
                    <option value="environment">環保永續</option>
                    <option value="other">其他</option>
                </select>
            </div>

            <!-- 右邊：搜尋 + 排序 -->
            <div class="search_text">
                <input type="text" id="search" placeholder="請輸入關鍵字" />
                <button onclick="search()"><i class="fa-solid fa-magnifying-glass"></i></button>

                <button class="sort" id="sortBtn" onclick="toggleSortMenu()">
                    <span id="sortLabel">排序</span> <i class="fa-solid fa-filter"></i>
                </button>

                <div id="sortMenu" class="sort-menu">
                    <div onclick="sortBy('hot')">最熱門</div>
                    <div onclick="sortBy('new')">最新</div>
                    <div onclick="sortBy('deadline')">結束日期</div>
                </div>

                <!-- 排序選單js -->
                <script>
                    function toggleSortMenu() {
                        const menu = document.getElementById("sortMenu");
                        menu.style.display = menu.style.display === "block" ? "none" : "block";
                    }

                    // 點擊項目排序
                    function sortBy(type) {
                        const labelMap = {
                            hot: "最熱門",
                            new: "最新",
                            deadline: "結束日期"
                        };

                        // 更新按鈕文字顯示
                        document.getElementById("sortLabel").textContent = labelMap[type];

                        // 關閉選單
                        document.getElementById("sortMenu").style.display = "none";
                    }
                    // 點擊外部關閉 sortMenu
                    document.addEventListener("click", function(event) {
                        const sortBtn = document.getElementById("sortBtn");
                        const sortMenu = document.getElementById("sortMenu");

                        if (!sortBtn.contains(event.target) && !sortMenu.contains(event.target)) {
                            sortMenu.style.display = "none";
                        }
                    });
                </script>
            </div>
        </div>
        <div class="funding_project_region">
            <div class="project-card">
                <div class="card-image">
                    <div class="category">
                        <span>社團活動</span>
                    </div>
                    <img
                        src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQk_5XgR2ZDah4v8eTfVCvgYJ4amCbsXWZt8g&s" />
                </div>
                <div class="card-info">
                    <div class="card-title">Geef Hayat een kans hikfgituhgjirfkekjhjkdllllllllllytthhhhhhhhhhhhhhhhrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrll</div>
                    <div class="progress-bar">
                        <div class="progress" style="width: 70%;"></div>
                    </div>
                    <div class="card-meta">
                        <div>
                            <span>NT$ 155,819 </span> <!--現在募到的錢 %數 可以超過100%(超過目標金額的意思)-->
                            <span class="divider">/</span>
                            <span>100%</span>
                        </div>
                        <div>
                            <span>25 <i class="fa-regular fa-user"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="project-card">
                <div class="card-image">
                    <div class="category">
                        <span>社團活動</span>
                    </div>
                    <img
                        src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQk_5XgR2ZDah4v8eTfVCvgYJ4amCbsXWZt8g&s" />
                </div>
                <div class="card-info">
                    <div class="card-title">Geef Hayat een kans hikfgituhgjirfkekjhjkdllllllllllytthhhhhhhhhhhhhhhhrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrll</div>
                    <div class="progress-bar">
                        <div class="progress" style="width: 70%;"></div>
                    </div>
                    <div class="card-meta">
                        <div>
                            <span>NT$ 155,819 </span> <!--現在募到的錢 %數 可以超過100%(超過目標金額的意思)-->
                            <span class="divider">/</span>
                            <span>100%</span>
                        </div>
                        <div>
                            <span>25 <i class="fa-regular fa-user"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="project-card">
                <div class="card-image">
                    <div class="category">
                        <span>社團活動</span>
                    </div>
                    <img
                        src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQk_5XgR2ZDah4v8eTfVCvgYJ4amCbsXWZt8g&s" />
                </div>
                <div class="card-info">
                    <div class="card-title">Geef Hayat een kans hikfgituhgjirfkekjhjkdllllllllllytthhhhhhhhhhhhhhhhrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrll</div>
                    <div class="progress-bar">
                        <div class="progress" style="width: 70%;"></div>
                    </div>
                    <div class="card-meta">
                        <div>
                            <span>NT$ 155,819 </span> <!--現在募到的錢 %數 可以超過100%(超過目標金額的意思)-->
                            <span class="divider">/</span>
                            <span>100%</span>
                        </div>
                        <div>
                            <span>25 <i class="fa-regular fa-user"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="project-card">
                <div class="card-image">
                    <div class="category">
                        <span>社團活動</span>
                    </div>
                    <img
                        src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQk_5XgR2ZDah4v8eTfVCvgYJ4amCbsXWZt8g&s" />
                </div>
                <div class="card-info">
                    <div class="card-title">Geef Hayat een kans hikfgituhgjirfkekjhjkdllllllllllytthhhhhhhhhhhhhhhhrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrll</div>
                    <div class="progress-bar">
                        <div class="progress" style="width: 70%;"></div>
                    </div>
                    <div class="card-meta">
                        <div>
                            <span>NT$ 155,819 </span> <!--現在募到的錢 %數 可以超過100%(超過目標金額的意思)-->
                            <span class="divider">/</span>
                            <span>100%</span>
                        </div>
                        <div>
                            <span>25 <i class="fa-regular fa-user"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="project-card">
                <div class="card-image">
                    <div class="category">
                        <span>社團活動</span>
                    </div>
                    <img
                        src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQk_5XgR2ZDah4v8eTfVCvgYJ4amCbsXWZt8g&s" />
                </div>
                <div class="card-info">
                    <div class="card-title">Geef Hayat een kans hikfgituhgjirfkekjhjkdllllllllllytthhhhhhhhhhhhhhhhrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrll</div>
                    <div class="progress-bar">
                        <div class="progress" style="width: 70%;"></div>
                    </div>
                    <div class="card-meta">
                        <div>
                            <span>NT$ 155,819 </span> <!--現在募到的錢 %數 可以超過100%(超過目標金額的意思)-->
                            <span class="divider">/</span>
                            <span>100%</span>
                        </div>
                        <div>
                            <span>25 <i class="fa-regular fa-user"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="project-card">
                <div class="card-image">
                    <div class="category">
                        <span>社團活動</span>
                    </div>
                    <img
                        src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQk_5XgR2ZDah4v8eTfVCvgYJ4amCbsXWZt8g&s" />
                </div>
                <div class="card-info">
                    <div class="card-title">Geef Hayat een kans hikfgituhgjirfkekjhjkdllllllllllytthhhhhhhhhhhhhhhhrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrll</div>
                    <div class="progress-bar">
                        <div class="progress" style="width: 70%;"></div>
                    </div>
                    <div class="card-meta">
                        <div>
                            <span>NT$ 155,819 </span> <!--現在募到的錢 %數 可以超過100%(超過目標金額的意思)-->
                            <span class="divider">/</span>
                            <span>100%</span>
                        </div>
                        <div>
                            <span>25 <i class="fa-regular fa-user"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="project-card">
                <div class="card-image">
                    <div class="category">
                        <span>社團活動</span>
                    </div>
                    <img
                        src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQk_5XgR2ZDah4v8eTfVCvgYJ4amCbsXWZt8g&s" />
                </div>
                <div class="card-info">
                    <div class="card-title">Geef Hayat een kans hikfgituhgjirfkekjhjkdllllllllllytthhhhhhhhhhhhhhhhrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrll</div>
                    <div class="progress-bar">
                        <div class="progress" style="width: 70%;"></div>
                    </div>
                    <div class="card-meta">
                        <div>
                            <span>NT$ 155,819 </span> <!--現在募到的錢 %數 可以超過100%(超過目標金額的意思)-->
                            <span class="divider">/</span>
                            <span>100%</span>
                        </div>
                        <div>
                            <span>25 <i class="fa-regular fa-user"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="project-card">
                <div class="card-image">
                    <div class="category">
                        <span>社團活動</span>
                    </div>
                    <img
                        src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQk_5XgR2ZDah4v8eTfVCvgYJ4amCbsXWZt8g&s" />
                </div>
                <div class="card-info">
                    <div class="card-title">Geef Hayat een kans hikfgituhgjirfkekjhjkdllllllllllytthhhhhhhhhhhhhhhhrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrll</div>
                    <div class="progress-bar">
                        <div class="progress" style="width: 70%;"></div>
                    </div>
                    <div class="card-meta">
                        <div>
                            <span>NT$ 155,819 </span> <!--現在募到的錢 %數 可以超過100%(超過目標金額的意思)-->
                            <span class="divider">/</span>
                            <span>100%</span>
                        </div>
                        <div>
                            <span>25 <i class="fa-regular fa-user"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="project-card">
                <div class="card-image">
                    <div class="category">
                        <span>社團活動</span>
                    </div>
                    <img
                        src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQk_5XgR2ZDah4v8eTfVCvgYJ4amCbsXWZt8g&s" />
                </div>
                <div class="card-info">
                    <div class="card-title">Geef Hayat een kans hikfgituhgjirfkekjhjkdllllllllllytthhhhhhhhhhhhhhhhrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrll</div>
                    <div class="progress-bar">
                        <div class="progress" style="width: 70%;"></div>
                    </div>
                    <div class="card-meta">
                        <div>
                            <span>NT$ 155,819 </span> <!--現在募到的錢 %數 可以超過100%(超過目標金額的意思)-->
                            <span class="divider">/</span>
                            <span>100%</span>
                        </div>
                        <div>
                            <span>25 <i class="fa-regular fa-user"></i></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="pagination">
        <button onclick="changePage('prev')">上一頁</button>
        <span id="page-indicator">第 1 頁</span>
        <button onclick="changePage('next')">下一頁</button>
    </div>
    <script>
        let currentPage = 1;
        let currentSort = 'new';

        function search() {
            currentPage = 1;
            fetchData();
        }

        function sortBy(type) {
            currentSort = type;
            currentPage = 1;
            fetchData();
        }

        function changePage(direction) {
            if (direction === 'prev' && currentPage > 1) currentPage--;
            else if (direction === 'next') currentPage++;
            fetchData();
        }

        function fetchData() {
            const keyword = document.getElementById('search').value;
            const category = document.getElementById('category').value;

            const params = new URLSearchParams({
                keyword: keyword,
                category: category,
                sort: currentSort,
                page: currentPage
            });

            fetch(`fetch_funding_data.php?${params}`)
                .then(res => res.json())
                .then(data => {
                    renderCards(data.cards);
                    document.getElementById("page-indicator").textContent = `第 ${currentPage} 頁`;
                });
        }

        function renderCards(cards) {
            const container = document.getElementById('funding-cards');
            container.innerHTML = '';

            cards.forEach(card => {
                container.innerHTML += `
      <div class="project-card">
        <div class="card-image">
          <div class="category"><span>${card.category_name}</span></div>
          <img src="${card.image}">
        </div>
        <div class="card-info">
          <div class="card-title">${card.title}</div>
          <div class="progress-bar">
            <div class="progress" style="width: ${card.progress}%"></div>
          </div>
          <div class="card-meta">
            <div><span>NT$ ${card.raised}</span> / <span>${card.progress}%</span></div>
            <div><span>${card.supporter} <i class="fa-regular fa-user"></i></span></div>
          </div>
        </div>
      </div>`;
            });
        }

        // 預設載入第一頁
        window.onload = fetchData;
    </script>

    <footer class="footer">
        <div class="logo_space">
            <img src="img\logo.png" style="width: 150px;">
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

</body>

</html>