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

    <link rel="stylesheet" href="css/advice_detail.css">
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
                        <a href="ongoing_funding_search.php">進行中計畫</a>
                        <a href="due_funding_search.php">成功案例</a>
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

                    <a href="advice_search.php">建言瀏覽</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="dropbtn">募資</button>
                <div class="dropdown-content">
                    <a href="ongoing_funding_search.php">進行中計畫</a>
                    <a href="due_funding_search.php">成功案例</a>
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


    <?php
    // 從網址中取得建言的 ID
    $advice_id = isset($_GET['advice_id']) ? $_GET['advice_id'] : 0;

    // Step 1: 連接資料庫
    $link = mysqli_connect('localhost', 'root', '', 'system_project');

    // 檢查資料庫連線是否成功
    if (!$link) {
        die("資料庫連線失敗: " . mysqli_connect_error());
    }

    // Step 3: 查詢公告資料，根據建言 ID 查詢
    $sql = "SELECT a.advice_id, a.user_id, a.advice_title, a.category, a.advice_content, a.advice_state, a.announce_date, a.agree, ai.file_path
    FROM advice a
    LEFT JOIN advice_image ai ON ai.advice_id = a.advice_id
    WHERE a.advice_id = $advice_id";

    $advice_id = isset($_GET['advice_id']) ? intval($_GET['advice_id']) : 0;
    $status = isset($row['advice_state']) ? $row['advice_state'] : 'pending';


    // 執行查詢
    $result = mysqli_query($link, $sql);

    // 檢查查詢是否成功
    if (!$result) {
        die("查詢錯誤: " . mysqli_error($link));
    }

    // Step 4: 顯示公告
    if ($row = mysqli_fetch_assoc($result)) {

        $categoryMap = [
            "all" => "全部分類",
            "equipment" => "設施改善",
            "academic" => "學術發展",
            "club" => "社團活動",
            "welfare" => "公益關懷",
            "environment" => "環保永續",
            "other" => "其他"
        ];
        $categoryKey = $row['category'];
        $categoryName = isset($categoryMap[$categoryKey]) ? $categoryMap[$categoryKey] : '未知分類';
        $target = 3; // 附議目標人數
        $agree = $row['agree'];
        $percent = min(100, floor(($agree / $target) * 100));

        $remain = max(0, $target - $agree);
        $color = $percent >= 100 ? '#4caf50' : '#2196f3'; // 綠或藍
        ?>
        <div class="container">
            <main class="suggestion-detail">
                <!-- 標題 -->
                <h1 class="title" id="advice-title"><?php echo htmlspecialchars($row['advice_title']); ?></h1>
                <?php
                $agree = (int) $row['agree'];
                $agreeThreshold = 3; //
            

                $announceDate = new DateTime($row['announce_date']);
                $dueDate = clone $announceDate;
                $dueDate->modify('+30 days');
                $now = new DateTime();
                $expired = $now > $dueDate;

                // 狀態
                if ($agree >= $agreeThreshold) {
                    $statusClass = 'status-passed';
                    $statusLabel = '已達標';
                } elseif ($expired) {
                    $statusClass = 'status-failed';
                    $statusLabel = '未達標';
                } else {
                    $statusClass = 'status-pending';
                    $statusLabel = '進行中';
                }
                ?>

                <span class="suggestion-status <?php echo $statusClass; ?>">
                    <?php echo $statusLabel; ?>
                </span>



                <?php
                include 'db_connection.php';

                $advice_id = $_GET['advice_id'] ?? 0;

                $sql = "SELECT * FROM advice WHERE advice_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$advice_id]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$row) {
                    die("查無此建言。");
                }

                // 設定門檻與狀態
                $agreeThreshold = 3;
                $agreeCount = (int) $row['agree'];
                $replyState = trim($row['advice_state'] ?? '未回覆');
                $announceDate = new DateTime($row['announce_date']);
                $now = new DateTime();

                // 附議期限 = 提案日 + 15 天
                $dueDate = clone $announceDate;
                $dueDate->modify('+30 days');

                $expired = $now > $dueDate;
                $rejected = $expired && ($agreeCount < $agreeThreshold);

                // 狀態指標
                $status = 0;

                if (!$rejected) {
                    if ($agreeCount >= 0)
                        $status = 1;
                    if ($agreeCount >= $agreeThreshold)
                        $status = 2;
                    if ($replyState === '已回覆')
                        $status = 3;
                }
                ?>

                <?php


                // 查主建言的狀態
                $stmt1 = $link->prepare("SELECT advice_state FROM advice WHERE advice_id = ?");
                $stmt1->bind_param("i", $advice_id);
                $stmt1->execute();
                $result1 = $stmt1->get_result();
                $advice = $result1->fetch_assoc();
                $state = $advice['advice_state'] ?? '未處理';

                // 查校方最新處理內容
                $stmt2 = $link->prepare("SELECT content, state_time FROM advice_state WHERE advice_id = ? ORDER BY state_time DESC LIMIT 1");
                $stmt2->bind_param("i", $advice_id);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                $response = $result2->fetch_assoc();

                $content = $response['content'] ?? null;
                $update_time = $response['state_time'] ?? null;
                ?>

                <?php if ($rejected): ?>
                    <div class="rejected-message"
                        style="color: #b00020; background: #fbe9e7; padding: 15px; border-radius: 8px;margin-bottom: 20px;">
                        附議人數未達標，建言提案未通過。<br>
                        （附議期限已於 <?php echo $dueDate->format('Y-m-d'); ?> 結束）
                    </div>
                <?php else: ?>
                    <div class="progress-tracker">
                        <!-- 提案 -->
                        <div class="step <?php echo $status >= 0 ? 'completed' : ''; ?>">
                            <div class="circle"></div>
                            <div class="label">提案</div>
                            <div class="date"><?php echo htmlspecialchars($row['announce_date']); ?></div>
                        </div>

                        <div class="bar <?php echo $status >= 1 ? 'completed' : ''; ?>"></div>

                        <!-- 附議中 -->
                        <div class="step <?php echo $status == 1 ? 'active' : ($status > 1 ? 'completed' : ''); ?>">
                            <div class="circle"></div>
                            <div class="label">附議中</div>
                        </div>

                        <div class="bar <?php echo $status >= 2 ? 'completed' : ''; ?>"></div>

                        <!-- 附議達標 -->
                        <div class="step <?php echo $status == 2 ? 'active' : ($status > 2 ? 'completed' : ''); ?>">
                            <div class="circle"></div>
                            <div class="label">附議達標<br>等待校方回應</div>
                            <div class="date"><?= $update_time ?></div>
                        </div>

                        <div class="bar <?php echo $status >= 3 ? 'completed' : ''; ?>"></div>

                        <!-- 校方已回應 -->
                        <div class="step <?php echo $status == 3 ? 'active' : ''; ?>">
                            <div class="circle"></div>
                            <div class="label">校方已回應</div>
                        </div>
                    </div>


                <?php endif; ?>




                <div class="advice">
                    <!-- 發布人與分類 -->
                    <section class="meta">
                        <p id="advice-author">發布人：<?php echo htmlspecialchars($row['user_id']); ?></p>
                        <!-- 假設 user_id 是發布人 -->
                        <p id="advice-category">分類：<?php echo htmlspecialchars($categoryName); ?></p>
                    </section>

                    <!-- 圖片或 PDF -->
                    <div class="media-sidebar-wrapper">
                        <section class="media">
                            <?php if (!empty($row['file_path'])) { ?>
                                <img id="advice-image" src="<?php echo htmlspecialchars($row['file_path']); ?>" alt="建言圖片" />
                            <?php } else { ?>
                                <img id="advice-image" src="./uploads/homepage.png" alt="預設建言圖片" />
                            <?php } ?>
                        </section>

                        <div class="sidebar" id="sidebar">
                            <div class="progress-info-box">
                                <div class="circular-progress" style="--progress-percent: <?php echo $percent; ?>%;
                                 --progress-color: <?php echo $color; ?>;">
                                    <div class="progress-text"><?php echo $percent; ?>%</div>
                                </div>


                                <div class="people">
                                    <h5><strong>目前 <?php echo $agree; ?> 人 </strong></h5>
                                    <p><strong> 還差 <?php echo $remain; ?> 人</strong></p>
                                </div>
                            </div>



                            <div class="deadline">
                                <!--<p><i class="fa-solid fa-user icon-circle"></i>已有 <strong>30</strong> 人參與募資</p>-->
                                <p><i class="fa-solid fa-hourglass-half  icon-circle"></i></i> <strong>
                                        截止日：<?php echo date('Y/m/d', strtotime($row['announce_date'] . ' +15 days')); ?></strong>
                                </p>

                            </div>

                            <div class="button-group">
                                <!-- 隱藏 announce_date 作為 JS 用 -->
                                <span id="announce-date" style="display:none;"><?php echo $row['announce_date']; ?></span>

                                <!-- 附議按鈕 -->
                                <form id="insertForm" action="agree_insert.php" method="POST">
                                    <input type="hidden" name="advice_id" value="<?php echo $advice_id; ?>">

                                    <button class="agree-btn" id="agree-btn" onclick="handleAgree(event)">
                                        <i class="fa-solid fa-stamp"></i> 附議
                                    </button>
                                </form>

                                <script>
                                    function handleAgree(event) {
                                        event.preventDefault(); // 阻止按鈕預設送出表單

                                        const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

                                        if (!isLoggedIn) {
                                            Swal.fire({
                                                icon: 'warning',
                                                title: '請先登入',
                                                text: '附議為學生與教職人員專屬功能！',
                                                confirmButtonText: '確定',
                                                confirmButtonColor: '#3085d6',
                                                focusConfirm: false,
                                                didOpen: () => {
                                                    document.body.style.overflow = 'hidden';
                                                },
                                                didClose: () => {
                                                    document.body.style.overflow = '';
                                                    window.scrollTo(0, 0);
                                                }
                                            });
                                        } else {
                                            document.getElementById('insertForm').submit();
                                        }
                                    }
                                </script>

                                <div class="collect_share">
                                    <button class="collect-btn">收藏<i class="fa-solid fa-heart"></i></button>
                                    <button class="share-btn" onclick="copyLink()">分享 <i
                                            class="fa-solid fa-share"></i></button>

                                </div>

                            </div>
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



                    <!-- 內文 -->
                    <section class="content">
                        <p id="advice-content"><?php echo nl2br(htmlspecialchars($row['advice_content'])); ?></p>
                        <?php
                        // 連接資料庫
                        $link = mysqli_connect('localhost', 'root', '', 'system_project');
                        if (!$link) {
                            die('資料庫連線失敗: ' . mysqli_connect_error());
                        }

                        // 取得建言 ID
                        $advice_id = $_GET['advice_id'] ?? 0;

                        // 查詢附加檔案
                        $sql_file = "SELECT file_name, file_path FROM files WHERE advice_id = ?";
                        $stmt_file = mysqli_prepare($link, $sql_file);
                        mysqli_stmt_bind_param($stmt_file, 'i', $advice_id);
                        mysqli_stmt_execute($stmt_file);
                        $result_file = mysqli_stmt_get_result($stmt_file);

                        // 有檔案才顯示
                        if (mysqli_num_rows($result_file) > 0) {
                            echo '<div class="file-list">';
                            while ($file = mysqli_fetch_assoc($result_file)) {
                                echo '<div class="file-item">';
                                echo '<i class="fa-solid fa-file"></i> 附加文件:<a href="' . htmlspecialchars($file['file_path']) . '" download="' . htmlspecialchars($file['file_name']) . '">' . htmlspecialchars($file['file_name']) . '</a>';
                                echo '</div>';
                            }
                            echo '</div>';
                        }
                        ?>

                    </section>

                </div>
            </main>

            <?php


            $advice_id = $_GET['advice_id'] ?? 0;

            // 查主建言的狀態
            $stmt1 = $link->prepare("SELECT advice_state FROM advice WHERE advice_id = ?");
            $stmt1->bind_param("i", $advice_id);
            $stmt1->execute();
            $result1 = $stmt1->get_result();
            $advice = $result1->fetch_assoc();
            $state = $advice['advice_state'] ?? '未處理';

            // 查 suggestion_assignments_id
            $stmt2 = $link->prepare("SELECT suggestion_assignments_id FROM suggestion_assignments WHERE advice_id = ?");
            $stmt2->bind_param("i", $advice_id);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $assignment = $result2->fetch_assoc();
            $suggestion_assignments_id = $assignment['suggestion_assignments_id'] ?? null;

            // 預設回覆內容
            $content = null;
            $reply_update_time = null;

            // 如果有找到 suggestion_assignments_id，再去抓 replies 表
            if ($suggestion_assignments_id) {
                $stmt3 = $link->prepare("SELECT reply_text, replied_at FROM replies WHERE suggestion_assignments_id = ? ORDER BY replied_at DESC LIMIT 1");
                $stmt3->bind_param("i", $suggestion_assignments_id);
                $stmt3->execute();
                $result3 = $stmt3->get_result();
                $reply = $result3->fetch_assoc();

                if ($reply) {
                    $content = $reply['reply_text'];
                    $reply_update_time = $reply['replied_at'];
                }
            }
            ?>

            <div class="school-reply-card">
                <div class="reply-header">
                    <h5><strong>校方回覆</strong></h5>
                    <?php if ($reply_update_time): ?>
                        <span class="reply-time">最後更新：<?= htmlspecialchars($reply_update_time) ?></span>
                    <?php endif; ?>
                </div>
                <span class="reply-status <?= $state === '已回覆' ? 'replied' : 'pending' ?>">
                    <?= $state === '已回覆' ? '<i class="fa-solid fa-circle"></i>&nbsp已回覆' : '<i class="fa-solid fa-circle"></i>&nbsp尚未回覆' ?>
                </span>
                <div class="reply-content">
                    <p>
                        <?= $content ? nl2br(htmlspecialchars($content)) : '本建言尚待校方回覆，請耐心等候。' ?>
                    </p>
                </div>
            </div>



            <?php
    } else {
        echo "沒有找到相關建言。";
    }

    // 查詢留言資料
    $comments = [];
    $commentSql = "SELECT user_id, comment_content, comment_time, advice_id FROM comments WHERE advice_id = $advice_id";
    $commentResult = mysqli_query($link, $commentSql);

    if ($commentResult) {
        while ($commentRow = mysqli_fetch_assoc($commentResult)) {
            $comments[] = [
                'username' => htmlspecialchars($commentRow['user_id']),
                'text' => htmlspecialchars($commentRow['comment_content']),
                'time' => $commentRow['comment_time']
            ];
        }
    }

    // 將留言資料轉換為 JSON 格式
    echo "<script>const allComments = " . json_encode($comments) . ";</script>";
    ?>



        <section class="comments">
            <div class="comment-header">
                <h4>留言區</h4>
                <select id="sort-comments">
                    <option value="latest">留言時間：最新</option>
                    <option value="oldest">留言時間：最舊</option>
                </select>
            </div>

            <form id="commentForm">
                <input type="hidden" id="advice_id" name="advice_id"
                    value="<?php echo htmlspecialchars($advice_id); ?>">
                <div class="comment-input">
                    <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
                    <textarea id="comment_text" name="comment_text" placeholder="我要留言...(最多150字)" maxlength="150"
                        required></textarea>
                    <button id="submit-comment" type="submit"><i class="fa-solid fa-paper-plane"></i></button>
                </div>
            </form>
            <div id="responseMessage" style="margin-top: 20px;"></div>
            <script>
                function checkDeadline() {
                    const dateText = document.getElementById('announce-date').textContent.trim();
                    const announceDate = new Date(dateText);
                    const now = new Date();

                    // 設定截止日為提案日 + 30 天
                    const deadline = new Date(announceDate);
                    deadline.setDate(deadline.getDate() + 30);

                    if (now > deadline) {
                        // 超過時間，禁用按鈕
                        const btn = document.getElementById('agree-btn');
                        btn.disabled = true;
                        btn.innerHTML = '附議已截止';
                        btn.style.backgroundColor = '#ccc'; // 可選：讓按鈕變灰色
                        btn.style.cursor = 'not-allowed';
                    }
                }

                // 執行檢查
                checkDeadline();
            </script>


            <script>


                document.getElementById('commentForm').addEventListener('submit', async function (event) {
                    event.preventDefault();

                    // 從 PHP 將登入狀態帶入 JavaScript
                    const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

                    if (!isLoggedIn) {
                        // 若未登入，跳出提醒
                        Swal.fire({
                            icon: 'warning',
                            title: '請先登入',
                            text: '留言功能為會員專屬功能！',
                            confirmButtonText: '確定',
                            confirmButtonColor: '#3085d6',
                            focusConfirm: false, // 禁用自動聚焦
                            didOpen: () => {
                                document.body.style.overflow = 'hidden'; // 禁止滾動
                            },
                            didClose: () => {
                                document.body.style.overflow = ''; // 恢復滾動
                            }
                        });
                        return; // 阻止表單提交
                    }

                    const adviceId = document.getElementById('advice_id').value;
                    const commentText = document.getElementById('comment_text').value;

                    const responseMessage = document.getElementById('responseMessage');
                    responseMessage.textContent = '提交中...';

                    try {
                        const response = await fetch('./comments/submit_comment.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: new URLSearchParams({
                                advice_id: adviceId,
                                comment_text: commentText
                            })
                        });

                        const result = await response.json();

                        if (result.status === 'success') {
                            responseMessage.style.color = 'green';
                            responseMessage.textContent = result.message;

                            // 清空 comment_text
                            document.getElementById('comment_text').value = '';

                            // 動態新增留言到列表
                            const commentList = document.querySelector('.comment-list');
                            const newComment = document.createElement('li');
                            newComment.classList.add('comment-item');
                            newComment.innerHTML = `
                                <div class="user-avatar">👤</div>
                                <div class="comment-content">
                                    <p class="comment-meta">
                                        <strong>${result.username}</strong>
                                        <span class="comment-time">剛剛</span>
                                    </p>
                                    <p class="comment-text">${result.comment_text}</p>
                                </div>
                            `;
                            commentList.prepend(newComment); // 新留言插入到最前面
                        } else {
                            responseMessage.style.color = 'red';
                            responseMessage.textContent = result.message;
                        }

                        // 訊息五秒後消失
                        setTimeout(() => {
                            responseMessage.textContent = '';
                        }, 5000);

                    } catch (error) {
                        responseMessage.style.color = 'red';
                        responseMessage.textContent = '提交失敗，請稍後再試。';
                        console.error('Error:', error);

                        // 訊息五秒後消失
                        setTimeout(() => {
                            responseMessage.textContent = '';
                        }, 5000);
                    }
                });
            </script>




            <ul class="comment-list"></ul>


            <div class="pagination">
                <button id="prev-page">上一頁</button>
                <span id="page-indicator"></span>
                <button id="next-page">下一頁</button>
            </div>
        </section>

        </main>
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
</body>


