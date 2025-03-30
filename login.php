<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>孵仁</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link rel="stylesheet" href="css/login.css">
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
    <!-- 左側登入區塊 -->
    <div class="login-section">
      <div class="top-bar">
        <button class="back-btn" >
         <a href="homepage.php">← Back</a> 
        </button>
        <div class="logo"><img src="img\logo.png" style="width: 90px;"></div>
      </div>

      <div class="login-content">
        <h1>登入</h1>
        

        <form>
          <input type="email" placeholder="學號/教職員編號" required />
          <input type="password" placeholder="密碼" required />
          <button class="sign-in-btn" type="登入"><a href="homepage.css">登入</a></button>
        </form>
      </div>
    </div>

    <!-- 右側註冊區塊 -->
    <div class="signup-section">
      <h2>沒有帳號?</h2>
      <p>註冊帳號發表建言</p>
      <button class="signup-btn"><a href="register.php">註冊</a></button>
    </div>
  </div>
</body>
</html>