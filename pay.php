<?php
session_start();
require_once 'db_connection.php'; // 確保包含資料庫連線檔案

// 接收募資專案 ID
$project_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 預設標題
$funding_title = '募資專案';

// 從資料庫查詢對應的標題
if ($project_id > 0) {
    $sql = "SELECT title FROM fundraising_projects WHERE project_id = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$project_id])) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $funding_title = htmlspecialchars($row['title']); // 避免 XSS
        } else {
            $funding_title = "查無此專案";
        }
    } else {
        die("SQL 執行失敗");
    }
} else {
    die("未提供有效的專案 ID");
}
?>
<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $funding_title; ?></title>
</head>

<body>
    <h3><?php echo $funding_title; ?></h3>
</body>

</html>

<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>孵仁</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link rel="stylesheet" href="css/pay.css">
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
                    <a href="advice_search.php">建言瀏覽</a>
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
    <div>
        <button class="back-btn" onclick="history.back()">← 返回</button>
    </div>




    <div class="donate-wrapper">
        <div class="donate-header">
            <h3 class="title"><?php echo $funding_title; ?></h3>
            <h5 class="donate-title">支持這個提案</h5>
        </div>
        <hr>
        <form class="donate-form" method="post" action="funding_function/donate_money.php"
            onsubmit="return beforeSubmit()">
            <label class="section-label">選擇金額</label>
            <div class="amount-options">
                <button type="button" onclick="setAmount(100)">100</button>
                <button type="button" onclick="setAmount(500)">500</button>
                <button type="button" onclick="setAmount(1000)">1000</button>
                <button type="button" onclick="setAmount(2000)">2000</button>
            </div>

            <div class="custom-amount">
                <span>NT$</span>
                <input type="number" id="customAmount" name="donate_money" placeholder="自訂金額" required>
            </div>

            <label class="section-label">信用卡付款</label>
            <input type="text" id="cardNumber" maxlength="19" placeholder="卡號 1234 5678 9012 3456">
            <div class="card-row">
                <input type="text" id="expirationDate" placeholder="MM/YY" maxlength="5">
                <input type="text" maxlength="3" placeholder="CVC">
            </div>
            <hr>
            <label class="section-label">姓名（選填，我們將通知您募資專案的後續進度）</label>
            <input type="text" name="people_name" placeholder="輸入您的姓名">

            <label class="section-label">Email（選填，我們將通知您募資專案的後續進度）</label>
            <input type="email" name="email" placeholder="your@email.com">

            <!-- 隱藏欄位：指定募資專案 ID，可視情況修改 -->
            <input type="hidden" name="funding_id" value="<?php echo $project_id; ?>">

            <button class="donate-btn" type="submit">送出付款</button>
        </form>

    </div>


    <script>
        function setAmount(value) {
            // 設定自訂金額輸入框（如果你有的話）
            const input = document.querySelector('#customAmount');
            if (input) input.value = value;

            // 清除所有按鈕的 .selected
            document.querySelectorAll('.amount-options button').forEach(btn => {
                btn.classList.remove('selected');
            });

            // 將被點擊的按鈕加上 .selected
            const buttons = document.querySelectorAll('.amount-options button');
            buttons.forEach(btn => {
                if (btn.textContent.trim() == value) {
                    btn.classList.add('selected');
                }
            });
        }
    </script>

    <script>
        const cardInput = document.getElementById('cardNumber');

        cardInput.addEventListener('input', function (e) {
            let value = e.target.value;
            // 移除所有非數字
            value = value.replace(/\D/g, '');
            // 分成4碼一組
            value = value.replace(/(.{4})/g, '$1 ').trim();
            e.target.value = value;
        });

        const expirationInput = document.getElementById('expirationDate');

        expirationInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, ''); // 移除非數字
            if (value.length >= 3) {
                value = value.slice(0, 2) + '/' + value.slice(2, 4);
            }
            e.target.value = value;

        });
    </script>





</body>

</html>