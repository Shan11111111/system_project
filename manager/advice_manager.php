<?php
session_start();
// // 檢查是否已登入
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }
// // 檢查使用者權限
// if ($_SESSION['user_role'] !== 'manager') {
//     echo "您沒有權限訪問此頁面。";
//     header("Location: ../homepage.php");
//     exit();
// }


// 資料庫連線設定
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "system_project"; // 替換為您的資料庫名稱

// 建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 處理搜尋條件
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'desc'; // 預設為公告日期近到遠
$agree_sort = isset($_GET['agree_sort']) ? $_GET['agree_sort'] : ''; // 預設不排序覆議次數
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1; // 預設為第1頁
$limit = 8; // 每頁顯示8筆資料
$offset = ($page - 1) * $limit;

// 修改 SQL 查詢，加入搜尋、排序和覆議次數排序
$order_by = "announce_date $sort"; // 預設按公告日期排序
if ($agree_sort === 'desc') {
    $order_by = "agree DESC";
} elseif ($agree_sort === 'asc') {
    $order_by = "agree ASC";
}

$sql = "SELECT advice_id, advice_title, advice_content, agree, advice_state, announce_date, category 
        FROM advice 
        WHERE advice_title LIKE '%$search%' 
           OR advice_content LIKE '%$search%' 
           OR category LIKE '%$search%' 
        ORDER BY $order_by 
        LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

// 計算總頁數
$count_sql = "SELECT COUNT(*) as total FROM advice 
              WHERE advice_title LIKE '%$search%' 
                 OR advice_content LIKE '%$search%' 
                 OR category LIKE '%$search%'";
$count_result = $conn->query($count_sql);
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// 建立分類對照表
$categoryMap = [
    "all" => "全部分類",
    "equipment" => "設施改善",
    "academic" => "學術發展",
    "club" => "社團活動",
    "welfare" => "公益關懷",
    "environment" => "環保永續",
    "other" => "其他"
];
?>

