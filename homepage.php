<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>孵仁</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link rel="stylesheet" href="css/homepage.css">
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

    <!-- Banner -->
    <div class="banner">
        <div class="banner1">
            <div class="hot_propose">
                <div class="chicken">
                    <img src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180">
                </div>
            </div>
            <div class="new_propose">
                <div class="chicken">
                    <img src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180">
                </div>
            </div>
        </div>
        <div class="banner2">
            <div class="chicken">
                <img src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180">
            </div>
        </div>
        <div class="banner3">
            <div class="hot_fund">
                <div class="chicken">
                    <img src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180">
                </div>
            </div>
            <div class="new_fund">
                <div class="chicken">
                    <img src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180">
                </div>

            </div>

        </div>
    </div>

    <!--理念--->

    <div class="philosophy">
        <div class="p_idea">讓你的建言破殼而出，孵化校園新未來！</div>
    </div>
    <div class="container">
        <!--建言查看區--->
        <div class="seek_advice_area">
            <div class="seek_advice">查看建言</div>
            <div class="hot-area">
                <div class="advice_classifedtitle">
                    <div class="title-bar">
                        <div class="hot_tag">HOT</div>
                        <div class="right-controls">
                            <div class="swiper-button-prev-1"><i class="fa-solid fa-arrow-left"
                                    style="color: #a5a0a0;"></i></div>
                            <div class="swiper-button-next-1"><i class="fa-solid fa-arrow-right"
                                    style="color: #a5a0a0;"></i></div>
                            <div class="more"><a href="">更多</a></div>
                        </div>
                    </div>
                    <div class="swiper mySwiper1">
                        <div class="swiper-wrapper">

                            <!-- 模擬 8 筆資料，每個都是 swiper-slide -->

                            <!-- Slide 1 -->
                            <div class="swiper-slide">
                                <div class="adv_content_block">
                                    <div class="adv_content_img">
                                        <img
                                            src="https://img.kpopdata.com/upload/content/216/231/22416704092d26793206.jpg" />
                                    </div>
                                    <div class="adv_content_goal">
                                        <div class="adv_content_text">操場的圍欄破了，會危險</div>
                                        <div class="progress_line">
                                            <progress value="50" max="100"></progress>
                                            <div class="progress_percent">50%</div>
                                            <i class="fa-regular fa-user">25</i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 3 -->
                            <div class="swiper-slide">
                                <div class="adv_content_block">
                                    <div class="adv_content_img">
                                        <img
                                            src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180" />
                                    </div>
                                    <div class="adv_content_goal">
                                        <div class="adv_content_text">圖書館冷氣太強了</div>
                                        <div class="progress_line">
                                            <progress value="70" max="100"></progress>
                                            <div class="progress_percent">70%</div>
                                            <i class="fa-regular fa-user">35</i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 4 -->
                            <div class="swiper-slide">
                                <div class="adv_content_block">
                                    <div class="adv_content_img">
                                        <img
                                            src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180" />
                                    </div>
                                    <div class="adv_content_goal">
                                        <div class="adv_content_text">希望週五可以加開夜間課程</div>
                                        <div class="progress_line">
                                            <progress value="40" max="100"></progress>
                                            <div class="progress_percent">40%</div>
                                            <i class="fa-regular fa-user">20</i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 5 -->
                            <div class="swiper-slide">
                                <div class="adv_content_block">
                                    <div class="adv_content_img">
                                        <img
                                            src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180" />
                                    </div>
                                    <div class="adv_content_goal">
                                        <div class="adv_content_text">福利社飲料機經常壞掉</div>
                                        <div class="progress_line">
                                            <progress value="30" max="100"></progress>
                                            <div class="progress_percent">30%</div>
                                            <i class="fa-regular fa-user">15</i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 6 -->
                            <div class="swiper-slide">
                                <div class="adv_content_block">
                                    <div class="adv_content_img">
                                        <img
                                            src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180" />
                                    </div>
                                    <div class="adv_content_goal">
                                        <div class="adv_content_text">教室椅子有的壞掉了</div>
                                        <div class="progress_line">
                                            <progress value="60" max="100"></progress>
                                            <div class="progress_percent">60%</div>
                                            <i class="fa-regular fa-user">30</i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 7 -->
                            <div class="swiper-slide">
                                <div class="adv_content_block">
                                    <div class="adv_content_img">
                                        <img
                                            src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180" />
                                    </div>
                                    <div class="adv_content_goal">
                                        <div class="adv_content_text">建議設置更多回收桶</div>
                                        <div class="progress_line">
                                            <progress value="80" max="100"></progress>
                                            <div class="progress_percent">80%</div>
                                            <i class="fa-regular fa-user">40</i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 8 -->
                            <div class="swiper-slide">
                                <div class="adv_content_block">
                                    <div class="adv_content_img">
                                        <img
                                            src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180" />
                                    </div>
                                    <div class="adv_content_goal">
                                        <div class="adv_content_text">學生餐廳應增加蔬食選項</div>
                                        <div class="progress_line">
                                            <progress value="35" max="100"></progress>
                                            <div class="progress_percent">35%</div>
                                            <i class="fa-regular fa-user">18</i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 9 -->
                            <div class="swiper-slide">
                                <div class="adv_content_block">
                                    <div class="adv_content_img">
                                        <img
                                            src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180" />
                                    </div>
                                    <div class="adv_content_goal">
                                        <div class="adv_content_text">校門口機車亂停，建議規劃停車格</div>
                                        <div class="progress_line">
                                            <progress value="90" max="100"></progress>
                                            <div class="progress_percent">90%</div>
                                            <i class="fa-regular fa-user">45</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Swiper container -->
            </div>

            <!--new建言查看��--->
            <div class="new-area">
                <div class="advice_classifedtitle">
                    <div class="title-bar">
                        <div class="new_tag">NEW</div>
                        <div class="right-controls">
                            <div class="swiper-button-prev-2"><i class="fa-solid fa-arrow-left"
                                    style="color: #a5a0a0;"></i></div>
                            <div class="swiper-button-next-2"><i class="fa-solid fa-arrow-right"
                                    style="color: #a5a0a0;"></i></div>
                            <div class="more"><a href="">更多</a></div>
                        </div>
                    </div>
                    <div class="swiper mySwiper2">
                        <div class="swiper-wrapper">

                            <!-- 模擬 8 筆資料，每個都是 swiper-slide -->

                            <!-- Slide 2 -->
                            <div class="swiper-slide">
                                <div class="adv_content_block">
                                    <div class="adv_content_img">
                                        <img
                                            src="https://img.kpopdata.com/upload/content/216/231/22416704092d26793206.jpg" />
                                    </div>
                                    <div class="adv_content_goal">
                                        <div class="adv_content_text">操場的圍欄破了，會危險</div>
                                        <div class="progress_line">
                                            <progress value="50" max="100"></progress>
                                            <div class="progress_percent">50%</div>
                                            <i class="fa-regular fa-user">25</i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 3 -->
                            <div class="swiper-slide">
                                <div class="adv_content_block">
                                    <div class="adv_content_img">
                                        <img
                                            src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180" />
                                    </div>
                                    <div class="adv_content_goal">
                                        <div class="adv_content_text">圖書館冷氣太強了</div>
                                        <div class="progress_line">
                                            <progress value="70" max="100"></progress>
                                            <div class="progress_percent">70%</div>
                                            <i class="fa-regular fa-user">35</i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 4 -->
                            <div class="swiper-slide">
                                <div class="adv_content_block">
                                    <div class="adv_content_img">
                                        <img
                                            src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180" />
                                    </div>
                                    <div class="adv_content_goal">
                                        <div class="adv_content_text">希望週五可以加開夜間課程</div>
                                        <div class="progress_line">
                                            <progress value="40" max="100"></progress>
                                            <div class="progress_percent">40%</div>
                                            <i class="fa-regular fa-user">20</i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 5 -->
                            <div class="swiper-slide">
                                <div class="adv_content_block">
                                    <div class="adv_content_img">
                                        <img
                                            src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180" />
                                    </div>
                                    <div class="adv_content_goal">
                                        <div class="adv_content_text">福利社飲料機經常壞掉</div>
                                        <div class="progress_line">
                                            <progress value="30" max="100"></progress>
                                            <div class="progress_percent">30%</div>
                                            <i class="fa-regular fa-user">15</i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 6 -->
                            <div class="swiper-slide">
                                <div class="adv_content_block">
                                    <div class="adv_content_img">
                                        <img
                                            src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180" />
                                    </div>
                                    <div class="adv_content_goal">
                                        <div class="adv_content_text">教室椅子有的壞掉了</div>
                                        <div class="progress_line">
                                            <progress value="60" max="100"></progress>
                                            <div class="progress_percent">60%</div>
                                            <i class="fa-regular fa-user">30</i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 7 -->
                            <div class="swiper-slide">
                                <div class="adv_content_block">
                                    <div class="adv_content_img">
                                        <img
                                            src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180" />
                                    </div>
                                    <div class="adv_content_goal">
                                        <div class="adv_content_text">建議設置更多回收桶</div>
                                        <div class="progress_line">
                                            <progress value="80" max="100"></progress>
                                            <div class="progress_percent">80%</div>
                                            <i class="fa-regular fa-user">40</i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 8 -->
                            <div class="swiper-slide">
                                <div class="adv_content_block">
                                    <div class="adv_content_img">
                                        <img
                                            src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180" />
                                    </div>
                                    <div class="adv_content_goal">
                                        <div class="adv_content_text">學生餐廳應增加蔬食選項</div>
                                        <div class="progress_line">
                                            <progress value="35" max="100"></progress>
                                            <div class="progress_percent">35%</div>
                                            <i class="fa-regular fa-user">18</i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 9 -->
                            <div class="swiper-slide">
                                <div class="adv_content_block">
                                    <div class="adv_content_img">
                                        <img
                                            src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180" />
                                    </div>
                                    <div class="adv_content_goal">
                                        <div class="adv_content_text">校門口機車亂停，建議規劃停車格</div>
                                        <div class="progress_line">
                                            <progress value="90" max="100"></progress>
                                            <div class="progress_percent">90%</div>
                                            <i class="fa-regular fa-user">45</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Swiper container -->
            </div>
        </div>

        <!--募資進度查看區--->
        <!--還沒做完--->

        <div class="seek_fund">募資專區</div>
        <div class="seek_fund_area">
            <div class="fund_area">
                <div class="hot_fund">HOT</div>
                <div>
                    <div class="right-controls">
                        <div class="swiper-button-prev-3"><i class="fa-solid fa-arrow-left" style="color: #a5a0a0;"></i></div>
                        <div class="swiper-button-next-3"><i class="fa-solid fa-arrow-right" style="color: #a5a0a0;"></i></div>
                        <div class="more"><a href="">更多</a></div>
                    </div>
                </div>
                <div class="swiper mySwiper3">
                    <div class="swiper-wrapper">
                        <ol class="swiper-slide">
                            <li class="fund_content_block">
                                <div class="adv_content_block">
                                    <div class="adv_content_img">
                                        <img
                                            src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180" />
                                    </div>
                                    <div class="adv_content_goal">
                                        <div class="adv_content_text">校門口機車亂停，建議規劃停車格</div>
                                        <div class="progress_line">
                                            <progress value="90" max="100"></progress>
                                            <div class="progress_percent">90%</div>
                                            <i class="fa-regular fa-user">45</i>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="fund_content_block">
                                <div class="adv_content_block">
                                    <div class="adv_content_img">
                                        <img
                                            src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180" />
                                    </div>
                                    <div class="adv_content_goal">
                                        <div class="adv_content_text">校門口機車亂停，建議規劃停車格</div>
                                        <div class="progress_line">
                                            <progress value="90" max="100"></progress>
                                            <div class="progress_percent">90%</div>
                                            <i class="fa-regular fa-user">45</i>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="fund_content_block">
                                <div class="adv_content_block">
                                    <div class="adv_content_img">
                                        <img
                                            src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180" />
                                    </div>
                                    <div class="adv_content_goal">
                                        <div class="adv_content_text">校門口機車亂停，建議規劃停車格</div>
                                        <div class="progress_line">
                                            <progress value="90" max="100"></progress>
                                            <div class="progress_percent">90%</div>
                                            <i class="fa-regular fa-user">45</i>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="fund_content_block">
                                <div class="adv_content_block">
                                    <div class="adv_content_img">
                                        <img
                                            src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180" />
                                    </div>
                                    <div class="adv_content_goal">
                                        <div class="adv_content_text">校門口機車亂停，建議規劃停車格</div>
                                        <div class="progress_line">
                                            <progress value="90" max="100"></progress>
                                            <div class="progress_percent">90%</div>
                                            <i class="fa-regular fa-user">45</i>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="fund_content_block">
                                <div class="adv_content_block">
                                    <div class="adv_content_img">
                                        <img
                                            src="https://tse3.mm.bing.net/th?id=OIP.4rzwr9GyOUhE1mh2oS5oegHaGa&pid=Api&P=0&h=180" />
                                    </div>
                                    <div class="adv_content_goal">
                                        <div class="adv_content_text">校門口機車亂停，建議規劃停車格</div>
                                        <div class="progress_line">
                                            <progress value="90" max="100"></progress>
                                            <div class="progress_percent">90%</div>
                                            <i class="fa-regular fa-user">45</i>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ol>
                        <div class="swiper-slide">
                            <ol>
                                <li class="fund_content_block">卡片6</li>
                                <li class="fund_content_block">卡片7</li>
                                <li class="fund_content_block">卡片8</li>
                                <li class="fund_content_block">卡片9</li>
                                <li class="fund_content_block">卡片10</li>
                            </ol>
                        </div>
                        <div class="swiper-slide">
                            <ol>
                                <li class="fund_content_block">卡片11</li>
                                <li class="fund_content_block">卡片12</li>
                                <li class="fund_content_block">卡片13</li>
                                <li class="fund_content_block">卡片14</li>
                                <li class="fund_content_block">卡片15</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Swiper container -->
    </div>

    <footer>

    </footer>


    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- 初始化 Swiper -->
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

        /*CARD SLIDER*/
        const swiperConfigs = [{
                container: ".mySwiper1",
                next: ".swiper-button-next-1",
                prev: ".swiper-button-prev-1"
            },
            {
                container: ".mySwiper2",
                next: ".swiper-button-next-2",
                prev: ".swiper-button-prev-2"
            },
        ];

        swiperConfigs.forEach(config => {
            new Swiper(config.container, {
                slidesPerView: 'auto', // 用 auto 配合固定寬
                spaceBetween: 24,
                loop: true,
                speed: 900,
                navigation: {
                    nextEl: config.next,
                    prevEl: config.prev
                },
                slidesPerGroupAuto: true, // 自動根據可視區的卡片數滑動

                breakpoints: {
                    768: {
                        slidesPerView: 4,
                        slidesPerGroup: 4
                    },
                    480: {
                        slidesPerView: 2,
                        slidesPerGroup: 2
                    },
                    240: {
                        slidesPerView: 1,
                        slidesPerGroup: 1
                    }
                }
            });
        });
        /*募資區的控制按鈕*/
        const swiper3 = new Swiper(".mySwiper3", {
            slidesPerView: 1,
            spaceBetween: 30,
            speed: 900,
            navigation: {
                nextEl: ".swiper-button-next-3",
                prevEl: ".swiper-button-prev-3",
            },
        });
    </script>


</body>


<!--CDN link-->

</html>