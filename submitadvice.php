<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>提交建言</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="css/submit_advice.css">
    <!-- cdn link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <?php session_start(); ?>
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
                <a href="ongoing_funding_search.php">進行中募資</a>
                <a href="due_funding_search.php">已結束募資</a>
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


    
    <div class="container1">
        <!-- 左側標題與圖片 -->
        <div class="left-panel">
            <h1 class="main-title">提出建言</h1>
            <p class="subtitle">提出建言，讓校園更好</p>
            <!--<div class="image-placeholder"><img src="#"></div>-->
        </div>

        <!-- 右側表單 -->
        <div class="right-panel">

            <form action="advice_accept.php" method="post" enctype="multipart/form-data">

                <!-- 標題輸入 -->
                <label for="title">標題</label>
                <input type="text" id="title" name="advice_title" class="inpuu" required>

                <!-- 分類按鈕 -->
                <label>分類</label>
                <div class="category-buttons">
                    <button type="button" class="category" data-value="equipment">設施改善</button>
                    <button type="button" class="category" data-value="academic">學術發展</button>
                    <button type="button" class="category" data-value="welfare">公益活動</button>
                    <button type="button" class="category" data-value="environment">環保永續</button>
                    <button type="button" class="category" data-value="club">社團活動</button>
                    <button type="button" class="category" data-value="other">其他</button>
                </div>
                <input type="hidden" name="category" id="selected-category" required>

                <!-- 內容輸入 -->
                <label for="content">內容</label>
                <textarea id="content" name="advice_content" class="inpuu" required></textarea>

                <!-- 上傳按鈕 -->
                <label>檔案 / 照片上傳</label>

                <!-- 照片上傳 -->
                <button type="button" class="upload-btn" id="photoUploadBox">照片上傳</button>
                <input type="file" id="photoFileInput" name="file" accept="image/*" hidden>

                <!-- 文件上傳 -->
                <button type="button" class="upload-btn" id="fileUploadBox">文件上傳</button>
                <input type="file" id="fileFileInput" name="file2" accept=".pdf, .doc, .docx, .ppt, .pptx" hidden>

                <!-- 提交按鈕 -->
                <button type="submit" class="submit">提交</button>
            </form>

        </div>
    </div>
    <script>
        function toggleMobileMenu() {
            var mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('active');
        }

        // 手機 dropdown 點擊展開
        document.querySelectorAll('.mobile-menu .dropdown .dropbtn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault(); // 防止跳頁
                const parent = btn.parentElement;
                parent.classList.toggle('active');
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const categoryButtons = document.querySelectorAll(".category");
            const selectedCategoryInput = document.getElementById("selected-category");

            // 分類按鈕選擇
            categoryButtons.forEach(button => {
                button.addEventListener("click", function () {
                    categoryButtons.forEach(btn => btn.classList.remove("selected"));
                    this.classList.add("selected");
                    selectedCategoryInput.value = this.getAttribute("data-value");
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const photoUploadBox = document.getElementById("photoUploadBox");
            const photoFileInput = document.getElementById("photoFileInput");
            const fileUploadBox = document.getElementById("fileUploadBox");
            const fileFileInput = document.getElementById("fileFileInput");

            // 點擊照片上傳按鈕
            photoUploadBox.addEventListener("click", function () {
                photoFileInput.click();
            });

            // 顯示選擇的照片名稱
            photoFileInput.addEventListener("change", function () {
                if (photoFileInput.files.length > 0) {
                    photoUploadBox.textContent = photoFileInput.files[0].name;
                }
            });

            // 點擊文件上傳按鈕
            fileUploadBox.addEventListener("click", function () {
                fileFileInput.click();
            });

            // 顯示選擇的文件名稱
            fileFileInput.addEventListener("change", function () {
                if (fileFileInput.files.length > 0) {
                    fileUploadBox.textContent = fileFileInput.files[0].name;
                }
            });
        });
    </script>
</body>

</html>