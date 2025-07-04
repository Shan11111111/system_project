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
    $project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;
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
        left join suggestion_assignments sa on sa.suggestion_assignments_id= p.suggestion_assignments_id
        LEFT JOIN advice a ON a.advice_id = sa.advice_id
        WHERE p.project_id = $project_id";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        die("SQL 查詢錯誤: " . mysqli_error($link));
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        //日期處理不要動
        $suggestion_assignments_id = $row['suggestion_assignments_id'] ?? 0;
        $start_date = $row['start_date'];
        $end_date = $row['end_date'];

        // 日期處理
        $today = new DateTime();
        $end_date_obj = new DateTime($end_date);

        if ($today > $end_date_obj) {
            $remaining_text = "已結束";
        } else {
            $interval = $today->diff($end_date_obj);
            $days_remaining = $interval->days;
            $hours_remaining = $interval->h;
            $minutes_remaining = $interval->i;
        }

        // 設定時區為 GMT+8（台灣）
        $timezone = new DateTimeZone('Asia/Taipei');

        // 建立現在時間，指定時區
        $today = new DateTime('now', $timezone);

        // 取得資料庫的 end_date
        $end_date_obj = new DateTime($end_date, $timezone);

        // 比較兩個時間
        if ($today > $end_date_obj) {
            $remaining_text = "已結束";
        } else {
            $interval = $today->diff($end_date_obj);
            $days_remaining = $interval->days;
            $hours_remaining = $interval->h;
            $minutes_remaining = $interval->i;

            if ($days_remaining > 0) {
                $remaining_text = "剩餘 {$days_remaining} 天 {$hours_remaining} 小時";
            } elseif ($hours_remaining > 0) {
                $remaining_text = "剩餘 {$hours_remaining} 小時";
            } elseif ($minutes_remaining > 0) {
                $remaining_text = "剩餘不到 1 小時";
            } else {
                $remaining_text = "已結束";
            }
        }


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
                    $image_path = 'uploads/homepage.png';
                }
            } else {
                $image_path = 'uploads/homepage.png';
            }
        } else {
            $image_path = 'homepage.png';
        }

        $row['image_path'] = $image_path;

        // 募資金額總和
        $fund_sql = "SELECT SUM(donation_amount) AS current_amount FROM donation_record WHERE project_id = $project_id";
        $fund_result = mysqli_query($link, $fund_sql);
        if ($fund_result && mysqli_num_rows($fund_result) > 0) {
            $fund_row = mysqli_fetch_assoc($fund_result);
            $row['current_amount'] = $fund_row['current_amount'] ?? 0;
        } else {
            $row['current_amount'] = 0;
        }

        // 參與者統計
        $participant_count = 0;
        $count_sql = "SELECT COUNT(*) AS total FROM donation_record WHERE project_id = $project_id";
        $count_result = mysqli_query($link, $count_sql);
        if ($count_result && mysqli_num_rows($count_result) > 0) {
            $count_row = mysqli_fetch_assoc($count_result);
            $participant_count = $count_row['total'];
        }

        // 計算募資百分比
        $funding_goal = $row['funding_goal'];
        $current_amount = $row['current_amount'];
        if ($current_amount >= $funding_goal) {
            $funding_status_text = "專案募資成功！";
            // $state_sql = "UPDATE fundraising_projects SET status = '已成功' WHERE project_id = $project_id";
            // mysqli_query($link, $state_sql);
        } else {
            $funding_status_text = "募資專案尚未達標";
        }
        $progress_percent = ($funding_goal > 0) ? ($current_amount / $funding_goal) * 100 : 0;
    } else {
        echo "查無專案資料";
        exit;
    }

    $is_project_expired = ($today > $end_date_obj);

    ?>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_text'])) {
        $comment_text = trim($_POST['comment_text']);
        $user_id = $_POST['user_id']; // 如果未登入，設為 0 或其他預設值
        $project_id = $_POST['project_id'] ?? 0; // 確保 project_id 也有值
        $method = $_POST['method'] ?? '';

        if (!empty($comment_text) && $project_id > 0 && $user_id > 0 && $method === 'comment_send') {
            $stmt = $link->prepare("INSERT INTO funding_comments (project_id, user_id, comment_text, created_at) VALUES (?, ?, ?, NOW())");

            // 檢查 prepare 是否成功
            if ($stmt === false) {
                die("SQL 語句準備失敗: " . $link->error);
            }

            $stmt->bind_param("iis", $project_id, $user_id, $comment_text);

            if ($stmt->execute()) {
                echo "<script>alert('留言成功！');</script>";
                echo "<script>window.location.href = 'funding_detail.php?project_id=$project_id';</script>";
            } else {
                echo "<script>alert('留言失敗，請稍後再試！');</script>";
                echo "<script>window.location.href = 'funding_detail.php?project_id=$project_id';</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('留言內容不可為空，或未登入！');</script>";
            echo "<script>window.location.href = 'funding_detail.php?project_id=$project_id';</script>";
        }
    }
    ?>

    <div class="main-container">
        <div class="left">
            <h1 class="title"><?php echo htmlspecialchars($row['project_title']); ?></h1>
            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="專案圖片" class="project-image">


            <!--don't move-->
            <div class="progress-text-box <?php echo $is_project_expired ? 'expired' : ''; ?>">
                <?php if ($is_project_expired): ?>
                    <p class="expired-text"><strong>募資專案已結束</strong></p>
                <?php else: ?>
                    <p><strong><?php echo htmlspecialchars($funding_status_text); ?></strong></p>
                    <p>在 <strong><?php echo htmlspecialchars($end_date); ?></strong> 募資結束前，您都可以持續贊助此計畫。</p>
                <?php endif; ?>
            </div>

            <div class="tabs">
                <div class="tab active" onclick="showTab(0)">專案內容</div>
                <div class="tab" onclick="showTab(1)">進度回報</div>
                <div class="tab" onclick="showTab(2)">常見問題</div>
                <div class="tab" onclick="showTab(3)">留言</div>
            </div>

            <div class="tab-content active">
                <p><?php echo nl2br(htmlspecialchars($row['project_description'])); ?></p>
                <?php
                // 資料庫連線
                $link = mysqli_connect('localhost', 'root', '', 'system_project');
                if (!$link) {
                    die('資料庫連線失敗: ' . mysqli_connect_error());
                }

                // 取得專案 ID
                $project_id = $_GET['project_id'] ?? 0;

                // 查附件檔案路徑
                $sql_file = "
    SELECT sa.proposal_file_path
    FROM fundraising_projects fp
    JOIN suggestion_assignments sa ON fp.suggestion_assignments_id = sa.suggestion_assignments_id
    WHERE fp.project_id = ?
