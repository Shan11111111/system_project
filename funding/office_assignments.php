<?php
// 資料庫連線
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}

// 啟動 Session
session_start();
$office_id = $_SESSION['user_id'];

// 統一 $cat 判斷
if ($_SESSION['user_id'] == '123') {
    $cat = 'academic';
} else if ($_SESSION['user_id'] == '345678') {
    $cat = 'club';
} else if ($_SESSION['user_id'] == '2222') {
    $cat = 'equipment';
} else if ($_SESSION['user_id'] == '0909') {
    $cat = 'welfare';
} else if ($_SESSION['user_id'] == '0904') {
    $cat = 'environment';
} else {
    $cat = 'other';
}

// 引入個人資料模組
// include 'profile_module.php';

// 每頁顯示的記錄數量
$records_per_page = 4;

// 獲取當前頁數，預設為第 1 頁
$current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($current_page < 1) {
    $current_page = 1;
}

// 計算偏移量
$offset = ($current_page - 1) * $records_per_page;

// 搜尋條件
$search = isset($_GET['search']) ? $_GET['search'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

// 計算總記錄數
$count_sql = "SELECT COUNT(*) AS total FROM suggestion_assignments sa
                              JOIN advice a ON sa.advice_id = a.advice_id
                              WHERE sa.office_id = ?";
// 更新 SQL 查詢條件
if (!empty($search)) {
    $count_sql .= " AND (a.advice_title LIKE ? OR sa.advice_id LIKE ?)";
}
if (!empty($status)) {
    $count_sql .= " AND sa.status = ?";
}
$count_stmt = $conn->prepare($count_sql);
// 綁定參數
if (!empty($search) && !empty($status)) {
    $search_param = '%' . $search . '%';
    $count_stmt->bind_param("isss", $office_id, $search_param, $search_param, $status);
} elseif (!empty($search)) {
    $search_param = '%' . $search . '%';
    $count_stmt->bind_param("iss", $office_id, $search_param, $search_param);
} elseif (!empty($status)) {
    $count_stmt->bind_param("is", $office_id, $status);
} else {
    $count_stmt->bind_param("i", $office_id);
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page); // 計算總頁數
$count_stmt->close();

// 查詢分派給該處所的建言
$sql = "SELECT sa.suggestion_assignments_id, sa.advice_id, a.advice_title, sa.status, sa.notification, sa.admin_feedback 
                        FROM suggestion_assignments sa
                        JOIN advice a ON sa.advice_id = a.advice_id
                        WHERE sa.office_id = ?";
if (!empty($search)) {
    $sql .= " AND (a.advice_title LIKE ? OR sa.advice_id LIKE ?)";
}
if (!empty($status)) {
    $sql .= " AND sa.status = ?";
}
$sql .= " ORDER BY sa.suggestion_assignments_id DESC LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
if (!empty($search) && !empty($status)) {
    $search_param = '%' . $search . '%';
    $stmt->bind_param("issii", $office_id, $search_param, $search_param, $status, $records_per_page, $offset);
} elseif (!empty($search)) {
    $search_param = '%' . $search . '%';
    $stmt->bind_param("issii", $office_id, $search_param, $search_param, $records_per_page, $offset);
} elseif (!empty($status)) {
    $stmt->bind_param("isii", $office_id, $status, $records_per_page, $offset);
} else {
    $stmt->bind_param("iii", $office_id, $records_per_page, $offset);
}
$stmt->execute();
$result = $stmt->get_result();

// 取得所有處所（只查一次，建議放在 while 迴圈外面，這裡為簡化直接查詢）
$offices_result = $conn->query("SELECT user_id, department FROM users where level='office'");
$offices = [];
while ($office_row = $offices_result->fetch_assoc()) {
    $offices[] = $office_row;
}
?>
<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>處所分派建言</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            background-color: #f4f4f9;
        }

        /* 左側導覽列 */
        .sidebar {
            width: 250px;
            background-color: #007BFF;
            color: #fff;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            margin: 5px 0;
            border-radius: 4px;
        }

        .sidebar a:hover {
            background-color: #0056b3;
        }

        /* 頁面內容 */
        .content {
            margin-left: 280px;
            padding: 20px;
            width: calc(100% - 280px);
        }

        /* 搜尋表單樣式 */
        .search-bar {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .search-bar input[type="text"] {
            padding: 8px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-bar button {
            padding: 8px 12px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }

        /* 美化下拉選單 */
        .search-bar select {
            padding: 8px;
            width: 200px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
            font-size: 1em;
            color: #333;
            appearance: none;
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4 5"%3E%3Cpath fill="%23333" d="M2 0L0 2h4z" /%3E%3C/svg%3E');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 10px;
            cursor: pointer;
        }

        .search-bar select:focus {
            outline: none;
            border-color: #007BFF;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        /* 表格樣式 */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        thead {
            background-color: #007BFF;
            color: #fff;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: rgb(167, 185, 255);
        }

        /* 分頁樣式 */
        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            margin: 0 5px;
            padding: 8px 12px;
            text-decoration: none;
            background-color: #007BFF;
            color: #fff;
            border-radius: 4px;
        }

        .pagination a.active {
            background-color: #0056b3;
            font-weight: bold;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }

        /* 卡片樣式 */
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            text-align: center;
        }

        .card h3 {
            margin: 0 0 10px;
        }

        .card h3 a {
            color: #007BFF;
            text-decoration: none;
        }

        .card h3 a:hover {
            text-decoration: underline;
        }

        .card p {
            margin: 5px 0;
        }

        .actions {
            margin-top: 10px;
        }

        .btn {
            display: inline-block;
            margin: 5px 0;
            padding: 8px 12px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .toggle-replies-btn {
            margin-top: 10px;
            padding: 8px 12px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .toggle-replies-btn:hover {
            background-color: #0056b3;
        }

        .replies {
            margin-top: 10px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        /* 頭部個人資訊區 */
        .header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 10px 20px;
            background-color: #f4f4f9;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .profile {
            position: relative;
            cursor: pointer;
        }

        .profile-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile-info p {
            margin: 0;
            font-size: 1em;
            color: #333;
        }

        .profile img {
            border-radius: 50%;
            width: 40px;
            height: 40px;
        }

        .dropdown {
            display: none;
            position: absolute;
            top: 50px;
            right: 0;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            overflow: hidden;
            z-index: 1000;
            width: 150px;
        }

        .dropdown a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #333;
            font-size: 0.9em;
        }

        .dropdown a:hover {
            background-color: rgb(159, 193, 255);
        }

        /* 轉移提案表單美化 */
        .transfer-form {
            display: flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
            margin-bottom: 10px;
        }

        .transfer-form select {
            padding: 8px 12px;
            border: 1.5px solid #007BFF;
            border-radius: 6px;
            background-color: #f4f8ff;
            font-size: 1em;
            color: #007BFF;
            transition: border-color 0.2s;
            outline: none;
        }

        .transfer-form select:focus {
            border-color: #0056b3;
            background-color: #e6f0ff;
        }

        .transfer-form .transfer-btn {
            padding: 8px 18px;
            background: linear-gradient(90deg, #007BFF 60%, #0056b3 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 1em;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0, 123, 255, 0.08);
            transition: background 0.2s, box-shadow 0.2s;
        }

        .transfer-form .transfer-btn:hover {
            background: linear-gradient(90deg, #0056b3 60%, #007BFF 100%);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
        }

        /* 懸浮按鈕樣式 */
        #fab {
            position: fixed;
            right: 40px;
            bottom: 40px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #007BFF;
            color: #fff;
            font-size: 12px;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            z-index: 999;
            /* 新增 relative 以便紅點定位 */
            position: fixed;
            /* 讓紅點能定位在按鈕內 */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #fab:hover {
            background: #0056b3;
        }

        /* Modal 樣式 */
        .fab-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.3);
        }

        .fab-modal-content {
            background: #fff;
            margin: 10% auto;
            padding: 30px 20px;
            border-radius: 8px;
            width: 320px;
            position: relative;
        }

        .fab-close {
            position: absolute;
            right: 16px;
            top: 10px;
            font-size: 24px;
            cursor: pointer;
        }

        .fab-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 14px;
            height: 14px;
            background: red;
            border-radius: 50%;
            border: 2px solid #fff;
            z-index: 1001;
            display: inline-block;
            box-shadow: 0 0 2px #333;
            pointer-events: none;
        }
    </style>
