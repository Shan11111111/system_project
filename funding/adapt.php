<?php
// 確保已經啟動 session 並連接資料庫
session_start();

if (!isset($_SESSION["user_id"])) {
    die("錯誤：未登入");
}

$user_id = $_SESSION["user_id"];

// 連接資料庫
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("連接失敗：" . $conn->connect_error);
}

// 引入個人資料模組
include 'profile_module.php';

// 計算資料總數
$total_sql = "SELECT COUNT(*) AS total FROM advice
              INNER JOIN advice_state
              ON advice.advice_id = advice_state.advice_id
              LEFT JOIN suggestion_assignments 
              ON advice.advice_id = suggestion_assignments.advice_id 
              WHERE advice.advice_state = '未處理' 
              AND suggestion_assignments.advice_id IS NULL 
              AND agree >= 3
              AND state_time >= DATE_SUB(NOW(), INTERVAL 10 DAY)";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];

$limit = 10; // 每頁顯示的資料數量
$total_pages = ceil($total_records / $limit); // 計算總頁數

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// 更新查詢語句，加入 LIMIT 和 OFFSET
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$sql = "SELECT user_id, advice_state,category, announce_date, advice.advice_id, advice_title, agree, state_time 
        FROM advice
        INNER JOIN advice_state
        ON advice.advice_id = advice_state.advice_id
        LEFT JOIN suggestion_assignments 
        ON advice.advice_id = suggestion_assignments.advice_id 
        WHERE advice.advice_state = '未處理' 
        AND suggestion_assignments.advice_id IS NULL 
        AND agree >= 3
        AND state_time >= DATE_SUB(NOW(), INTERVAL 10 DAY)";

if (!empty($search)) {
    $sql .= " AND (advice_title LIKE '%$search%' OR user_id LIKE '%$search%' or advice.advice_id like '%$search%')";
}

$sql .= " LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

if (!$result) {
    die("SQL 查詢失敗：" . $conn->error);
}


?>

<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>建言認領</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
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
            font-size: 1.5em;
        }

        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            margin: 5px 0;
            border-radius: 4px;
            font-size: 1em;
        }

        .sidebar a:hover {
            background-color: #0056b3;
        }

        /* 頁面內容 */
        .content {
            margin-left: 280px;
            padding: 20px;
        }

        h1 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #007BFF;
        }

        /* 表格樣式 */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        thead {
            background-color: #007BFF;
            color: #fff;
        }

        th,
        td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            font-weight: bold;
            font-size: 1em;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e6f0ff;
        }

        td {
            font-size: 0.95em;
        }

        /* 按鈕樣式 */
        button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* 搜尋表單樣式 */
        .search-bar {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-bar input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .search-bar button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }

        /* 分頁樣式 */
        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            margin: 0 5px;
            padding: 10px 15px;
            text-decoration: none;
            background-color: #007BFF;
            color: #fff;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .pagination a.active {
            background-color: #0056b3;
            font-weight: bold;
        }

        .pagination a:hover {
            background-color: #0056b3;
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

        /* 卡片樣式 */
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .card h3 {
            margin: 0 0 10px;
            font-size: 1.2em;
            color: #007BFF;
        }

        .card p {
            margin: 5px 0;
            font-size: 0.95em;
            color: #333;
        }

        .card button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
            margin-top: 10px;
        }

        .card button:hover {
            background-color: #0056b3;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            margin: 0 5px;
            padding: 10px 15px;
            text-decoration: none;
            background-color: #007BFF;
            color: #fff;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .pagination a.active {
            background-color: #0056b3;
            font-weight: bold;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        // 點擊其他地方關閉下拉選單
        window.onclick = function (event) {
            const dropdown = document.getElementById('dropdownMenu');
            if (!event.target.closest('.profile')) {
                dropdown.style.display = 'none';
            }
        };
    </script>
</head>

<body>
    <!-- 左側導覽列 -->
    <div class="sidebar">
        <h2>管理系統</h2>
        <a href="../homepage.php">孵仁首頁</a>
        <a href="announcement.php">發布公告</a>
        <a href="adapt.php">自由認領達標建言區</a>
        <a href="../funding/office_assignments.php">提交提案與專案管理</a>
        <a href="office_apply_date.php">延後募款申請</a>
        <a href="funding_FAQ.php">募資常見問題</a>
        <a href="funding_return.php">募資進度回報</a>
        <a href="data">數據分析</a>
    </div>

    <!-- 個人資訊區 -->



    <div class="content">
        <h1>可認領的建言</h1>

        <!-- 搜尋欄 -->
        <div class="search-bar">
            <form action="adapt.php" method="GET">
                <input type="text" name="search" placeholder="搜尋建言..."
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit">搜尋</button>
            </form>
        </div>

        <!-- 卡片容器 -->
        <div class="card-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($advice = $result->fetch_assoc()): ?>

                    <div class="card">
                        <?php echo "<h3><a href='../advice_detail.php?advice_id=" . htmlspecialchars($advice['advice_id']) . "' style='color: #007BFF; text-decoration: none;'>(點擊查看)建言 ID: " . htmlspecialchars($advice['advice_id']) . "</a></h3>";
                        $categoryMap = [
                            "all" => "全部分類",
                            "equipment" => "設施改善",
                            "academic" => "學術發展",
                            "club" => "社團活動",
                            "welfare" => "公益關懷",
                            "environment" => "環保永續",
                            "other" => "其他"
                        ]; ?>
                        <p><strong>建言人:</strong> <?php echo htmlspecialchars($advice['user_id']); ?></p>
                        <p><strong>建言內容:</strong> <?php echo htmlspecialchars($advice['advice_title']); ?></p>
                        <p><strong>覆議次數:</strong> <?php echo htmlspecialchars($advice['agree']); ?></p>
                        <p><strong>建言分類:</strong> <?php echo htmlspecialchars($categoryMap[$advice['category']] ?? '未知分類'); ?>
                        </p>
                        <p><strong>達成時間:</strong> <?php echo htmlspecialchars($advice['state_time']); ?></p>
                        <form action="office_adapt.php" method="POST">
                            <input type="hidden" name="advice_id" value="<?php echo htmlspecialchars($advice['advice_id']); ?>">
                            <button type="submit">認領</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>目前沒有可認領的建言。</p>
            <?php endif; ?>
        </div>

        <!-- 分頁 -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="adapt.php?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        // 點擊其他地方關閉下拉選單
        window.onclick = function (event) {
            const dropdown = document.getElementById('dropdownMenu');
            if (!event.target.closest('.profile')) {
                dropdown.style.display = 'none';
            }
        };
    </script>
</body>

</html>

<?php
$conn->close();
?>