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
    <link rel="stylesheet" href="css/member_center.css">
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
                    <a class="nav-item" href="<?php
                                                if ($_SESSION['level'] == 'student' || $_SESSION['level'] == 'teacher') {
                                                    echo 'member_center.php';
                                                } else if ($_SESSION['level'] == 'office') {
                                                    echo 'funding/office_assignments.php';
                                                } else if ($_SESSION['level'] == 'manager') {
                                                    echo 'manager/advice_manager.php';
                                                }
                                                ?>">
                        <i class="fas fa-user-circle"></i>
                        <?php
                        if ($_SESSION['level'] == 'student' || $_SESSION['level'] == 'teacher') {
                            echo "會員專區";
                        } else if ($_SESSION['level'] == 'office') {
                            echo "行政專區";
                        } else if ($_SESSION['level'] == 'manager') {
                            echo "後台管理";
                        }
                        ?>
                    </a>

                    <a href="javascript:void(0);" class="nav-item" id="logout-link">
                        <i class="fas fa-sign-out-alt"></i> 登出
                    </a>
                    <script>
                        document.getElementById('logout-link').addEventListener('click', function() {
                            const confirmLogout = confirm("確定要登出嗎？");
                            if (confirmLogout) {
                                window.location.href = "logout.php";
                            }
                        });
                    </script>
                <?php } else { ?>
                    <a href="login.php" class="nav-item"><i class="fas fa-sign-in-alt"></i> 登入</a>
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

    <div class="member-container">
        <!-- 側邊選單 -->
        <aside class="member-sidebar">
            <h2 class="sidebar-title">會員中心</h2>
            <ul>
                <li><a href="member_center/member_profile.php" target="memberContent"><i class="fa-solid fa-user"></i>
                        我的資料</a></li>
                <li><a href="member_center/member_favorites.php" target="memberContent"><i class="fa-solid fa-heart"></i> 收藏紀錄</a>
                </li>
                <li><a href="member_center/member_advice.php" target="memberContent"><i class="fa-solid fa-lightbulb"></i>
                        我的建言</a></li>
                <li><a href="member_center/member_agreements.php" target="memberContent"><i class="fa-solid fa-stamp"></i> 我的附議</a>
                </li>
                <li><a href="member_center/member_fundings.php" target="memberContent"><i class="fa-solid fa-hand-holding-heart"></i>
                        募資紀錄</a></li>
            </ul>
        </aside>

        <!-- 主內容區 iframe -->
        <main class="member-main">
            <iframe name="memberContent" src="member_center/member_profile.php" frameborder="0"></iframe>
        </main>
    </div>

    <script>
        // 取得所有側邊欄的 a 標籤
        const sidebarLinks = document.querySelectorAll('.member-sidebar a');

        // 監聽每個 link 的點擊
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function() {
                // 先清掉所有 a 的 active class
                sidebarLinks.forEach(l => l.classList.remove('active'));
                // 再給目前點到的 a 加上 active
                this.classList.add('active');
            });
        });




        // 點擊漢堡切換 menu
        document.getElementById('mobile-menu-toggle').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('active');
        });

        // 手機 dropdown 點擊展開
        document.querySelectorAll('.mobile-menu .dropdown .dropbtn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault(); // 防止跳頁
                const parent = btn.parentElement;
                parent.classList.toggle('active');
            });
        });
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 400) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>

</body>

</html>