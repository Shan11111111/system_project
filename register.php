<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>孵仁</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link rel="stylesheet" href="css/register.css">
    <!-- Swiper -->
    <link rel="stylesheet" href="https://unpkg.com/swiper@11/swiper-bundle.min.css">

    <!-- cdn link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


</head>

<body>
    <div class="container1">
        <!-- 左側註冊表單 -->
        <div class="login-section">
            <div class="login-content">
                <div class="top-bar">
                    <button class="back-btn" onclick="history.back()">← Back</button>
                    
                </div>

                <h2>註冊</h2>
                <form>
                    <!-- 身分 + 學院 選擇 -->
                    <div class="form-row">
                        <div class="select-wrapper">
                            <select name="role" required>
                                <option value="">請選擇身分</option>
                                <option value="學生">學生</option>
                                <option value="教職員">教職員</option>
                            </select>
                        </div>

                        <div class="select-wrapper">
                            <select name="department" required>
                                <option value="">請選擇學院</option>
                                <option value="文學院">文學院</option>
                                <option value="理學院">理學院</option>
                                <option value="工學院">工學院</option>
                                <option value="管理學院">管理學院</option>
                                <option value="其他">其他</option>
                            </select>
                        </div>
                    </div>

                    <input type="text" placeholder="學號" required />
                    <input type="email" placeholder="Email" required />
                    <input type="text" placeholder="暱稱" required />
                    <input type="password" placeholder="密碼" required />
                    <input type="password" placeholder="確認密碼" required />

                    <button class="sign-in-btn" type="submit"><a href="login.php">註冊</a></button>
                </form>
            </div>
        </div>

        <!-- 右側導引區塊 -->
        <div class="signup-section">
            <h2>歡迎回來!</h2>
            <p>已經有帳號了嗎</p>
            <button class="signup-btn" ><a href="login.php">登入</a></button>
        </div>
    </div>
</body>

</html>