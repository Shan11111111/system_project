<!DOCTYPE html>
<html lang="zh-Hant">
<head>
  <meta charset="UTF-8">
  <title>建言瀏覽頁面</title>
  <link rel="stylesheet" href="css/test.css">
</head>
<body>

  <!-- 快要達標 -->
  <div class="highlight">
    快要達標的建言：這裡可以加 swiper 或卡片！
  </div>

  <!-- Tabs -->
  <div class="tabs">
    <div class="tab active" onclick="switchTab('active')">進行中</div>
    <div class="tab" onclick="switchTab('ended')">已結束</div>
  </div>

  <!-- 選單 + 搜尋 -->
  <div class="filter-bar">
    <select id="category">
      <option value="">選擇分類</option>
      <option value="animal">動物</option>
      <option value="environment">環境</option>
    </select>
    <input type="text" id="search" placeholder="請輸入關鍵字">
    <button onclick="search()">搜尋</button>
    <button onclick="sortBy('hot')">HOT</button>
    <button onclick="sortBy('new')">NEW</button>
  </div>

  <!-- 建言列表 -->
  <div id="suggestion-list"></div>

  <!-- 分頁 -->
  <div class="pagination" id="pagination"></div>

  <div class="footer">footer</div>

  <script>
    const data = Array.from({ length: 25 }, (_, i) => ({
      title: `建言標題 ${i + 1}`,
      comments: Math.floor(Math.random() * 80),
      deadline: '剩約30天',
      status: i % 2 === 0 ? 'active' : 'ended',
      passed: i % 3 === 0, // 每三個通過一次
      publishDate: '2025-03-01'
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
                <span>附議數：${item.comments}</span>
                <span>留言數：${Math.floor(item.comments / 2)}</span>
                <span>${item.deadline}</span>
                <span>發布日：${item.publishDate}</span>
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
      alert("尚未實作");
    }

    function sortBy(type) {
      alert("排序：" + type);
    }

    renderSuggestions();
  </script>
</body>
</html>
