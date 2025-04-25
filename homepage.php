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

    <link rel="stylesheet" href="css/homepage.css">
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

    <!-- Navbar -->

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
                                                    echo '#';
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

    <!-- Banner -->
    <div class="banner">
        <div class="banner1">
            <div class="hot_propose">
                <div class="chicken" data-hover="🥚">
                    <img src="img\image2333.jpg">
                    <div class="chicken_tag"> 建言瀏覽</div>
                </div>
            </div>
            <div class="new_propose">
                <div class="chicken">
                    <img src="img\6A7933B2-AA2E-498F-964B-259E5C7ACB2B.png">
                    <div class="chicken_tag">關於我們</div>
                </div>

            </div>
        </div>
        <div class="banner2">
            <div class="chicken">
                <img src="img\F66F58A6-65E9-4AF5-9188-3D246E67AE26.png">
                <div class="chicken_tag">我要建言</div>
            </div>
        </div>
        <div class="banner3">
            <div class="hot_fund">
                <div class="chicken">
                    <img src="img\10C332BB-804B-48D6-B909-50720AD3B2B5.png">
                    <div class="chicken_tag">募資專案</div>
                </div>
            </div>
            <div class="new_fund">
                <div class="chicken">
                    <img src="img\D8904374-10AE-4B6B-B6FB-355FEA8C7B44.png">
                    <div class="chicken_tag">成功專案</div>
                </div>

            </div>
        </div>
    </div>

    <!--理念--->

    <div class="philosophy">
        <div class="p_idea">讓你的建言破殼而出，孵化校園新未來！</div>
    </div>
    <div class="container">
        <!--建言查看區--->
        <div class="seek_advice_area">
            <div class="seek_advice">查看建言</div>
            <div class="hot-area">
                <div class="advice_classifedtitle">
                    <div class="title-bar">
                        <div class="hot_tag">HOT</div>
                        <div class="right-controls">
                            <div class="swiper-button-prev-1"><i class="fa-solid fa-arrow-left"
                                    style="color: #a5a0a0;"></i></div>
                            <div class="swiper-button-next-1"><i class="fa-solid fa-arrow-right"
                                    style="color: #a5a0a0;"></i></div>
                            <div class="more"><a href="advice_search.php">更多</a></div>
                        </div>
                    </div>
                    <div class="swiper mySwiper1">
                        <div class="swiper-wrapper">
                            <?php
                            $link = mysqli_connect('localhost', 'root');
                            mysqli_select_db($link, "system_project");

                            // 檢查連線是否成功
                            if (!$link) {
                                die("資料庫連線失敗: " . mysqli_connect_error());
                            }
                            // 查詢資料庫中的建言資料
                            $sql = "SELECT a.advice_id, a.advice_title, a.advice_content, a.category, a.agree, a.advice_state, 
               ai.file_path FROM advice a LEFT JOIN advice_image ai ON a.advice_id = ai.advice_id where a.agree<=2 ORDER BY a.agree DESC"; // 查詢最熱門的建言

                            $result = mysqli_query($link, $sql);
                            if (!$result) {
                                die("查詢失敗: " . mysqli_error($link));
                            }
                            // 檢查是否有資料
                            if (mysqli_num_rows($result) > 0) {
                                // 輸出每一筆資料
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $advice_id = $row['advice_id'];
                                    $advice_title = $row['advice_title'];
                                    $advice_content = $row['advice_content'];
                                    $category = $row['category'];
                                    $agree = $row['agree'];
                                    $status = isset($row['advice_state']) ? $row['advice_state'] : 'pending';
                                    $progress = $row['agree'] / 3 * 100; // 假設進度是根據同意數量計算的百分比
                                    $progress = round($progress); // 四捨五入到整數
                                    if ($progress > 100) {
                                        $progress = 100; // 確保進度不超過 100%
                                    }
                                    $progress_width = $progress . "%"; // 計算進度條的寬度
                                    // 這裡可以根據需要顯示建言的內容，例如標題、進度等
                                    // 這裡是模擬的圖片網址，實際上應該從資料庫中獲取
                                    // 獲取圖片路徑，若無圖片則使用預設圖片
                                    $image_url = !empty($row['file_path']) ? $row['file_path'] : 'https://img.kpopdata.com/upload/content/216/231/22416704092d26793206.jpg';
                            ?>
                                    <!-- 模擬 8 筆資料，每個都是 swiper-slide -->
                                    <div class="swiper-slide">
                                        <a href="advice_detail.php?advice_id=<?php echo urlencode($advice_id); ?>"
                                            style="text-decoration: none; color: inherit;">

                                            <div class="adv_content_block">
                                                <div class="adv_content_img">
                                                    <img src="<?php echo htmlspecialchars($image_url); ?>" />
                                                </div>
                                                <div class="adv_content_goal">
                                                    <div class="adv_content_text"><?php echo htmlspecialchars($advice_title); ?>
                                                    </div>
                                                    <div class="progress-line">
                                                        <div class="progress"
                                                            style="width:<?php echo htmlspecialchars($progress_width); ?>;">
                                                        </div>
                                                    </div>
                                                    <div class="card-data">
                                                        <span><i
                                                                class="fa-regular fa-user"></i><?php echo htmlspecialchars($agree); ?></span>
                                                        <span><?php echo htmlspecialchars($progress_width); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>


                            <?php }
                            }
                            ?>
                            <?php mysqli_close($link); ?>

                            <!-- <div class="swiper-slide">
                                <div class="adv_content_block">
                                    <div class="adv_content_img">
                                        <img
                                            src="https://img.kpopdata.com/upload/content/216/231/22416704092d26793206.jpg" />
                                    </div>
                                    <div class="adv_content_goal">
                                        <div class="adv_content_text">學校後門晚上無路燈，學生很害怕</div>
                                        <div class="progress-line">
                                            <div class="progress" style="width: 45%;"></div>
                                        </div>
                                        <div class="card-data">
                                            <span><i class="fa-regular fa-user"></i>18</span>
                                            <span>45%</span>
                                        </div>
                                    </div>
                                </div>
                            </div> -->

                        </div>
                    </div>
                </div>
            </div>

            <!--new建言查看��--->
            <div class="new-area">
                <div class="advice_classifedtitle">
                    <div class="title-bar">
                        <div class="new_tag">NEW</div>
                        <div class="right-controls">
                            <div class="swiper-button-prev-2"><i class="fa-solid fa-arrow-left"
                                    style="color: #a5a0a0;"></i>
                            </div>
                            <div class="swiper-button-next-2"><i class="fa-solid fa-arrow-right"
                                    style="color: #a5a0a0;"></i>
                            </div>
                            <div class="more"><a href="advice_search.php">更多</a></div>
                        </div>
                    </div>
                    <div class="swiper mySwiper2">
                        <div class="swiper-wrapper">
                            <?php
                            $link = mysqli_connect('localhost', 'root');
                            mysqli_select_db($link, "system_project");

                            // 檢查連線是否成功
                            if (!$link) {
                                die("資料庫連線失敗: " . mysqli_connect_error());
                            }
                            // 查詢資料庫中的建言資料
                            $sql = "SELECT a.advice_id, a.advice_title, a.advice_content, a.category, a.agree, 
               ai.file_path FROM advice a LEFT JOIN advice_image ai ON a.advice_id = ai.advice_id where a.agree<3 ORDER BY a.announce_date DESC"; // 查詢最新的建言

                            $result = mysqli_query($link, $sql);
                            if (!$result) {
                                die("查詢失敗: " . mysqli_error($link));
                            }
                            // 檢查是否有資料
                            if (mysqli_num_rows($result) > 0) {
                                // 輸出每一筆資料
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $advice_id = $row['advice_id'];
                                    $advice_title = $row['advice_title'];
                                    $advice_content = $row['advice_content'];
                                    $category = $row['category'];
                                    $agree = $row['agree'];
                                    $progress = $row['agree'] / 3 * 100; // 假設進度是根據同意數量計算的百分比
                                    $progress = round($progress); // 四捨五入到整數
                                    if ($progress > 100) {
                                        $progress = 100; // 確保進度不超過 100%
                                    }
                                    $progress_width = $progress . "%"; // 計算進度條的寬度
                                    // 這裡可以根據需要顯示建言的內容，例如標題、進度等

                                    // 獲取圖片路徑，若無圖片則使用預設圖片
                                    $image_url = !empty($row['file_path']) ? $row['file_path'] : 'https://img.kpopdata.com/upload/content/216/231/22416704092d26793206.jpg';

                            ?>
                                    <!-- 模擬 8 筆資料，每個都是 swiper-slide -->

                                    <div class="swiper-slide">
                                        <a href="advice_detail.php?advice_id=<?php echo urlencode($advice_id); ?>"
                                            style="text-decoration: none; color: inherit;">
                                            <div class="adv_content_block">
                                                <div class="adv_content_img">
                                                    <img src="<?php echo htmlspecialchars($image_url); ?>" />
                                                </div>
                                                <div class="adv_content_goal">
                                                    <div class="adv_content_text"><?php echo htmlspecialchars($advice_title); ?>
                                                    </div>
                                                    <div class="progress-line">
                                                        <div class="progress"
                                                            style="width:<?php echo htmlspecialchars($progress_width); ?>;">
                                                        </div>
                                                    </div>
                                                    <div class="card-data">
                                                        <span><i
                                                                class="fa-regular fa-user"></i><?php echo htmlspecialchars($agree); ?></span>
                                                        <span><?php echo htmlspecialchars($progress_width); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                            <?php }
                            } ?>

                            <?php mysqli_close($link) ?>
                            <!-- <div class="swiper-slide">
                                <div class="adv_content_block">
                                    <div class="adv_content_img">
                                        <img
                                            src="https://img.kpopdata.com/upload/content/216/231/22416704092d26793206.jpg" />
                                    </div>
                                    <div class="adv_content_goal">
                                        <div class="adv_content_text">學校後門晚上無路燈，學生很害怕</div>
                                        <div class="progress-line">
                                            <div class="progress" style="width: 45%;"></div>
                                        </div>
                                        <div class="card-data">
                                            <span><i class="fa-regular fa-user"></i>18</span>
                                            <span>45%</span>
                                        </div>
                                    </div>
                                </div>
                            </div> -->

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--募資進度查看區--->

        <div class="seek_fund">
            <div class="seek_advice">募資進度</div>
            <div class="fund_area">
                <div class="top_fund_bar">
                    <div class="hotfund_tag">HOT</div>
                    <div class="fund_controls">
                        <div class="swiper-button-prev-3"><i class="fa-solid fa-arrow-left" style="color: #a5a0a0"></i>
                        </div>
                        <div class="swiper-button-next-3"><i class="fa-solid fa-arrow-right" style="color: #a5a0a0"></i>
                        </div>
                        <div class="more"><a href="#">更多</a></div>
                    </div>
                </div>

                <?php
                $link = mysqli_connect('localhost', 'root');
                mysqli_select_db($link, "system_project");

                // 檢查連線是否成功
                if (!$link) {
                    die("資料庫連線失敗: " . mysqli_connect_error());
                }

                // 查詢資料庫中的募資資料
                $sql = "SELECT f.project_id, a.advice_id, a.advice_title, a.advice_content, a.category, a.agree, 
               ai.file_path, f.funding_goal, s.proposal_text, 
               COALESCE(COUNT(d.donor),0) AS donor_count, 
               COALESCE(SUM(d.donation_amount), 0) AS total_funding
        FROM fundraising_projects f
        INNER JOIN suggestion_assignments s ON f.suggestion_assignments_id = s.suggestion_assignments_id
        LEFT JOIN donation_record d ON f.project_id = d.project_id
        INNER JOIN advice a ON s.advice_id = a.advice_id
        LEFT JOIN advice_image ai ON a.advice_id = ai.advice_id
        GROUP BY f.project_id
        ORDER BY a.announce_date DESC";

                $result = mysqli_query($link, $sql);
                if (!$result) {
                    die("查詢失敗: " . mysqli_error($link));
                }

                $data = [];
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $data[] = $row;
                    }
                }
                mysqli_close($link);

                // 切成每頁 5 筆資料（1 張大卡 + 最多 4 張小卡）
                $chunkedData = array_chunk($data, 5);
                ?>

                <!-- swiper 輸出區塊 -->
                <div class="swiper mySwiper3">
                    <div class="swiper-wrapper">
                        <?php foreach ($chunkedData as $group): ?>
                            <?php
                            $bigCard = array_shift($group); // 第一筆作為大卡
                            $bigCardImage = !empty($bigCard['file_path']) ? $bigCard['file_path'] : 'default_big_image.jpg';
                            $bigCardTitle = htmlspecialchars($bigCard['advice_title']);
                            $bigCardMoney = (float)($bigCard['funding_goal'] ?? 0);
                            $bigCardDonorCount = (int)($bigCard['donor_count'] ?? 0);
                            $bigCardTotalFunding = (float)($bigCard['total_funding'] ?? 0);
                            $progressDisplay = ($bigCardMoney > 0) ? intval(($bigCardTotalFunding / $bigCardMoney) * 100) : 0;
                            $progressBar = min(100, $progressDisplay);
                            ?>
                            <div class="swiper-slide">
                                <div class="fund-section">
                                    <div class="fund-content">
                                        <!-- 大卡 -->
                                        <a href="funding_detail.php?id=<?php echo urlencode($bigCard['project_id']); ?>" style="text-decoration: none; color: inherit;">
                                            <div class="left-big-card">
                                                <div class="fundraiser-card">
                                                    <div class="card-image">
                                                        <img src="<?php echo $bigCardImage; ?>" alt="大圖">
                                                    </div>
                                                    <div class="card-info">
                                                        <div class="card-title"><?php echo $bigCardTitle; ?></div>
                                                        <div class="progress-bar">
                                                            <div class="progress" style="width:<?php echo $progressBar; ?>%;"></div>
                                                        </div>
                                                        <div class="card-meta">
                                                            <div>
                                                                <span>NT$ <?php echo number_format($bigCardTotalFunding, 0); ?></span>
                                                                <span class="divider">/</span>
                                                                <span><?php echo $progressDisplay; ?>%</span>
                                                            </div>
                                                            <div>
                                                                <span><?php echo $bigCardDonorCount; ?> <i class="fa-regular fa-user"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>

                                        <!-- 小卡 -->
                                        <div class="right-small-cards">
                                            <?php foreach ($group as $smallCard): ?>
                                                <?php
                                                $img = !empty($smallCard['file_path']) ? $smallCard['file_path'] : 'default_small_image.jpg';
                                                $title = htmlspecialchars($smallCard['advice_title']);
                                                $goal = (float)($smallCard['funding_goal'] ?? 0);
                                                $total = (float)($smallCard['total_funding'] ?? 0);
                                                $donor = (int)($smallCard['donor_count'] ?? 0);
                                                $progressDisplay = ($goal > 0) ? intval(($total / $goal) * 100) : 0;
                                                $progressBar = min(100, $progressDisplay);
                                                ?>
                                                <a href="funding_detail.php?id=<?php echo urlencode($smallCard['project_id']); ?>" style="text-decoration: none; color: inherit;">
                                                    <div class="fundraiser-card small-card">
                                                        <div class="card-image">
                                                            <img src="<?php echo $img; ?>" alt="小圖">
                                                        </div>
                                                        <div class="card-info">
                                                            <div class="card-title"><?php echo $title; ?></div>
                                                            <div class="progress-bar">
                                                                <div class="progress" style="width: <?php echo $progressBar; ?>%;"></div>
                                                            </div>
                                                            <div class="card-meta">
                                                                <div>
                                                                    <span>NT$ <?php echo number_format($total, 0); ?> / <?php echo $progressDisplay; ?>%</span>
                                                                </div>
                                                                <div>
                                                                    <span><?php echo $donor; ?> <i class="fa-regular fa-user"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div> <!-- .fund-content -->
                                </div> <!-- .fund-section -->
                            </div> <!-- .swiper-slide -->
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
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
                    <div>意見箱</div>
                </div>
                <div class="connection">
                    <div>242新北市新莊區中正路510號.</div>
                    <div>電話:(02)2905-2000</div>
                </div>
            </div>

        </div>

    </footer>


    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- 初始化 Swiper -->
    <script>
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

        /*CARD SLIDER*/
        const swiperConfigs = [{
                container: ".mySwiper1",
                next: ".swiper-button-next-1",
                prev: ".swiper-button-prev-1"
            },
            {
                container: ".mySwiper2",
                next: ".swiper-button-next-2",
                prev: ".swiper-button-prev-2"
            }
        ];

        swiperConfigs.forEach(config => {
            new Swiper(config.container, {
                slidesPerView: 'auto', // 用 auto 配合固定寬
                spaceBetween: 24,
                loop: true,
                speed: 800,
                navigation: {
                    nextEl: config.next,
                    prevEl: config.prev
                },
                slidesPerGroupAuto: true, // 自動根據可視區的卡片數滑動

                breakpoints: {
                    768: {
                        slidesPerView: 4,
                        slidesPerGroup: 4
                    },
                    480: {
                        slidesPerView: 2,
                        slidesPerGroup: 2
                    },
                    240: {
                        slidesPerView: 1,
                        slidesPerGroup: 1
                    }
                }
            });
        });
        new Swiper(".mySwiper3", {
            slidesPerView: 1,
            slidesPerGroup: 1,
            loop: true,
            speed: 900,
            navigation: {
                nextEl: ".swiper-button-next-3",
                prevEl: ".swiper-button-prev-3"
            },
            breakpoints: {
                768: {
                    slidesPerView: 1,
                    slidesPerGroup: 1
                },
                1024: {
                    slidesPerView: 1,
                    slidesPerGroup: 1
                }
            }
        });
    </script>


</body>


<!--CDN link-->

</html>