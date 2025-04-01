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


</head>

<body>

    <!--navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <!-- LOGO -->
            <div class="logo">
                    <img src="img/logo.png" style="width: 90px;">
                </div>
            <!-- 漢堡按鈕 -->
            <div class="menu-toggle" id="mobile-menu-toggle">☰</div>

            <!-- 桌面版選單 -->
            <div class="nav-center desktop-menu">
                <div class="dropdown">
                    <button class="dropbtn">建言</button>
                    <div class="dropdown-content">
                        <a href="submitadvice.php">發布建言</a>
                        <a href="advice_search.php">最新建言</a><!--之後要設(不知道是前端還後端)-->
                        <a href="advice_search.php">熱門建言</a>
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
                <a href="login.php" class="nav-item">登入</a>
                <a href="register.php" class="nav-item">註冊</a>
            </div>
        </div>

        <!-- 手機版選單 -->
        <div class="mobile-menu" id="mobile-menu">
            <div class="dropdown">
                <button class="dropbtn">建言</button>
                <div class="dropdown-content">
                    <a href="submitadvice.php">發布建言</a>
                    <a href="advice_search.php">最新建言</a><!--之後要設(不知道是前端還後端)-->
                    <a href="advice_search.php">熱門建言</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="dropbtn">募資</button>
                <div class="dropdown-content">
                    <a href="#">進行中計畫</a>
                    <a href="#">成功案例</a>
                </div>
            </div>
            <a href="login.php" class="nav-item">登入</a>
            <a href="register.php" class="nav-item">註冊</a>
        </div>
    </nav>

    <div class="container">
        <main class="suggestion-detail">
            <!-- 標題 -->
            <h1 class="title" id="advice-title">建言標題</h1>
            <span id="suggestion-status" class="suggestion-status status-pending">進行中</span>

            <!-- 進度條區域 -->
            <section class="progress-section">
                <div class="dates">
                    <span id="announce-date">發布日：2025/01/01</span>
                    <span id="deadline-date">截止日：2025/02/01</span>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar">
                        <div class="progress" style="width: 65%"></div>
                    </div>
                    <div class="progress-info">
                        目前 1234 人 / 還差 766 人
                        <span class="percent">65%</span>
                    </div>
                </div>
            </section>
            <div class="advice">
                <!-- 發布人與分類 -->
                <section class="meta">
                    <p id="advice-author">發布人：?</p>
                    <p id="advice-category">分類：學術發展</p>
                </section>

                <!-- 圖片或 PDF -->
                <section class="media">
                    <img id="advice-image"
                        src="https://afpbb.ismcdn.jp/mwimgs/1/4/810mw/img_1409ea76cc56c3d005d7abda3c4e67e288902.jpg"
                        alt="建言圖片" />
                    <a id="advice-pdf-link" class="pdf-link" href="file.pdf" target="_blank">查看 PDF</a>
                </section>

                <!-- 內文 -->
                <section class="content">
                    <p id="advice-content">這裡是建言內文...呵呵</p>
                </section>
            </div>
            <hr style="width=70%; border-color:black;">

            <section class="comments">
                <div class="comment-header">
                    <h4>留言區</h4>
                    <select id="sort-comments">
                        <option value="latest">留言時間：最新</option>
                        <option value="oldest">留言時間：最舊</option>
                    </select>
                </div>

                <div class="comment-input">
                    <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
                    <textarea id="comment-text" placeholder="我要留言..."></textarea>
                    <button id="submit-comment"><i class="fa-solid fa-paper-plane"></i></button>
                </div>

                <ul class="comment-list"></ul>


                <div class="pagination">
                    <button id="prev-page">上一頁</button>
                    <span id="page-indicator"></span>
                    <button id="next-page">下一頁</button>
                </div>
            </section>

        </main>
    </div>


    <!-- Fixed 按鈕 -->
    <div class="fixed-buttons">
        <button class="back-btn" onclick="history.back()">上一頁 </button>
        <a href="#" class="reply-btn agree-btn" id="agree-btn" data-advice-id="">附議</a>


        <a href="#top" class="top-btn">Top</a>
    </div>

    <footer class="footer"> footer</footer>

    <script>


        const statusEl = document.getElementById('suggestion-status');
        const status = 'pending'; // 假資料，可改為 'passed' 或 'failed'

        const statusMap = {
            passed: { text: '通過', class: 'status-passed' },
            failed: { text: '未通過', class: 'status-failed' },
            pending: { text: '進行中', class: 'status-pending' }
        };

        if (statusMap[status]) {
            statusEl.textContent = statusMap[status].text;
            statusEl.className = `suggestion-status ${statusMap[status].class}`;
        }


        const commentList = document.querySelector('.comment-list');
        const pageIndicator = document.getElementById('page-indicator');
        const prevBtn = document.getElementById('prev-page');
        const nextBtn = document.getElementById('next-page');
        const sortSelect = document.getElementById('sort-comments');
        const submitBtn = document.getElementById('submit-comment');
        const textarea = document.getElementById('comment-text');

        // ✨ 假資料：加上 username
        let allComments = Array.from({ length: 30 }, (_, i) => ({
            username: `使用者${i + 1}`,
            text: `這是留言 #${i + 1}`,
            time: new Date(2025, 2, 29, 12, i).toLocaleString(),
        }));

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

        submitBtn.addEventListener('click', () => {
            const text = textarea.value.trim();
            if (text) {
                const now = new Date().toLocaleString();
                allComments.push({
                    username: '我自己', //  未來從登入使用者資料取得
                    text,
                    time: now
                });
                textarea.value = '';
                currentSort = 'latest';
                currentPage = 1;
                sortSelect.value = 'latest';
                renderComments();
            }
        });


        renderComments();



        const urlParams = new URLSearchParams(window.location.search);
        const adviceId = urlParams.get('id');

        // 確保在 API 請求中傳遞 id 參數
        fetch(`advice_pull.php?id=${adviceId}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    const advice = data[0]; // 假設只返回一條資料
                    // 更新建言標題
                    document.getElementById('advice-title').textContent = advice.advice_title;
                    // 更新發布人
                    document.getElementById('advice-author').textContent = `發布人：${advice.user_id}`;
                    // 更新建言分類
                    document.getElementById('advice-category').textContent = `分類：${advice.category}`;
                    // 更新建言內文
                    document.getElementById('advice-content').textContent = advice.advice_content;
                    // 更新發布日與截止日
                    document.getElementById('announce-date').textContent = `發布日：${advice.announce_date}`;
                    document.getElementById('deadline-date').textContent = `截止日：${advice.deadline_date}`; // 假設有 deadline_date 欄位

                    // 更新建言狀態
                    document.getElementById('suggestion-status').textContent =
                        advice.advice_state === '未處理' ? '未處理' :
                            (advice.advice_state === '進行中' ? '進行中' : '已結束');

                    // 如果有圖片，顯示圖片
                    if (advice.image_url) {
                        document.getElementById('advice-image').src = advice.image_url;
                    }

                    // 如果有PDF連結，顯示PDF連結
                    if (advice.pdf_url) {
                        document.getElementById('advice-pdf-link').href = advice.pdf_url;
                    }
                }
            })
            .catch(error => console.error('Error:', error));






        document.getElementById("agree-btn").addEventListener("click", function (event) {
            event.preventDefault(); // 防止超連結跳轉

            // 從網址中取得 'id' 參數
            const urlParams = new URLSearchParams(window.location.search);
            const adviceId = urlParams.get('id'); // 取得 'id' 參數

            if (!adviceId) {
                alert("無效的 advice_id！");
                return;
            }

            console.log("附議的 advice_id:", adviceId); // 測試用

            // 發送 AJAX 請求到後端
            fetch("update_agree.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `advice_id=${adviceId}` // 傳送 advice_id 到後端
            })
                .then(response => response.text())
                .then(data => {
                    alert("附議成功！");
                })
                .catch(error => console.error("錯誤:", error));
        });







    </script>


</body>

</html>