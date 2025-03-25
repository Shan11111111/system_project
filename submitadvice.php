<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>提交建言</title>
    <link rel="stylesheet" href="css\submitadvice.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <!-- 搜尋框 -->
            <input type="text" class="search-bar" placeholder="搜尋...">

            <!-- 建言 Dropdown -->
            <div class="dropdown nav-left">
                <button class="dropbtn">建言</button>
                <div class="dropdown-content">
                    <a href="#">最新建言</a>
                    <a href="#">熱門建言</a>
                </div>
            </div>

            <!-- LOGO -->
            <div class="logo">LOGO</div>

            <!-- 募資 Dropdown -->
            <div class="dropdown nav-right">
                <button class="dropbtn">募資</button>
                <div class="dropdown-content">
                    <a href="#">進行中計畫</a>
                    <a href="#">成功案例</a>
                </div>
            </div>

            <!-- 登入 & 註冊 -->
            <div class="auth-links">
                <a href="#" class="nav-item">登入</a>
                <a href="#" class="nav-item">註冊</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <!-- 左側標題與圖片 -->
        <div class="left-panel">
            <h1 class="main-title">提出建言</h1>
            <p class="subtitle">提出建言，讓校園更好</p>
            <div class="image-placeholder"><img src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180"></div>
        </div>

        <!-- 右側表單 -->
        <div class="right-panel">
            <form action="#" method="POST" >
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
        document.addEventListener("DOMContentLoaded", function() {
    const categoryButtons = document.querySelectorAll(".category");
    const selectedCategoryInput = document.getElementById("selected-category");
    const uploadBox = document.getElementById("uploadBox");
    const fileInput = document.getElementById("fileInput");

    // 分類按鈕選擇
    categoryButtons.forEach(button => {
        button.addEventListener("click", function() {
            categoryButtons.forEach(btn => btn.classList.remove("selected"));
            this.classList.add("selected");
            selectedCategoryInput.value = this.getAttribute("data-value");
        });
    });

    // 點擊上傳按鈕
    uploadBox.addEventListener("click", function() {
        fileInput.click();
    });

    // 顯示選擇的文件名稱
    fileInput.addEventListener("change", function() {
        if (fileInput.files.length > 0) {
            uploadBox.textContent = fileInput.files[0].name;
        }
    });
});

    </script>
</body>
</html>
