<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>提交建言</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="css\submitadvice.css">
    <!-- cdn link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <!-- LOGO -->
            <div class="logo">LOGO</div>

            <!-- 漢堡按鈕 -->
            <div class="menu-toggle" id="mobile-menu-toggle">☰</div>

            <!-- 桌面版選單 -->
            <div class="nav-center desktop-menu">
                <div class="dropdown">
                    <button class="dropbtn">建言</button>
                    <div class="dropdown-content">
                        <a href="submitadvice.php">發布建言</a>
                        <a href="#">最新建言</a>
                        <a href="#">熱門建言</a>
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
                <a href="#" class="nav-item">登入</a>
                <a href="#" class="nav-item">註冊</a>
            </div>
        </div>

        <!-- 手機版選單 -->
        <div class="mobile-menu" id="mobile-menu">
            <div class="dropdown">
                <button class="dropbtn">建言</button>
                <div class="dropdown-content">
                    <a href="submitadvice.php">發布建言</a>
                    <a href="#">最新建言</a>
                    <a href="#">熱門建言</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="dropbtn">募資</button>
                <div class="dropdown-content">
                    <a href="#">進行中計畫</a>
                    <a href="#">成功案例</a>
                </div>
            </div>
            <a href="#" class="nav-item">登入</a>
            <a href="#" class="nav-item">註冊</a>
        </div>
    </nav>
    <div class="container1">
        <!-- 左側標題與圖片 -->
        <div class="left-panel">
            <h1 class="main-title">提出建言</h1>
            <p class="subtitle">提出建言，讓校園更好</p>
            <div class="image-placeholder"><img
                    src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180"></div>
        </div>

        <!-- 右側表單 -->
        <div class="right-panel">
            <form action="#" method="POST">
                <!-- 標題輸入 -->
                <label for="title">標題</label>
                <input type="text" id="title" name="title" class="inpuu" required>

                <!-- 分類按鈕 -->
                <label>分類</label>
                <div class="category-buttons">
                    <button type="button" class="category" data-value="設施改善">設施改善</button>
                    <button type="button" class="category" data-value="學術發展">學術發展</button>
                    <button type="button" class="category" data-value="公益活動">公益活動</button>
                    <button type="button" class="category" data-value="環保永續">環保永續</button>
                    <button type="button" class="category" data-value="社團活動">社團活動</button>
                    <button type="button" class="category" data-value="其他">其他</button>
                </div>
                <input type="hidden" name="category" id="selected-category" required>

                <!-- 內容輸入 -->
                <label for="content">內容</label>
                <textarea id="content" name="content" class="inpuu" required></textarea>

                <!-- 上傳按鈕 -->
                <label>檔案 / 照片上傳</label>
                <button type="button" class="upload-btn" id="uploadBox">上傳</button>
                <input type="file" id="fileInput" name="file" accept="image/*" hidden>

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
                btn.addEventListener('click', function(e) {
                    e.preventDefault(); // 防止跳頁
                    const parent = btn.parentElement;
                    parent.classList.toggle('active');
                });
            });
    
        document.addEventListener("DOMContentLoaded", function () {
            const categoryButtons = document.querySelectorAll(".category");
            const selectedCategoryInput = document.getElementById("selected-category");
            const uploadBox = document.getElementById("uploadBox");
            const fileInput = document.getElementById("fileInput");

            // 分類按鈕選擇
            categoryButtons.forEach(button => {
                button.addEventListener("click", function () {
                    categoryButtons.forEach(btn => btn.classList.remove("selected"));
                    this.classList.add("selected");
                    selectedCategoryInput.value = this.getAttribute("data-value");
                });
            });

            // 點擊上傳按鈕
            uploadBox.addEventListener("click", function () {
                fileInput.click();
            });

            // 顯示選擇的文件名稱
            fileInput.addEventListener("change", function () {
                if (fileInput.files.length > 0) {
                    uploadBox.textContent = fileInput.files[0].name;
                }
            });
        });

    </script>
</body>

</html>