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

$result = $conn->query(query: $sql);

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
    <link rel="stylesheet" href="css/adv_manager.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>
    <!-- 左側導覽列 -->
    <div class="sidebar">
        <h2 style="color:#7c4d2b;">管理系統</h2>
        <a href="../homepage.php">孵仁首頁</a>
        <a href="advice_manager.php">建言管理</a>
        <!-- <a href="assign_office.php">達標建言分配處所</a> -->
        <a href="review_proposals.php">募資專案審核</a>
        <!-- <a href="project_manager.php">募資管理</a> -->
        <a href="review_extension_requests.php">延後募資申請審核</a>
        <a href="people_manager.php">人員處理</a>
        <a href="../funding/announcement.php">發布公告</a>
        <a href="javascript:void(0);" id="logout-link"><i class="fa-solid fa-right-from-bracket"></i>登出</a>

        <script>
            document.getElementById('logout-link').addEventListener('click', function() {
                const confirmLogout = confirm("確定要登出嗎？");
                if (confirmLogout) {
                    window.location.href = "../logout.php";
                }
            });
        </script>


        <!-- <a href="#">數據分析</a> -->
    </div>

    <div class="content">
        <h1>建言管理頁面</h1>

        <!-- 搜尋表單 -->
        <form method="GET" action="advice_manager.php" class="filter-form">
            <div class="filter-left">
                <input type="text" name="search" placeholder="搜尋建言..." value="<?php echo htmlspecialchars($search); ?>">
                <select name="sort">
                    <option value="desc" <?php echo $sort === 'desc' ? 'selected' : ''; ?>>公告日期：近到遠</option>
                    <option value="asc" <?php echo $sort === 'asc' ? 'selected' : ''; ?>>公告日期：遠到近</option>
                </select>
                <select name="agree_sort">
                    <option value="" <?php echo $agree_sort === '' ? 'selected' : ''; ?>>附議次數排序</option>
                    <option value="desc" <?php echo $agree_sort === 'desc' ? 'selected' : ''; ?>>覆議次數：高到低</option>
                    <option value="asc" <?php echo $agree_sort === 'asc' ? 'selected' : ''; ?>>覆議次數：低到高</option>
                </select>
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
            <a href="advice_manager.php" class="clear-btn">清除篩選</a>
        </form>

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
                    class="page-link <?= ((int)$i === (int)$page) ? 'active' : '' ?>">
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