<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>建言管理頁面</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
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
            /* 與 sidebar 寬度一致 */
            padding: 20px;
            width: calc(100% - 250px);
        }

        /* 頭部個人資料 */
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
            display: inline-block;
        }

        .profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
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
        }

        .dropdown a:hover {
            background-color: rgb(159, 193, 255);
        }

        /*form*/

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            margin-bottom: 20px;
            padding: 20px 0;
        }

        .filter-form input[type="text"],
        .filter-form select {
            padding: 10px;
            width: 220px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            flex-shrink: 0;
        }

        .filter-form button,
        .filter-form a {
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 4px;
            display: inline-block;
            text-align: center;
            white-space: nowrap;
            height: 42px;
            /* ✅ 統一高度 */
            line-height: 20px;
        }

        .filter-form button {
            background-color: #007BFF;
            color: #fff;
            border: none;
        }

        .filter-form button:hover {
            background-color: #0056b3;
        }

        .filter-form a {
            background-color: #6c757d;
            color: #fff;
            text-decoration: none;
            line-height: 20px;
        }

        /* 表格樣式 */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .table-ellipsis {
            max-width: 150px;
            /* 自行調整欄位寬度 */
            max-height: 60px;
            /* 自行調整欄位高度 */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            cursor: pointer;
            text-align: left;
            /* ✅ 只改內容靠左 */

        }


        thead {
            background-color: #007BFF;
            color: #fff;
        }

        th {
            text-align: center;
            /* ✅ 表頭維持置中（預設也是） */
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

        input[type="number"] {
            padding: 8px;
            width: 80%;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 8px 12px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .my-popup {
            font-size: 16px;
        }

        .my-confirm-btn {
            background-color: #007BFF;
            color: white;
            padding: 8px 20px;
            font-size: 14px;
        }

        .pagination {
            text-align: center;
            margin: 30px 0;
            font-size: 16px;
        }

        .page-link {
            display: inline-block;
            padding: 6px 10px;
            color: #5c2e91;
            border-bottom: 2px solid transparent;
            text-decoration: none;
            margin: 0 4px;
            transition: all 0.2s ease;
        }

        .page-link:hover {
            border-color: #5c2e91;
            color: #2c0d77;
        }

        .page-link.active {
            font-weight: bold;
            border-color: #5c2e91;
            color: #5c2e91;
            pointer-events: none;
        }
    </style>
</head>

<body>
    <!-- 左側導覽列 -->
    <div class="sidebar">
        <h2>管理系統</h2>
        <a href="../homepage.php">孵仁首頁</a>
        <a href="advice_manager.php">建言管理</a>
        <!-- <a href="assign_office.php">達標建言分配處所</a> -->
        <a href="review_proposals.php">募資專案審核</a>
        <!-- <a href="project_manager.php">募資管理</a> -->
        <a href="review_extension_requests.php">延後募資申請審核</a>
        <a href="people_manager.php">人員處理</a>
        <a href="../funding/announcement.php">發布公告</a>


        <!-- <a href="#">數據分析</a> -->
    </div>

    <div class="content">
        <h1>建言管理頁面</h1>

        <!-- 搜尋表單 -->
        <div class="filter-bar">
            <form method="GET" action="advice_manager.php" class="filter-form">
                <input type="text" name="search" placeholder="搜尋建言..." value="<?php echo htmlspecialchars($search); ?>">
                <select name="sort">
                    <option value="desc" <?php echo $sort === 'desc' ? 'selected' : ''; ?>>公告日期：近到遠</option>
                    <option value="asc" <?php echo $sort === 'asc' ? 'selected' : ''; ?>>公告日期：遠到近</option>
                </select>
                <select name="agree_sort">
                    <option value="" <?php echo $agree_sort === '' ? 'selected' : ''; ?>>覆議次數排序</option>
                    <option value="desc" <?php echo $agree_sort === 'desc' ? 'selected' : ''; ?>>覆議次數：高到低</option>
                    <option value="asc" <?php echo $agree_sort === 'asc' ? 'selected' : ''; ?>>覆議次數：低到高</option>
                </select>
                <button type="submit">搜尋</button>
            </form>
            <a href="advice_manager.php" class="clear-btn">清除篩選</a>
        </div>
        
        <!-- 表格內容 -->
        <table>
            <thead>
                <tr>
                    <th>建言 ID</th>
                    <th>建言標題</th>
                    <th>建言內容</th>
                    <th>分類</th>
                    <th>處理狀態</th>
                    <th>公告日期</th>
                    <th>覆議次數</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // 取得中文分類名稱
                        $categoryName = $categoryMap[$row['category']] ?? $row['category'];
                        echo "<tr>";
                        echo "<td>" . $row['advice_id'] . "</td>";
                        echo "<td class='table-ellipsis' title='" . htmlspecialchars($row['advice_title']) . "' onclick='showFullText(\"" . htmlspecialchars(addslashes($row['advice_title'])) . "\", \"" . htmlspecialchars(addslashes($row['advice_content'])) . "\")'>" . htmlspecialchars($row['advice_title']) . "</td>";
                        echo "<td class='table-ellipsis' title='" . htmlspecialchars($row['advice_content']) . "' onclick='showFullText(\"" . htmlspecialchars(addslashes($row['advice_title'])) . "\", \"" . htmlspecialchars(addslashes($row['advice_content'])) . "\")'>" . htmlspecialchars($row['advice_content']) . "</td>";

                        echo "<td>" . htmlspecialchars($categoryName) . "</td>";
                        echo "<td>" . ($row['advice_state'] ?? '未處理') . "</td>";
                        echo "<td>" . $row['announce_date'] . "</td>";
                        echo "<td>" . $row['agree'] . "</td>";
                        echo "<td>";
                        echo "<form action='delete_advice.php' method='POST' style='display:inline-block; margin-left:10px;'>";
                        echo "<input type='hidden' name='advice_id' value='" . $row['advice_id'] . "'>";
                        echo "<button type='submit' onclick='return confirm(\"確定要刪除這個建言嗎？\");'>刪除</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>沒有符合條件的建言</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <!-- 引入 SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            function showFullText(title, content) {
                Swal.fire({
                    title: title,
                    html: `
            <div style="
                max-height: 300px;
                overflow-y: auto;
                text-align: left;
                padding-right: 10px;
                line-height: 1.6;
                font-size: 18px;">
                ${content}
            </div>
        `,
                    width: 600,
                    showCloseButton: false,
                    confirmButtonText: '關閉',
                    customClass: {
                        confirmButton: 'my-confirm-btn'
                    }
                });
            }
        </script>


        <!-- 分頁按鈕 -->
        <div class="pagination">
            <?php
            $visiblePages = 5; // 最多顯示幾個分頁按鈕
            $start = max(1, $page - floor($visiblePages / 2));
            $end = min($start + $visiblePages - 1, $total_pages);

            // 修正 start 若接近尾頁時會超出 total_pages
            $start = max(1, $end - $visiblePages + 1);
            ?>

            <?php if ($page > 1): ?>
                <a href="?search=<?= urlencode($search); ?>&sort=<?= $sort; ?>&agree_sort=<?= $agree_sort; ?>&page=<?= $page - 1 ?>" class="page-link">&laquo; 上一頁</a>
            <?php endif; ?>

            <?php for ($i = $start; $i <= $end; $i++): ?>
                <a href="?search=<?= urlencode($search); ?>&sort=<?= $sort; ?>&agree_sort=<?= $agree_sort; ?>&page=<?= $i ?>"
                    class="page-link <?= ($i === $page) ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?search=<?= urlencode($search); ?>&sort=<?= $sort; ?>&agree_sort=<?= $agree_sort; ?>&page=<?= $page + 1 ?>" class="page-link">下一頁 &raquo;</a>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        // 點擊其他地方關閉下拉選單
        window.onclick = function(event) {
            const dropdown = document.getElementById('dropdownMenu');
            if (!event.target.matches('.profile img')) {
                dropdown.style.display = 'none';
            }
        }
    </script>
</body>

</html>
<?php
$conn->close();
?>