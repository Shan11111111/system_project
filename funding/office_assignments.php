<?php
                // 資料庫連線
                $conn = new mysqli("localhost", "root", "", "system_project");
                if ($conn->connect_error) {
                    die("資料庫連線失敗: " . $conn->connect_error);
                }

                // 啟動 Session
                session_start();
                $office_id = $_SESSION['user_id'];

                // 引入個人資料模組
                // include 'profile_module.php';

                // 每頁顯示的記錄數量
                $records_per_page = 4;

                // 獲取當前頁數，預設為第 1 頁
                $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                if ($current_page < 1) {
                    $current_page = 1;
                }

                // 計算偏移量
                $offset = ($current_page - 1) * $records_per_page;

                // 搜尋條件
                $search = isset($_GET['search']) ? $_GET['search'] : '';

                // 計算總記錄數
                $count_sql = "SELECT COUNT(*) AS total FROM suggestion_assignments sa
                              JOIN advice a ON sa.advice_id = a.advice_id
                              WHERE sa.office_id = ?";
                if (!empty($search)) {
                    $count_sql .= " AND (a.advice_title LIKE ? OR sa.advice_id LIKE ?)";
                }
                $count_stmt = $conn->prepare($count_sql);
                if (!empty($search)) {
                    $search_param = '%' . $search . '%';
                    $count_stmt->bind_param("iss", $office_id, $search_param, $search_param);
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
                $sql .= " ORDER BY sa.suggestion_assignments_id DESC LIMIT ? OFFSET ?";

                $stmt = $conn->prepare($sql);
                if (!empty($search)) {
                    $stmt->bind_param("issii", $office_id, $search_param, $search_param, $records_per_page, $offset);
                } else {
                    $stmt->bind_param("iii", $office_id, $records_per_page, $offset);
                }
                $stmt->execute();
                $result = $stmt->get_result();
                
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
    </style>
</head>

<body>
    <!-- 左側導覽列 -->
    <div class="sidebar">
        <h2>管理系統</h2>
        <a href="../homepage.php">孵仁首頁</a>
        <a href="#">發布公告</a>
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
        <div class="search-bar">
            <form action="office_assignments.php" method="GET">
                <input type="text" name="search" placeholder="輸入建言 ID 或標題進行搜尋" value="<?php echo htmlspecialchars($search); ?>">
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
                    // echo "<p><strong>狀態:</strong> " . htmlspecialchars($row['status']) . "</p>";

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
                <a href="?page=<?php echo $current_page - 1; ?>&search=<?php echo htmlspecialchars($search); ?>">上一頁</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>" 
                   class="<?php echo $i === $current_page ? 'active' : ''; ?>">
                   <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <a href="?page=<?php echo $current_page + 1; ?>&search=<?php echo htmlspecialchars($search); ?>">下一頁</a>
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
</body>

</html>
<?php
$conn->close();
?>