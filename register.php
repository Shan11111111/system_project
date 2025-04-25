<?php
session_start();
?>
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
                    <button class="back-btn" >
                        <a href="homepage.php">← Back</a> </button>
                        <div class="logo"><img src="img\logo.png" style="width: 90px;"></div>
                </div>

                <h2>註冊</h2>
                <form id="register-form" action="register_in.php" method="POST">
                    <input type="hidden" name="method" value="register">
                    <!-- 身分 + 學院 選擇 -->
                    <div class="form-row">
                        <div class="select-wrapper">
                            <select name="level" required>
                                <option value="" disabled selected hidden>請選擇身分</option>
                                <option value="student">學生</option>
                                <option value="teacher">教職員</option>
                            </select>
                        </div>

                        <div class="select-wrapper">
                            <select name="department" required>
                                <option value="" disabled selected hidden>請選擇學院</option>
                                <option value="文學院">文學院</option>
                                <option value="藝術學院">藝術學院</option>
                                <option value="外國語文學院">外國語文學院</option>
                                <option value="理工學院">理工學院</option>
                                <option value="管理學院">管理學院</option>
                                <option value="社會科學院">社會科學院</option>
                                <option value="法律學院">法律學院</option>
                                <option value="醫學院">醫學院</option>
                                <option value="民生學院">民生學院</option>
                                <option value="傳播學院">傳播學院</option>
                                <option value="教育學院">教育學院</option>
                                <option value="織品服裝學院織品服裝學院">織品服裝學院織品服裝學院</option>
                            </select>
                        </div>
                    </div>

                    <input type="int" name="user_id" placeholder="學號/教職員編號" required />
                    <input type="email" name="email" placeholder="Email" required />
                    <input type="text" name="name" placeholder="暱稱" required />
                    <input type="password" id="password" name="password" placeholder="密碼" required />
                    <input type="password" id="confirm-password" placeholder="確認密碼" required />

                    <button class="sign-in-btn" type="submit">註冊</button>
                </form>
            </div>
        </div>

        <!-- 右側導引區塊 -->
        <div class="signup-section">
            <h2>歡迎回來!</h2>
            <p>已經有帳號了嗎</p>
            <a href="login.php"><button class="signup-btn" style="color: #7c4d2b;">登入</button></a>
        </div>
    </div>

    <script>
        // 表單提交時進行密碼驗證
        document.getElementById('register-form').addEventListener('submit', function (event) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (password !== confirmPassword) {
                event.preventDefault(); // 阻止表單提交
                alert('密碼與確認密碼不相符，請重新輸入！');
            }
        });
    </script>
</body>

</html>