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

$limit = 9; // 每頁顯示的資料數量
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
    <!-- cdn link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        :root {
            /* 主色調 */
            --color-yellow: #fff6da;
            /* 鵝黃色 - 小雞感 */
            /*navbar*/
            --color-orange-brown: #D9A679;
            /* 溫柔橘棕 - 強調/按鈕  */
            --color-dark-brown: #7c4d2b;
            /* 深咖啡 - 導航、標題 * 文字的hover/ 

  /* 輔助色 */
            --color-soft-green: #dddfab7f;
            /* 嫩綠色 - 自然感 */
            --color-cream: #fff8ed;
            /* 奶油白 - 背景 */

            /* 字體與邊線 */
            --color-text: #4B3F2F;
            /* 深褐灰 - 內文字體 */
            --color-line: #D7CBB8;
            --navbar-text: #fff6da;
            /* 淡褐線條 */

            /* 狀態/互動 */
            --color-orange: #f6a623;

            /* hover/active 狀態用的柔橘 */
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--color-cream);
            color: #333;
        }

        /* 左側導覽列 */
        .sidebar {
            width: 250px;
            background-color: var(--color-yellow);
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
            color: var(--color-dark-brown);
        }

        .sidebar a {
            display: block;
            color: var(--color-dark-brown);
            text-decoration: none;
            padding: 10px 15px;
            margin: 5px 0;
            border-radius: 4px;
            font-size: 1em;
            font-weight: bold;
        }

        .sidebar a:hover {
            background-color: var(--color-orange-brown);
        }

        /* 頁面內容 */
        .content {
            margin-left: 280px;
            padding: 20px;
        }

        h1 {
            font-size: 2em;
            margin-bottom: 20px;
            color: var(--color-dark-brown);
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
            background-color: #f4d35e;
            color: black;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
        }

        .search-bar button:hover {
            background-color:rgb(197, 155, 3);
        }

        /* 分頁樣式 */
        .pagination {
            margin-top: 20px;
            text-align: center;
        }

       



        /* 卡片樣式 */
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background-color: var(--color-yellow);
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .card h3 {
            margin: 0 0 12px;
            font-size: 1.2em;
            color: #5a3c1a;
            /* 深咖啡主色系 */
        }

        .card p {
            margin: 6px 0;
            font-size: 0.95em;
            color: #333;
            line-height: 1.4;
        }

        /* 單行省略標題 */
        .title-ellipsis {
            display: inline-block;
            max-width: 180px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: bottom;
        }

       

        .card button {
            padding: 10px 15px;
            background-color: var(--color-orange);
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
            margin-top: 10px;
        }

        .card button:hover {
            background-color: rgb(197, 155, 3);
        }

        .pagination {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 8px;
        }


        .pagination a {
            margin: 0 5px;
            padding: 10px 15px;
            text-decoration: none;
            background-color: rgb(255, 241, 159);
            color: black;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .pagination a.active {
            background-color: #ffd966;
            font-weight: bold;
        }

        .pagination a:hover {
            background-color: #ffd966;
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
        <h2>行政專區</h2>
        <a href="../homepage.php">孵仁首頁</a>
        <a href="announcement.php">發布公告</a>
        <a href="adapt.php">自由認領達標建言區</a>
        <a href="../funding/office_assignments.php">提交提案與專案管理</a>
        <a href="office_apply_date.php">延後募款申請</a>
        <a href="funding_FAQ.php">募資常見問題</a>
        <a href="funding_return.php">募資進度回報</a>
        <a href="javascript:void(0);" id="logout-link"><i class="fa-solid fa-right-from-bracket"></i>登出</a>
        <script>
            document.getElementById('logout-link').addEventListener('click', function () {
                // 彈出確認視窗
                const confirmLogout = confirm("確定要登出嗎？");
                if (confirmLogout) {
                    // 如果用戶選擇確定，導向登出頁面
                    window.location.href = "../logout.php";
                }
                // 如果用戶選擇取消，什麼都不做
            });
        </script>
    </div>

    <!-- 個人資訊區 -->



    <div class="content">
        <h1>可認領的建言</h1>

        <!-- 搜尋欄 -->
        <div class="search-bar">
            <form action="adapt.php" method="GET">
                <input type="text" name="search" placeholder="搜尋建言..."
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>

        <!-- 卡片容器 -->
        <div class="card-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($advice = $result->fetch_assoc()): ?>

                    <div class="card">
                        <h3>
                            <a href="../advice_detail.php?advice_id=<?php echo htmlspecialchars($advice['advice_id']); ?>"
                                style="color: #7c4d2b; text-decoration: none;">
                                <span class="title-ellipsis"><?php echo htmlspecialchars($advice['advice_title']); ?></span>
                                (點擊查看)
                            </a>
                        </h3>
                        <?php $categoryMap = [
                            "all" => "全部分類",
                            "equipment" => "設施改善",
                            "academic" => "學術發展",
                            "club" => "社團活動",
                            "welfare" => "公益關懷",
                            "environment" => "環保永續",
                            "other" => "其他"
                        ]; ?>
                        <p><strong>建言ID:</strong> <?php echo htmlspecialchars($advice['advice_id']); ?></p>
                        <p><strong>建言人:</strong> <?php echo htmlspecialchars($advice['user_id']); ?></p>
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