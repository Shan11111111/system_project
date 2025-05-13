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

// 處理篩選條件
$filter_level = isset($_GET['level']) ? $_GET['level'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// 分頁邏輯
$limit = 10; // 每頁顯示 10 筆資料
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// 動態生成 SQL 查詢
$sql = "SELECT user_id, name, level, email, department FROM users WHERE level != 'manager'";
if (!empty($filter_level)) {
    $sql .= " AND level = '" . $conn->real_escape_string($filter_level) . "'";
}
if (!empty($search_query)) {
    $sql .= " AND (name LIKE '%" . $conn->real_escape_string($search_query) . "%' 
                OR email LIKE '%" . $conn->real_escape_string($search_query) . "%' 
                OR department LIKE '%" . $conn->real_escape_string($search_query) . "%'
                OR user_id LIKE '%" . $conn->real_escape_string($search_query) . "%')";
}
$sql .= " LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// 計算總頁數
$total_sql = "SELECT COUNT(*) AS total FROM users WHERE level != 'manager'";
if (!empty($filter_level)) {
    $total_sql .= " AND level = '" . $conn->real_escape_string($filter_level) . "'";
}
if (!empty($search_query)) {
    $total_sql .= " AND (name LIKE '%" . $conn->real_escape_string($search_query) . "%' 
                    OR email LIKE '%" . $conn->real_escape_string($search_query) . "%' 
                    OR department LIKE '%" . $conn->real_escape_string($search_query) . "%'
                    OR user_id LIKE '%" . $conn->real_escape_string($search_query) . "%')";
}
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_pages = ceil($total_row['total'] / $limit);
?>

<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>人員管理頁面</title>
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
            padding: 20px;
            width: calc(100% - 250px);
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

        /* 分頁樣式 */
        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            margin: 0 5px;
            padding: 10px 15px;
            text-decoration: none;
            color: #007BFF;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .pagination a.active {
            background-color: #007BFF;
            color: #fff;
            border: none;
        }

        .pagination a:hover {
            background-color: #0056b3;
            color: #fff;
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

    
    <!-- 頁面內容 -->
    <div class="content">
        <!-- 頭部 -->
        <!-- <div class="header">
            <div class="profile">
                <img src="../img/logo.png" alt="頭像" onclick="toggleDropdown()">
                <div class="dropdown" id="dropdownMenu">
                    <a href="#">個人資料</a>
                    <a href="#">設定</a>
                    <a href="#">登出</a>
                </div>
            </div>
        </div> -->

        <!-- 篩選表單 -->
        <h1>人員管理頁面</h1>
        <form method="GET" action="people_manager.php" style="margin-bottom: 20px; display: flex; align-items: center; gap: 15px; flex-wrap: wrap; background-color: #f9f9f9; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            <div style="display: flex; flex-direction: column;">
                <label for="level" style="font-weight: bold; margin-bottom: 5px;">篩選身分:</label>
                <select name="level" id="level" style="padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 200px;">
                    <option value="">全部</option>
                    <option value="student" <?php if ($filter_level == 'student') echo 'selected'; ?>>學生</option>
                    <option value="teacher" <?php if ($filter_level == 'teacher') echo 'selected'; ?>>教職員</option>
                    <option value="office" <?php if ($filter_level == 'office') echo 'selected'; ?>>處所負責人</option>
                </select>
            </div>
            <div style="display: flex; flex-direction: column;">
                <label for="search" style="font-weight: bold; margin-bottom: 5px;">查詢:</label>
                <input type="text" name="search" id="search" placeholder="輸入學號、名字、email或科系" value="<?php echo htmlspecialchars($search_query); ?>" style="padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 300px;">
            </div>
            <div>
                <button type="submit" style="padding: 10px 20px 10px; background-color: #007BFF; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">篩選</button>
            </div>
        </form>

        <!-- 表格內容 -->
        <table>
            <thead>
                <tr>
                    <th>學號ID/教職員編號</th>
                    <th>名字</th>
                    <th>科系</th>
                    <th>email</th>
                    <th>身分</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['user_id'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['department']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . $row['level'] . "</td>";
                        echo "<td>";
                        echo "<form action='delete_people.php' method='POST' style='display:inline-block; margin-left:10px;'>";
                        echo "<input type='hidden' name='user_id' value='" . $row['user_id'] . "'>";
                        echo "<button type='submit' onclick='return confirm(\"確定要刪除這個使用者嗎？\");'>刪除</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>沒有符合條件的使用者</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- 分頁 -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="people_manager.php?page=<?php echo $i; ?>&level=<?php echo htmlspecialchars($filter_level); ?>&search=<?php echo htmlspecialchars($search_query); ?>" class="<?php echo $i == $page ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
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