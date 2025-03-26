<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>孵仁</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link rel="stylesheet" href="css/advice_search.css">
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




    <div class="container">
        <!-- 快要達標 -->
        <div class="highlight">
            <div class="highlight_content">快要達標的建言</div>
            <div class="highlight_btn">去覆議</div>
        </div>
        <div class="highlight_title"><center><p>快要達標的建言，還剩php人</p></center></div>
        <div class="advice_space">
            <!-- Tabs -->
            <div class="tabs">
                <div class="tab active" onclick="switchTab('active')">進行中</div>
                <div class="tab" onclick="switchTab('ended')">已結束</div>
            </div>
            <hr style="width=70%; border-color:black;">

            <!-- 選單 + 搜尋 -->
            <div class="filter-bar">
                <div class="search_text">
                    <select id="category">
                        <option value="all">全部分類</option>
                        <option value="equipment">設施改善</option>
                        <option value="academic">學術發展</option>
                        <option value="environment">社團活動</option>
                        <option value="welfare">公益關懷</option>
                        <option value="environment">環保永續</option>
                        <option value="other">其他</option>
                    </select>
                    <input type="text" id="search" placeholder="請輸入關鍵字">
                    <button onclick="search()"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <div class="search_sort">
                    <!--按鈕按下去後箭頭改變 牽涉到後端 所以留給你們 這裡有倒敘正敘的箭頭icon-->
                    <button onclick="toggleArrow(this)">HOT<i class="fa-solid fa-caret-up"></i></button>
                    <button onclick="toggleArrow(this)">NEW<i class="fa-solid fa-caret-up"></i></button>
                </div>
            </div>

            <!-- 建言列表 -->
            <div id="suggestion-list"></div>

            <!-- 分頁 -->
            <div class="pagination" id="pagination"></div>
        </div>
    </div>
    <div class="footer">footer</div>

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

        const data = Array.from({ length: 25 }, (_, i) => ({
            title: `建言標題 ${i + 1}`,
            comments: Math.floor(Math.random() * 80),
            deadline: '剩約天',
            status: i % 2 === 0 ? 'active' : 'ended',
            passed: i % 3 === 0, // 每三個通過一次
            publishDate: 'date'
        }));

        let currentTab = 'active';
        let currentPage = 1;
        const itemsPerPage = 10;

        function switchTab(tab) {
            currentTab = tab;
            currentPage = 1;
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab')[tab === 'active' ? 0 : 1].classList.add('active');
            renderSuggestions();
        }

        function renderSuggestions() {
            const list = document.getElementById('suggestion-list');
            list.innerHTML = '';
            const filtered = data.filter(item => item.status === currentTab);
            const paginated = filtered.slice((currentPage - 1) * itemsPerPage, currentPage * itemsPerPage);

            paginated.forEach(item => {
                const div = document.createElement('div');
                div.className = 'suggestion';

                if (currentTab === 'ended') {
                    div.innerHTML = `
            <img src="https://placekitten.com/300/169" alt="建言圖">
            <div class="suggestion-content">
              <div class="suggestion-title">${item.title}</div>
              <div class="suggestion-meta">
                <span class="suggestion-status ${item.passed ? 'status-passed' : 'status-failed'}">
                  ${item.passed ? '通過' : '未通過'}
                </span>
                <span>發布日：${item.publishDate}</span>
              </div>
            </div>
          `;
                } else {
                    div.innerHTML = `
            <img src="https://placekitten.com/300/169" alt="建言圖">
            <div class="suggestion-content">
              <div class="suggestion-title">${item.title}</div>
              <div class="suggestion-meta">
              <div class="data">
                <span>附議數：${item.comments}</span>
                <span><i class="fa-solid fa-comment"></i>：${Math.floor(item.comments / 2)}</span>
                </div>
                
                <div class="date">
                <i class="fa-solid fa-clock"></i>
                <span>${item.deadline}</span>
                <span>發布日：${item.publishDate}</span>
                </div>
              </div>
            </div>
          `;
                }

                list.appendChild(div);
            });

            renderPagination(filtered.length);
        }

        function renderPagination(totalItems) {
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            if (currentPage > 1) {
                const prev = document.createElement('button');
                prev.textContent = '上一頁';
                prev.onclick = () => { currentPage--; renderSuggestions(); };
                pagination.appendChild(prev);
            }

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                if (i === currentPage) btn.disabled = true;
                btn.onclick = () => { currentPage = i; renderSuggestions(); };
                pagination.appendChild(btn);
            }

            if (currentPage < totalPages) {
                const next = document.createElement('button');
                next.textContent = '下一頁';
                next.onclick = () => { currentPage++; renderSuggestions(); };
                pagination.appendChild(next);
            }
        }

        function search() {
            alert("沒做");
        }
        
        function toggleArrow(btn) {
            const icon = btn.querySelector("i");
            icon.classList.toggle("fa-caret-up");
            icon.classList.toggle("fa-caret-down");
        }


        renderSuggestions();
    </script>
</body>

</html>