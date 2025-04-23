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

    <link rel="stylesheet" href="css/funding_detail.css">
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

    <?php
    $project_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $link = mysqli_connect('localhost', 'root', '', 'system_project');

    if (!$link) {
        die("資料庫連線失敗: " . mysqli_connect_error());
    }

    // 取得專案基本資料 + 建議 ID + 關聯欄位
    $sql = "SELECT 
            p.project_id,
            p.title AS project_title,
            p.description AS project_description,
            p.funding_goal,
            p.start_date,
            p.end_date,
            p.status,
            p.suggestion_assignments_id, 
            a.advice_id
        FROM fundraising_projects p
        LEFT JOIN advice a ON a.advice_id = p.suggestion_assignments_id
        WHERE p.project_id = $project_id";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        die("SQL 查詢錯誤: " . mysqli_error($link));
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $suggestion_assignments_id = $row['suggestion_assignments_id'] ?? 0;
        $start_date = $row['start_date'];

        // 處理日期與剩餘天數
        $start_date_obj = new DateTime($start_date);
        $start_date_obj->modify('+6 months');
        $end_date = $start_date_obj->format('Y/m/d H:i');

        $today = new DateTime();
        $end_date_obj = new DateTime($end_date);
        $interval = $today->diff($end_date_obj);
        $days_remaining = $interval->invert ? 0 : $interval->days;

        // 查圖片：先拿到 advice_id
        if (!empty($suggestion_assignments_id)) {
            $sql_advice = "SELECT advice_id FROM suggestion_assignments WHERE suggestion_assignments_id = $suggestion_assignments_id";
            $result_advice = mysqli_query($link, $sql_advice);

            if ($result_advice && mysqli_num_rows($result_advice) > 0) {
                $advice_row = mysqli_fetch_assoc($result_advice);
                $advice_id = $advice_row['advice_id'];

                // 查圖片檔案路徑
                $image_sql = "SELECT file_path FROM advice_image WHERE advice_id = $advice_id";
                $image_result = mysqli_query($link, $image_sql);

                if ($image_result && mysqli_num_rows($image_result) > 0) {
                    $image_row = mysqli_fetch_assoc($image_result);
                    $image_path = $image_row['file_path'];
                } else {
                    $image_path = 'homepage.png';
                }
            } else {
                $image_path = 'homepage.png';
            }
        } else {
            $image_path = 'homepage.png';
        }

        $row['image_path'] = $image_path;

        // 募資金額總和
        $fund_sql = "SELECT SUM(donate_money) AS current_amount FROM funding_people WHERE funding_id = $project_id";
        $fund_result = mysqli_query($link, $fund_sql);
        if ($fund_result && mysqli_num_rows($fund_result) > 0) {
            $fund_row = mysqli_fetch_assoc($fund_result);
            $row['current_amount'] = $fund_row['current_amount'] ?? 0;
        } else {
            $row['current_amount'] = 0;
        }

        // 參與者統計
        $participant_count = 0;
        $count_sql = "SELECT COUNT(*) AS total FROM funding_people WHERE funding_id = $project_id";
        $count_result = mysqli_query($link, $count_sql);
        if ($count_result && mysqli_num_rows($count_result) > 0) {
            $count_row = mysqli_fetch_assoc($count_result);
            $participant_count = $count_row['total'];
        }

        // 計算募資百分比
        $funding_goal = $row['funding_goal'];
        $current_amount = $row['current_amount'];
        $progress_percent = ($funding_goal > 0) ? ($current_amount / $funding_goal) * 100 : 0;
    } else {
        echo "查無專案資料";
        exit;
    }
    ?>


    <div class="main-container">
        <div class="left">
            <h1 class="title"><?php echo htmlspecialchars($row['project_title']); ?></h1>
            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="專案圖片" class="project-image">




            <div class="progress-text-box">
                <p><strong>專案募資成功！</strong></p>
                <p>在 <strong><?php echo htmlspecialchars($end_date); ?></strong> 募資結束前，您都可以持續贊助此計畫。</p>
            </div>
            <div class="tabs">
                <div class="tab active" onclick="showTab(0)">專案內容</div>
                <div class="tab" onclick="showTab(1)">進度回報</div>
                <div class="tab" onclick="showTab(2)">常見問題</div>
                <div class="tab" onclick="showTab(3)">留言</div>
            </div>

            <div class="tab-content active">
                <p><?php echo nl2br(htmlspecialchars($row['project_description'])); ?></p>
                <p>附件：<a href="file/專案說明.pdf" download>專案說明.pdf</a></p>
            </div>


            <div class="tab-content">

                <div class="progress-card">
                    <div class="progress-header">
                        <h3 class="progress-title">進度標題</h3>
                        <span class="progress-date">2025/04/18</span>
                    </div>
                    <div class="progress-content">
                        <!--內文放這，不要用<P>直接放不然css會失效-->

                    </div>
                    <div class="progress-footer">
                        <a href="#" class="read-more">查看更多</a>
                    </div>
                </div>

                <div class="progress-card">
                    <div class="progress-header">
                        <h3 class="progress-title">進度標題</h3>
                        <span class="progress-date">2025/04/18</span>
                    </div>
                    <div class="progress-content">
                        <!--內文放這，不要用<P>直接放不然css會失效-->

                    </div>
                    <div class="progress-footer">
                        <a href="#" class="read-more">查看更多</a>
                    </div>
                </div>

            </div>
            <div class="tab-content">


                <div class="faq-list">
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">
                            <div class="faq-meta">
                                <div class="faq-date">更新於 2025/03/24zz</div>
                                <div class="faq-title">如何確認是否贊助成功？</div>
                            </div>
                            <div class="faq-arrow"><i class="fa-solid fa-caret-down"></i></div>
                        </div>
                        <div class="faq-answer">
                            贊助成功後，您會收到系統寄出的通知信，並可在會員中心查看贊助紀錄。
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">
                            <div class="faq-meta">
                                <div class="faq-date">更新於 2025/03/24 </div>
                                <div class="faq-title">uhhhhh</div>
                            </div>
                            <div class="faq-arrow"><i class="fa-solid fa-caret-down"></i></div>
                        </div>
                        <div class="faq-answer">
                            <ol>
                                <li>gchg</li>
                                <li>vyjfu</li>

                            </ol>
                        </div>
                    </div>

                </div>


            </div>
            <div class="tab-content">

                <section class="comments">
                    <div class="comment-header">
                        <h4>留言區</h4>
                    </div>

                    <div class="comment-input">
                        <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
                        <textarea id="comment-text" maxlength="150" placeholder="我要留言..."></textarea>
                        <button id="submit-comment"><i class="fa-solid fa-paper-plane"></i></button>
                    </div>

                    <ul class="comment-list">
                        <li class="comment-item">
                            <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
                            <div class="comment-body">
                                <div class="comment-meta">
                                    <span class="user-name">學生</span>
                                    <span class="comment-time">2025/04/18 14:32</span>
                                </div>
                                <div class="comment-content">金主爸爸快來</div>
                            </div>
                        </li>
                        <li class="comment-item">
                            <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
                            <div class="comment-body">
                                <div class="comment-meta">
                                    <span class="user-name">老師</span>
                                    <span class="comment-time">2025/04/18 13:05</span>
                                </div>
                                <div class="comment-content">讚欸</div>
                            </div>
                        </li>
                    </ul>
                </section>

            </div>
        </div>

        <div class="sidebar">
            <div class="progress-info-box">
                <div class="circular-progress" style="--progress-percent: <?php echo $progress_percent; ?>%; --progress-color: #f9a825;">
                    <div class="progress-text"><?php echo round($progress_percent); ?>%</div>
                </div>

                <div class="money">
                    <h3><strong>NT$<?php echo number_format($row['current_amount']); ?></strong></h3>
                    <p>目標 <strong>NT$<?php echo number_format($row['funding_goal']); ?></strong></p>
                </div>
            </div>

            <div class="text-info">
                <p><i class="fa-solid fa-user icon-circle"></i>已有 <strong><?php echo $participant_count; ?></strong> 人參與募資</p>
                <p><i class="fa-solid fa-hourglass-half icon-circle"></i>剩餘 <strong><?php echo $days_remaining; ?></strong> 天</p>
            </div>

            <div class="button-group">
                <a href="pay.php"><button class="donate-btn">立即募資</button></a>

                <button class="share-btn">分享</button>
            </div>
        </div>


    </div>

    <script>
        function showTab(index) {
            const tabs = document.querySelectorAll(".tab");
            const contents = document.querySelectorAll(".tab-content");
            tabs.forEach((tab, i) => {
                tab.classList.toggle("active", i === index);
                contents[i].classList.toggle("active", i === index);
            });
        }
    </script>

    <script>
        function toggleFaq(el) {
            const item = el.closest('.faq-item');
            item.classList.toggle('open');
        }
    </script>

</body>

</html>