</head>

<body>
    <!-- 左側導覽列 -->
    <div class="sidebar">
        <h2>管理系統</h2>
        <a href="../homepage.php">孵仁首頁</a>
        <a href="announcement.php">發布公告</a>
        <a href="adapt.php">自由認領達標建言區</a>
        <a href="office_assignments.php">提交提案與專案管理</a>
        <a href="office_apply_date.php">延後募款申請</a>
        <a href="funding_FAQ.php">募資常見問題</a>
        <a href="funding_return.php">募資進度回報</a>
        <a href="data">數據分析</a>
    </div>



    <!-- 頁面內容 -->
    <div class="content">
        <h1>處所被分派之建言</h1>
        <!-- 搜尋表單 -->
        <div class="search-bar">
            <form action="office_assignments.php" method="GET">
                <input type="text" name="search" placeholder="輸入建言 ID 或標題進行搜尋"
                    value="<?php echo htmlspecialchars($search); ?>">
                <select name="status">
                    <option value="">所有狀態</option>
                    <option value="草擬中" <?php echo $status === '草擬中' ? 'selected' : ''; ?>>草擬中</option>
                    <option value="被退回" <?php echo $status === '被退回' ? 'selected' : ''; ?>>被退回</option>
                    <option value="審核中" <?php echo $status === '審核中' ? 'selected' : ''; ?>>審核中</option>
                    <option value="已通過" <?php echo $status === '已通過' ? 'selected' : ''; ?>>已通過</option>
                </select>
                <button type="submit">搜尋</button>
            </form>
        </div>

        <!-- 卡片顯示建言 -->
        <div class="card-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='card'>";
                    echo "<h3><a href='../advice_detail.php?advice_id=" . $row['advice_id'] . "' style='color: #007BFF; text-decoration: none;'>(點擊查看)建言 ID: " . htmlspecialchars($row['advice_id']) . "</a></h3>";
                    echo "<p><strong>建言標題:</strong> " . htmlspecialchars($row['advice_title']) . "</p>";

                    // 轉移提案表單
                    echo "<form action='transfer_assignment.php' method='POST' class='transfer-form'>";
                    echo "<input type='hidden' name='suggestion_assignments_id' value='" . $row['suggestion_assignments_id'] . "'>";
                    echo "<select name='new_office_id' required>";
                    echo "<option value=''>選擇轉移處所</option>";
                    foreach ($offices as $office) {
                        // 不顯示目前的處所
                        if ($office['user_id'] == $office_id)
                            continue;
                        echo "<option value='" . htmlspecialchars($office['user_id']) . "'>" . htmlspecialchars($office['department']) . "</option>";
                    }
                    echo "</select>";
                    echo "<button type='submit' class='transfer-btn'>轉移提案</button>";
                    echo "</form>";

                    // 操作按鈕
                    echo "<div class='actions'>狀態:";
                    if ($row['notification']) {
                        echo "<span style='color: red;'>有新的審核結果</span>";
                        echo " <a class='btn' href='reset_notification.php?suggestion_assignments_id=" . $row['suggestion_assignments_id'] . "'>查看通知</a>";
                    }
                    if ($row['status'] === '草擬中') {
                        echo " <a class='btn' href='submit_proposal.php?suggestion_assignments_id=" . $row['suggestion_assignments_id'] . "'>提交提案</a>";
                    } elseif ($row['status'] === '被退回') {
                        echo " <a class='btn' href='submit_proposal.php?suggestion_assignments_id=" . $row['suggestion_assignments_id'] . "'>重新提交</a>";
                    } elseif ($row['status'] === '審核中') {
                        echo " <span>等待審核</span>";
                    } elseif ($row['status'] === '已通過') {
                        echo " <span>已通過</span>";
                    }
                    echo "</div>";

                    // 回覆表單
                    echo "<form action='submit_reply.php' method='POST'>";
                    echo "<input type='hidden' name='suggestion_assignments_id' value='" . $row['suggestion_assignments_id'] . "'>";
                    echo "<textarea name='reply_text' rows='5' style='border-radius: 8px; width:100%' placeholder='輸入回覆內容...' required></textarea>";
                    echo "<br><button type='submit' style='width:100%;'>提交回覆</button>";
                    echo "</form>";

                    // 查看回覆紀錄按鈕和下拉內容
                    echo "<button class='toggle-replies-btn' onclick='loadReplies(this, " . $row['advice_id'] . ")'>查看回覆紀錄</button>";
                    echo "<div class='replies' style='display: none;'>";
                    echo "<p>回覆紀錄載入中...</p>";
                    echo "</div>";

                    echo "</div>";
                }
            } else {
                echo "<p>目前沒有分派的建言</p>";
            }
            ?>
        </div>

        <!-- 分頁按鈕 -->
        <div class="pagination">
            <?php if ($current_page > 1): ?>
                <a
                    href="?page=<?php echo $current_page - 1; ?>&search=<?php echo htmlspecialchars($search); ?>&status=<?php echo htmlspecialchars($status); ?>">上一頁</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>&status=<?php echo htmlspecialchars($status); ?>"
                    class="<?php echo $i === $current_page ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <a
                    href="?page=<?php echo $current_page + 1; ?>&search=<?php echo htmlspecialchars($search); ?>&status=<?php echo htmlspecialchars($status); ?>">下一頁</a>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function loadReplies(button, adviceId) {
            const repliesDiv = button.nextElementSibling;

            if (repliesDiv.style.display === "none") {
                // 顯示回覆紀錄容器
                repliesDiv.style.display = "block";
                button.textContent = "收起回覆紀錄";

                // 檢查是否已經載入過回覆紀錄
                if (!repliesDiv.dataset.loaded) {
                    // 發送 AJAX 請求
                    fetch(`load_replies.php?advice_id=${adviceId}`)
                        .then(response => response.text())
                        .then(data => {
                            repliesDiv.innerHTML = data;
                            repliesDiv.dataset.loaded = true; // 標記為已載入
                        })
                        .catch(error => {
                            repliesDiv.innerHTML = "<p>載入回覆紀錄失敗，請稍後再試。</p>";
                            console.error("Error loading replies:", error);
                        });
                }
            } else {
                // 隱藏回覆紀錄容器
                repliesDiv.style.display = "none";
                button.textContent = "查看回覆紀錄";
            }
        }
    </script>

    <?php
    // ...原本的 session 與 $cat 判斷...
    
    // 判斷有無可加入的建言
    $has_new_advice = false;
    $stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM advice WHERE category = ? AND advice_state = '未處理' AND agree > 2");
    if ($stmt) {
        $stmt->bind_param("s", $cat);
        $stmt->execute();
        $stmt->bind_result($cnt);
        $stmt->fetch();
        if ($cnt > 0)
            $has_new_advice = true;
        $stmt->close();
    }
    ?>
    <!-- 懸浮按鈕 -->
    <button id="fab" onclick="openFabModal()" <?php if (!$has_new_advice) echo 'disabled style="background:#aaa;cursor:not-allowed;"'; ?>>
    <?php if ($has_new_advice): ?>
        有新的建言需要加入!
        <span class="fab-dot"></span>
    <?php else: ?>
        目前無新的建言需要加入
    <?php endif; ?>