<!-- Fixed 按鈕 -->
<div class="fixed-buttons">
    <button class="back-btn" onclick="window.location.href='advice_search.php'"><i class="fa-solid fa-arrow-left"></i>
        <span>返回</span>
    </button>
    <!--
    <form id="insertForm" action="agree_insert.php" method="POST">
        <input type="hidden" name="advice_id" value="<?php echo $advice_id; ?>">

        單一按鈕 
    <button type="button" id="agree-btn" class="agree-fixed-btn" onclick="handleAgree()">
            <i class="fa-solid fa-stamp"></i>
            <span>附議</span>
        </button>
    </form>

    <script>
        function handleAgree() {
            // 從 PHP 將登入狀態帶入 JavaScript
            const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

            if (!isLoggedIn) {
                // 若未登入，跳出提醒
                Swal.fire({
                    icon: 'warning',
                    title: '請先登入',
                    text: '附議為學生與教職人員專屬功能！',
                    confirmButtonText: '確定',
                    confirmButtonColor: '#3085d6',
                    focusConfirm: false, // 禁用自動聚焦
                    didOpen: () => {
                        document.body.style.overflow = 'hidden'; // 禁止滾動
                    },
                    didClose: () => {
                        document.body.style.overflow = ''; // 恢復滾動
                        window.scrollTo(0, 0); // 避免滾動位置錯誤
                    }
                });
            } else {
                // 已登入，送出表單
                document.getElementById('insertForm').submit();
            }
        }
    </script>-->


    <!-- <script>
            document.addEventListener("DOMContentLoaded", function () {
                let agreeBtn = document.querySelector(".agree-btn");
                agreeBtn.addEventListener("click", function (event) {
                    event.preventDefault(); // 防止 a 標籤跳轉
                    let adviceId = this.getAttribute("data-advice-id");
                    document.getElementById("advice_id").value = adviceId;
                    document.getElementById("insertForm").submit(); // 提交表單
                });
            });
        </script> -->



    <a href="#top" class="top-btn">Top</a>
