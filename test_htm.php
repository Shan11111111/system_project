<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .highlight {
            background-color: #fffbf2;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .highlight_btn {
            background-color: #ff9800;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        .highlight_btn:hover {
            background-color: #ff5722;
        }

        .tabs {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .tab {
            background-color: #e0e0e0;
            padding: 10px 20px;
            cursor: pointer;
            margin: 0 5px;
            border-radius: 4px;
        }

        .tab.active {
            background-color: #ff9800;
            color: white;
        }

        .suggestion {
            background-color: white;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .suggestion img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }

        .suggestion-content {
            flex: 1;
        }

        .suggestion-title {
            font-size: 18px;
            font-weight: bold;
        }

        .suggestion-meta {
            font-size: 14px;
            color: #757575;
        }

        .pagination button {
            background-color: #eeeeee;
            border: none;
            padding: 10px;
            margin: 5px;
            cursor: pointer;
            border-radius: 4px;
        }

        .pagination button:disabled {
            background-color: #dcdcdc;
            cursor: not-allowed;
        }

        .loading {
            text-align: center;
            font-size: 18px;
            color: #ff5722;
            margin: 50px 0;
        }
    </style>

</head>

<body>
    <div class="container">
        <!-- 快要達標 -->
        <div class="highlight">
            <div class="highlight_content">快要達標的建言</div>
            <div class="highlight_btn">去覆議</div>
        </div>
        <div class="highlight_title">
            <center>
                <p>快要達標的建言，還剩php人</p>
            </center>
        </div>
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
                    <select id="category" onchange="search()">
                        <option value="">全部分類</option>
                        <option value="設施改善">設施改善</option>
                        <option value="學術發展">學術發展</option>
                        <option value="社團活動">社團活動</option>
                        <option value="公益活動">公益活動</option>
                        <option value="環保永續">環保永續</option>
                        <option value="其他">其他</option>
                    </select>

                    <input type="text" id="search" placeholder="請輸入關鍵字">
                    <button onclick="search()"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <div class="search_sort">
                    <button onclick="toggleSort('hot', this)">HOT <i class="fa-solid fa-caret-down"></i></button>
                    <button onclick="toggleSort('new', this)">NEW <i class="fa-solid fa-caret-down"></i></button>
                </div>
            </div>

            <!-- 建言列表 -->
            <div id="suggestion-list"></div>


            <!-- 搜尋表單 -->
            <div class="search-container">
                <form method="GET" action="">
                    <input type="text" name="search" placeholder="搜尋公告"
                        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                    <button type="submit">搜尋</button>
                </form>
            </div>

            <?php
            // Step 1: 連接資料庫
            $link = mysqli_connect('localhost', 'root');
            mysqli_select_db($link, "system_project");

            // Step 2: 處理搜尋功能
            $search = isset($_GET['search']) ? mysqli_real_escape_string($link, $_GET['search']) : '';

            // Step 3: 查詢公告資料，根據搜尋關鍵字來篩選標題或內容
            $sql = "SELECT * FROM advice";
            if ($search) {
                $sql .= " WHERE advice_title LIKE '%$search%' OR advice_content LIKE '%$search%'";
            }
            $result = mysqli_query($link, $sql);

            // Step 4: 顯示公告
            while ($row = mysqli_fetch_assoc($result)) {
                $comment = 0;
                $remainingDays = ceil((strtotime($row['announce_date'] . ' +30 days') - time()) / 86400);
                $remainingDays = max(0, $remainingDays); // 確保不會顯示負天數
                echo '<img src="${item.images || img/homepage.png}"  alt="建言圖">
                        <div class="suggestion-content">
                            <div class="suggestion-title">' . $row['advice_title'] . '</div>
                            <div class="suggestion-meta">
                                <div class="data">
                                    <span>附議數：' . $row['agree'], '</span>
                                    <span><i class="fa-solid fa-comment"></i>：'.$comment.'</span>
                                </div>
                                <div class="date">
                                    <i class="fa-solid fa-clock"></i>
                                    <span>'.$remainingDays.'</span>
                                    <span>發布日：' . $row['announce_date'] . '</span>
                                </div>
                            </div>
                        </div>';

                // 顯示標籤
                $tags = explode(' ', $row['category']);
                foreach ($tags as $tag) {
                    if (!empty($tag)) {
                        echo '<span class="tag">' . $tag . '</span>';
                    }
                }
                
            }
            ?>
        </div>



        <!-- 分頁 -->
        <div class="pagination" id="pagination"></div>
    </div>
    </div>
    <div class="footer">footer</div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let currentTab = 'active';  // 預設顯示進行中的建議
            let currentPage = 1;       // 預設顯示第1頁
            const itemsPerPage = 10;    // 每頁顯示的項目數
            let suggestions = [];       // 存放所有建議資料

            // 模擬從後端獲取建議資料
            fetch(`advice_get.php?page=1&status=active&sort=new`)
                .then(response => response.json())
                .then(data => {
                    suggestions = data.suggestions;  // 初始化資料
                    renderSuggestions();             // 渲染建議列表
                });

            // 切換 Tab 顯示內容
            function switchTab(tab) {
                currentTab = tab;  // 更新當前選擇的狀態
                currentPage = 1;   // 切換時回到第1頁
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab')[tab === 'active' ? 0 : 1].classList.add('active');
                renderSuggestions();  // 重新渲染建議列表
            }

            // 渲染建議列表
            function renderSuggestions(filteredSuggestions = suggestions) {
                const list = document.getElementById('suggestion-list');
                list.innerHTML = ''; // 清空現有的列表

                // 根據當前頁數進行分頁
                const startIndex = (currentPage - 1) * itemsPerPage;
                const pagedSuggestions = filteredSuggestions.slice(startIndex, startIndex + itemsPerPage);

                if (pagedSuggestions.length === 0) {
                    list.innerHTML = `<div class="no-data">目前沒有資料顯示</div>`;
                } else {
                    pagedSuggestions.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'suggestion';
                        div.onclick = () => {
                            window.location.href = `advice_detail.php?id=${item.id}`;
                        };

                        // 渲染建議內容
                        div.innerHTML = `
                        <img src="${item.images || 'img/homepage.png'}"  alt="建言圖">
                        <div class="suggestion-content">
                            <div class="suggestion-title">${item.title}</div>
                            <div class="suggestion-meta">
                                <div class="data">
                                    <span>附議數：${item.agree}</span>
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
                        list.appendChild(div);
                    });
                }

                renderPagination(filteredSuggestions.length); // 渲染分頁
            }

            // 渲染分頁
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

            // 初始化排序狀態
            let currentSort = {
                hot: 'desc',  // 默認按 "HOT" 降序排列
                new: 'desc'   // 默認按 "NEW" 降序排列
            };

            // 切換排序條件
            function toggleSort(type, button) {
                currentSort[type] = (currentSort[type] === 'desc') ? 'asc' : 'desc';
                updateArrow(button, currentSort[type]);
                search();  // 呼叫搜尋以更新顯示
            }

            // 更新排序箭頭的顯示
            function updateArrow(button, direction) {
                const icon = button.querySelector('i');
                if (direction === 'asc') {
                    icon.classList.remove('fa-caret-down');
                    icon.classList.add('fa-caret-up');
                } else {
                    icon.classList.remove('fa-caret-up');
                    icon.classList.add('fa-caret-down');
                }
            }

            // 搜索篩選
            function search() {
                const category = document.getElementById("category").value.trim();
                const keyword = document.getElementById("search").value.trim();
                const sortHot = currentSort?.hot === 'asc' ? 'asc' : 'desc';
                const sortNew = currentSort?.new === 'asc' ? 'asc' : 'desc';

                // 根據篩選條件過濾資料
                const filteredSuggestions = suggestions.filter(suggestion => {
                    const matchesCategory = category === "" || suggestion.category.includes(category);
                    const matchesKeyword = keyword === "" || suggestion.title.toLowerCase().includes(keyword.toLowerCase());
                    const matchesSortHot = sortHot === 'asc' ? suggestion.hot : !suggestion.hot;
                    const matchesSortNew = sortNew === 'asc' ? suggestion.new : !suggestion.new;

                    return matchesCategory && matchesKeyword && matchesSortHot && matchesSortNew;
                });

                renderSuggestions(filteredSuggestions);  // 渲染篩選後的結果
            }

        });
    </script>

</body>

</html>