</button>

    <!-- 彈出表單 Modal -->
    <div id="fabModal" class="fab-modal">
        <div class="fab-modal-content">
            <span class="fab-close" onclick="closeFabModal()">&times;</span>
            <h3>請將達標建言加入提案!</h3>
            <form id="fabForm" method="POST" action="abc.php">
                <label for="advice_category">待處理建言：</label>
                <table>
                    <?php
                    $conn = new mysqli("localhost", "root", "", "system_project");
                    if ($conn->connect_error) {
                        die("連線失敗: " . $conn->connect_error);
                    }

                    if ($_SESSION['user_id'] == '123') {
                        $cat = 'academic';
                    } else if ($_SESSION['user_id'] == '345678') {
                        $cat = 'club';
                    } else if ($_SESSION['user_id'] == '2222') {
                        $cat = 'equipment';
                    } else if ($_SESSION['user_id'] == '0909') {
                        $cat = 'welfare';
                    } else if ($_SESSION['user_id'] == '0904') {
                        $cat = 'environment';

                    } else {
                        $cat = 'other';
                    }

                    $stmt = $conn->prepare("SELECT advice_id, advice_title, category FROM advice  WHERE  category = ? AND advice_state = '未處理' AND agree > 2");
                    if ($stmt) {
                        $stmt->bind_param("s", $cat);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        $has_result = false;
                        // 先收集所有資料
                        $rows = [];
                        while ($row = $result->fetch_assoc()) {
                            $has_result = true;
                            $rows[] = $row;
                        }
                        if ($has_result) {
                            // 只輸出一次表頭
                            echo "<tr><th>標題</th><th>分類</th></tr>";
                            foreach ($rows as $row) {
                                echo "<tr><td>" . htmlspecialchars($row['advice_title']) . "</td><td>" . htmlspecialchars($row['category']) . "</td></tr>";
                                // 用 hidden 欄位傳遞所有 advice_id
                                echo "<input type='hidden' name='advice_id[]' value='" . htmlspecialchars($row['advice_id']) . "'>";
                            }
                        } else {
                            echo "<p>目前沒有可加入的建言</p>";
                        }
                        $stmt->close();
                    } else {
                        echo "<p>查詢失敗：" . htmlspecialchars($conn->error) . "</p>";
                    }
                    ?>
                </table>
                <br><br>
                <button type="submit">加入提案</button>
            </form>
        </div>
    </div>

    

    <script>
        function openFabModal() {
            document.getElementById('fabModal').style.display = 'block';
        }
        function closeFabModal() {
            document.getElementById('fabModal').style.display = 'none';
        }
        // 點擊 modal 外部關閉
        window.onclick = function (event) {
            var modal = document.getElementById('fabModal');
            if (event.target === modal) modal.style.display = "none";
        }
    </script>

</body>

</html>
<?php
$conn->close();
?>