";
                $stmt_file = mysqli_prepare($link, $sql_file);
                mysqli_stmt_bind_param($stmt_file, 'i', $project_id);
                mysqli_stmt_execute($stmt_file);
                $result_file = mysqli_stmt_get_result($stmt_file);
                $row_file = mysqli_fetch_assoc($result_file);

                // 顯示下載連結（若有檔案）
                if (!empty($row_file['proposal_file_path'])) {
                    $file_url = htmlspecialchars($row_file['proposal_file_path']);
                    $file_name = htmlspecialchars(basename($row_file['proposal_file_path']));
                    echo '<div class="project-file">';
                    echo ' 附件：<a href="' . $file_url . '" download="' . $file_name . '">' . $file_name . '</a>';
                    echo '</div>';
                }
                ?>

            </div>


            <div class="tab-content">
                <?php
                // 查詢進度回報
                $progress_sql = "SELECT title, content, file_path, updated_time 
                     FROM execution_report 
                     WHERE project_id = ? 
                     ORDER BY updated_time DESC";
                $stmt_progress = $link->prepare($progress_sql);

                if (!$stmt_progress) {
                    die("SQL 語句準備失敗：" . $link->error);
                }

                $stmt_progress->bind_param("i", $project_id);
                $stmt_progress->execute();
                $progress_result = $stmt_progress->get_result();

                if ($progress_result && $progress_result->num_rows > 0) {
                    while ($progress = $progress_result->fetch_assoc()) {
                        echo '<div class="progress-card" onclick="showFullContent(this)" data-full-content="' . htmlspecialchars($progress['content']) . '" data-title="' . htmlspecialchars($progress['title']) . '">';
                        echo '    <div class="progress-header">';
                        echo '        <h3 class="progress-title">' . htmlspecialchars($progress['title']) . '</h3>';
                        echo '        <span class="progress-date">' . htmlspecialchars($progress['updated_time']) . '</span>';
                        echo '    </div>';
                        echo '    <div class="progress-content">';
                        echo '        <div class="content-preview">' . nl2br(htmlspecialchars(mb_substr($progress['content'], 0, 100))) . '...</div>';
                        echo '    </div>';
                        if (!empty($progress['file_path'])) {
                            echo '    <div class="progress-footer">';
                            echo '        <a href="' . htmlspecialchars($progress['file_path']) . '" download>下載附件</a>';
                            echo '    </div>';
                        }
                        echo '</div>';
                    }
                } else {
                    echo '<p>目前尚無進度回報。</p>';
                }
                ?>



            </div>

            <div class="tab-content">
                <div class="faq-list">
                    <?php
                    // 查詢常見問題
                    $faq_query = "SELECT question, reply, updated_on FROM funding_faq WHERE project_id = $project_id ORDER BY updated_on DESC";
                    $faq_result = mysqli_query($link, $faq_query);

                    if (!$faq_result) {
                        die("SQL 查詢失敗：" . mysqli_error($link));
                    }

                    if (mysqli_num_rows($faq_result) > 0) {
                        while ($faq = mysqli_fetch_assoc($faq_result)) {
                            echo '<div class="faq-item">';
                            echo '<div class="faq-question" onclick="toggleFaq(this)">';
                            echo '<div class="faq-meta">';
                            echo '<div class="faq-date">更新於 ' . htmlspecialchars($faq['updated_on']) . '</div>';
                            echo '<div class="faq-title">' . htmlspecialchars($faq['question']) . '</div>';
                            echo '</div>';
                            echo '<div class="faq-arrow"><i class="fa-solid fa-caret-down"></i></div>';
                            echo '</div>';
                            echo '<div class="faq-answer">' . nl2br(htmlspecialchars($faq['reply'])) . '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>目前沒有常見問題。</p>';
                    }
                    ?>
                </div>
            </div>

            <div class="tab-content">

                <section class="comments">
                    <div class="comment-header">
                        <h4>留言區</h4>
                    </div>
                    <form method="POST" action="">
                        <div class="comment-input">

                            <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
                            <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                            <input type="hidden" name="method" value="comment_send">
                            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'] ?? 0; ?>">
                            <textarea id="comment-text" name="comment_text" maxlength="150"
                                placeholder="我要留言..."></textarea>
                            <button type="submit" id="submit-comment"><i class="fa-solid fa-paper-plane"></i></button>

                        </div>
                    </form>
                    <ul class="comment-list">
                        <?php
                        $comment_query = "SELECT u.user_id, fc.comment_text, fc.created_at 
                      FROM funding_comments fc
                      JOIN users u ON fc.user_id = u.user_id
                      WHERE fc.project_id = $project_id 
                      ORDER BY fc.created_at DESC";
                        $comment_result = mysqli_query($link, $comment_query);

                        if ($comment_result && mysqli_num_rows($comment_result) > 0) {
                            while ($comment = mysqli_fetch_assoc($comment_result)) {
                                echo '<li class="comment-item">';
                                echo '<div class="user-avatar"><i class="fa-solid fa-user"></i></div>';
                                echo '<div class="comment-body">';
                                echo '<div class="comment-meta">';
                                echo '<span class="user-name">' . htmlspecialchars($comment['user_id']) . '</span>';
                                echo '<span class="comment-time">' . htmlspecialchars($comment['created_at']) . '</span>';
                                echo '</div>';
                                echo '<div class="comment-content">' . htmlspecialchars($comment['comment_text']) . '</div>';
                                echo '</div>';
                                echo '</li>';
                            }
                        } else {
                            echo '<li class="comment-item">目前尚無留言。</li>';
                        }
                        ?>
                    </ul>
                </section>

            </div>
        </div>

        <div class="sidebar" id="sidebar">
            <div class="progress-info-box">
                <div class="circular-progress"
                    style="--progress-percent: <?php echo $progress_percent; ?>%; --progress-color: #f9a825;">
                    <div class="progress-text"><?php echo round($progress_percent); ?>%</div>
                </div>

                <div class="money">
                    <h3><strong>NT$<?php echo number_format($row['current_amount']); ?></strong></h3>
                    <p>目標 <strong>NT$<?php echo number_format($row['funding_goal']); ?></strong></p>
                </div>
            </div>

            <div class="text-info">
                <p><i class="fa-solid fa-user icon-circle"></i>已有 <strong><?php echo $participant_count; ?></strong>
                    人參與募資</p>
                <!--don't move-->
                <p><i class="fa-solid fa-hourglass-half icon-circle"></i>
                    <strong><?php echo $remaining_text; ?></strong>
                </p>
            </div>
            <!--don't move-->
            <div class="button-group">
                <div class="donate-button">
                    <?php if ($is_project_expired): ?>
                        <!-- 時間結束，按鈕禁用 -->
                        <button class="donate-btn" disabled
                            style="background-color: gray; cursor: not-allowed;">募資已結束</button>
                    <?php else: ?>
                        <!-- 可以募資 -->
                        <a href="pay.php?id=<?php echo "$project_id"; ?>" class="donate-btn">立即募資</a>
                    <?php endif; ?>
                </div>
                <button class="share-btn" onclick="copyLink()">分享 <i class="fa-solid fa-share"></i></button>


            </div>
        </div>

        <script>
            function copyLink() {
                const url = window.location.href;
                navigator.clipboard.writeText(url)
                    .then(() => alert('連結已複製到剪貼簿！'))
                    .catch(() => alert('複製失敗，請手動複製網址'));
            }
        </script>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const footer = document.querySelector('.footer');

            function updateSidebarPosition() {
                const sidebarHeight = sidebar.offsetHeight;
                const sidebarTop = sidebar.getBoundingClientRect().top + window.scrollY;
                const footerTop = footer.offsetTop;

                const scrollY = window.scrollY;
                const offsetTop = 100; // navbar高度
                const buffer = 40; // sidebar底部留空間

                const sidebarBottom = scrollY + offsetTop + sidebarHeight;
                const footerStart = footerTop;

                const windowWidth = window.innerWidth;

                if (windowWidth > 768) {
                    // 桌機版
                    if (sidebarBottom + buffer >= footerStart) {
                        sidebar.style.position = 'absolute';
                        sidebar.style.top = (footerStart - sidebarHeight - buffer) + 'px';
                    } else {
                        sidebar.style.position = 'fixed';
                        sidebar.style.top = offsetTop + 'px';
                    }
                } else {
                    // 手機版
                    sidebar.style.position = 'static'; // 還原
                    sidebar.style.top = 'auto'; // 還原
                }
            }

            window.addEventListener('scroll', updateSidebarPosition);
            window.addEventListener('resize', updateSidebarPosition);
            updateSidebarPosition(); // 初始呼叫一次
        });
    </script>



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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showFullContent(element) {
            const fullContent = element.getAttribute('data-full-content');
            const title = element.getAttribute('data-title'); // 獲取回報的標題
            Swal.fire({
                title: title, // 使用回報的標題
                html: `<div style="text-align: left;">${fullContent.replace(/\n/g, '<br>')}</div>`,
                confirmButtonText: '關閉',
                confirmButtonColor: '#3085d6',
            });
        }
    </script>



</body>


</html>