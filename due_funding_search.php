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

<>
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

    <!--主內容-->
    <div class="container">
        <div class="filter-bar">
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
            <div class="search_text">
                <input type="text" id="search" placeholder="請輸入關鍵字" />
                <button onclick="loadCards(1)"><i class="fa-solid fa-magnifying-glass"></i></button>
                <button class="sort" id="sortBtn" onclick="toggleSortMenu()">
                    <span id="sortLabel">排序</span> <i class="fa-solid fa-filter"></i>
                </button>
                <div id="sortMenu" class="sort-menu">
                    <div onclick="setSort('successful')">募資成功</div>
                    <div onclick="setSort('fail')">募資失敗</div>
                    <div onclick="setSort('all')">所有募資</div>
                </div>
            </div>
        </div>

        <div class="funding_project_region" id="card-container"></div>
        <div id="no-result" style="text-align: center; font-size: 16px; color: #888; margin-top: 20px;"></div>

        <div class="pagination" id="pagination"></div>
    </div>

    <!-- ✅ JavaScript 功能 -->
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
        window.addEventListener('scroll', function () {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 400) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        const categoryMap = {
            all: "全部分類",
            equipment: "設施改善",
            academic: "學術發展",
            club: "社團活動",
            welfare: "公益關懷",
            environment: "環保永續",
            other: "其他"
        };

        let currentPage = 1;
        let currentSort = 'all';
        const itemsPerPage = 9;

        function getFilters() {
            return {
                category: document.getElementById("category").value,
                keyword: document.getElementById("search").value.trim(),
                sort: currentSort
            };
        }

        function loadCards(page = 1) {
            currentPage = page;

            const {
                category,
                keyword,
                sort
            } = getFilters();
            const url = `funding_function/fetch_funding_cards.php?page=${page}&category=${category}&keyword=${encodeURIComponent(keyword)}&sort=${sort}&page_type=due`;



            fetch(url)
                .then(res => res.json())
                .then(res => {
                    const container = document.getElementById("card-container");
                    const noResult = document.getElementById("no-result");
                    container.innerHTML = "";
                    noResult.innerHTML = "";

                    if (res.data.length === 0) {
                        noResult.innerText = "查無結果";
                        document.getElementById("pagination").innerHTML = "";
                        return;
                    }

                    res.data.forEach(card => {
                        let stamp = '';
                        if (card.is_expired) {
                            if (card.progress >= 100) {
                                stamp = `<img class="card-stamp" src="img/passed_funding.png" alt="成功">`;
                            } else {
                                stamp = `<img class="card-stamp" src="img/failed_funding.png" alt="失敗">`;
                            }
                        }

                        container.innerHTML += `                       
                       <div class="project-card" data-id="${card.id}">   
                            <div class="card-image"> 
                                <div class="category"><span>${categoryMap[card.category] || card.category}</span></div>
                                <img src="${card.file_path}" />
                            </div>

                            <div class="card-info">
                                <div class="card-title">${card.title}</div>
                                <div class="progress-bar">
                                    <div class="progress" style="width: ${card.progress}%"></div>
                                </div>
                                <div class="card-meta">
                                    <div>
                                        <span>NT$ ${card.raised}</span>
                                        <span class="divider">/</span>
                                        <span>${card.progress}%</span>
                                    </div>
                                    <div>
                                         <span>${card.supporter} <i class="fa-regular fa-user"></i></span>
                                    </div>
                                </div>
                            </div>
                          ${stamp}  <!-- ✅ 浮水印放在圖片和資訊區之間 -->
                        </div>
                    `;
                    });

                    renderPagination(res.totalPages);
                    // 綁定每一張卡片的點擊事件
                    document.querySelectorAll(".project-card").forEach(cardEl => {
                        const id = cardEl.getAttribute("data-id");
                        cardEl.onclick = () => {
                            window.location.href = `funding_detail.php?id=${id}`;
                        };
                    });


                });
        }

        function renderPagination(totalPages) {
            const pagination = document.getElementById("pagination");
            pagination.innerHTML = "";

            if (totalPages <= 1) return;

            if (currentPage > 1) {
                const prev = document.createElement("button");
                prev.textContent = "←";
                prev.onclick = () => {
                    currentPage--;
                    loadCards(currentPage);
                    window.scrollTo(0, 0);
                };
                pagination.appendChild(prev);
            }

            if (currentPage > 1) {
                const prevNum = document.createElement("button");
                prevNum.textContent = currentPage - 1;
                prevNum.onclick = () => {
                    currentPage--;
                    loadCards(currentPage);
                    window.scrollTo(0, 0);
                };
                pagination.appendChild(prevNum);
            }

            const current = document.createElement("button");
            current.textContent = currentPage;
            current.disabled = true;
            current.classList.add("active");
            pagination.appendChild(current);

            if (currentPage < totalPages) {
                const nextNum = document.createElement("button");
                nextNum.textContent = currentPage + 1;
                nextNum.onclick = () => {
                    currentPage++;
                    loadCards(currentPage);
                    window.scrollTo(0, 0);
                };
                pagination.appendChild(nextNum);
            }

            if (currentPage < totalPages) {
                const next = document.createElement("button");
                next.textContent = "→";
                next.onclick = () => {
                    currentPage++;
                    loadCards(currentPage);
                    window.scrollTo(0, 0);
                };
                pagination.appendChild(next);
            }
        }

        function setSort(type) {
            currentSort = type;
            document.getElementById("sortLabel").textContent = {
                successful: "募資成功",
                fail: "募資失敗",
            } [type];
            document.getElementById("sortMenu").style.display = "none";
            loadCards(1);
        }

        function toggleSortMenu() {
            const menu = document.getElementById("sortMenu");
            menu.style.display = menu.style.display === "block" ? "none" : "block";
        }

        document.getElementById("category").addEventListener("change", () => loadCards(1));
        document.getElementById("search").addEventListener("keypress", function(e) {
            if (e.key === "Enter") loadCards(1);
        });

        // ✅ 預設載入熱門
        window.addEventListener("DOMContentLoaded", () => {
            loadCards(1);
        });
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