</div>



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

    document.addEventListener('DOMContentLoaded', function () {
        // 從 URL 的上頁連結中獲取 status
        const statusFromPreviousPage = new URLSearchParams(window.location.search).get('id');
        const status = <?php echo json_encode($status); ?>;

        const statusMap = {
            passed: {
                text: '通過',
                class: 'status-passed'
            },
            failed: {
                text: '未通過',
                class: 'status-failed'
            },
            pending: {
                text: '未處理',
                class: 'status-pending'
            }
        };

        const statusEl = document.getElementById('suggestion-status');
        if (statusEl) {
            if (statusMap[status]) {
                statusEl.textContent = statusMap[status].text;
                statusEl.className = `suggestion-status ${statusMap[status].class}`;
            } else {
                statusEl.textContent = '未知狀態';
                statusEl.className = 'suggestion-status status-unknown';
            }
        } else {
            console.error('找不到 suggestion-status 元素');
        }

        const commentList = document.querySelector('.comment-list');
        const pageIndicator = document.getElementById('page-indicator');
        const prevBtn = document.getElementById('prev-page');
        const nextBtn = document.getElementById('next-page');
        const sortSelect = document.getElementById('sort-comments');

        const commentsPerPage = 10;
        let currentPage = 1;
        let currentSort = 'latest';

        // 計算留言與現在的時間差
        function timeAgo(dateString) {
            const now = new Date();
            const past = new Date(dateString);
            const diff = Math.floor((now - past) / 1000); // 秒數差

            if (diff < 60) return '剛剛';
            if (diff < 3600) return `${Math.floor(diff / 60)} 分鐘前`;
            if (diff < 86400) return `${Math.floor(diff / 3600)} 小時前`;
            return `${Math.floor(diff / 86400)} 天前`;
        }

        function renderComments() {
            let sortedComments = [...allComments];
            if (currentSort === 'latest') {
                sortedComments.reverse();
            }

            const start = (currentPage - 1) * commentsPerPage;
            const paginatedComments = sortedComments.slice(start, start + commentsPerPage);

            commentList.innerHTML = '';
            paginatedComments.forEach(comment => {
                const li = document.createElement('li');
                li.classList.add('comment-item');
                li.innerHTML = `
                    <div class="user-avatar">👤</div>
                    <div class="comment-content">
                        <p class="comment-meta">
                            <strong>${comment.username}</strong>
                            <span class="comment-time">${timeAgo(comment.time)}</span>
                        </p>
                        <p class="comment-text">${comment.text}</p>
                    </div>
                `;
                commentList.appendChild(li);
            });

            const totalPages = Math.ceil(allComments.length / commentsPerPage);
            pageIndicator.textContent = `第 ${currentPage} / ${totalPages} 頁`;
            prevBtn.disabled = currentPage === 1;
            nextBtn.disabled = currentPage === totalPages;
        }

        prevBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                renderComments();
            }
        });

        nextBtn.addEventListener('click', () => {
            const totalPages = Math.ceil(allComments.length / commentsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                renderComments();
            }
        });

        sortSelect.addEventListener('change', () => {
            currentSort = sortSelect.value;
            currentPage = 1;
            renderComments();
        });

        renderComments();
    });
</script>


</body>

</html>