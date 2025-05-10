<?php
session_start();

// 確保使用者已登入
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 獲取使用者 ID
$user_id = $_SESSION['user_id'];

// 資料庫連線設定
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "system_project";

// 建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 查詢使用者名稱和部門
$stmt = $conn->prepare("SELECT name, department FROM users WHERE user_id = ?");
if (!$stmt) {
    die("SQL 準備失敗：" . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $department);

if (!$stmt->fetch()) {
    $name = "未知使用者";
    $department = "未知部門";
}

$stmt->close();

// 查詢建言資料
$sql = "SELECT user_id, advice_state, announce_date, advice.advice_id, advice_title, agree, state_time 
        FROM advice
        INNER JOIN advice_state
        ON advice.advice_id = advice_state.advice_id
        LEFT JOIN suggestion_assignments 
        ON advice.advice_id = suggestion_assignments.advice_id 
        WHERE advice.advice_state = '未處理' 
        AND suggestion_assignments.advice_id IS NULL 
        AND agree >= 3
        AND state_time <= DATE_SUB(NOW(), INTERVAL 10 DAY)";

$result = $conn->query($sql);

if (!$result) {
    die("SQL 查詢失敗: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>達標建言處理</title>
    <style>
        /* 全局樣式 */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            background-color: #f4f6f9;
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
            transition: background-color 0.3s ease;
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

        .content h1 {
            font-size: 2em;
            color: #333;
            margin-bottom: 20px;
        }

        /* 頭部個人資訊區 */
        .header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 10px 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
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

        .profile-info img {
            border-radius: 50%;
            width: 40px;
            height: 40px;
        }

        .profile-info p {
            margin: 0;
            font-size: 1em;
            color: #333;
            font-weight: bold;
        }

        .dropdown {
            display: none;
            position: absolute;
            top: 60px;
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

        /* 按鈕樣式 */
        .btn {
            padding: 8px 12px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9em;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <!-- 左側導覽列 -->
    <div class="sidebar">
        <h2>管理系統</h2>
        <a href="../homepage.php">孵仁首頁</a>
        <a href="../manager/advice_manager.php">建言管理</a>
        <a href="assign_office.php">達標建言分配處所</a>
        <a href="review_proposals.php">募資專案審核</a>
        <a href="review_extension_requests.php">延後募資申請審核</a>
        <a href="people_manager.php">人員處理</a>
        <a href="../funding/announcement.php">發布公告</a>
        <a href="#">數據分析</a>
    </div>

    <!-- 頁面內容 -->
    <div class="content">
        <!-- 頭部 -->
        <div class="header">
            <div class="profile">
                <div class="profile-info" onclick="toggleDropdown()">
                    <img src="../img/logo.png" alt="頭像">
                    <p><?php echo htmlspecialchars($name); ?> - <?php echo htmlspecialchars($department); ?></p>
                </div>
                <div class="dropdown" id="dropdownMenu">
                    <a href="#">個人資料</a>
                    <a href="#">設定</a>
                    <a href="#">登出</a>
                </div>
            </div>
        </div>

        <!-- 表格內容 -->
        <h1>分配建言處理處所</h1>
        <table>
            <thead>
                <tr>
                    <th>建言 ID</th>
                    <th>建言人</th>
                    <th>建言內容</th>
                    <th>覆議次數</th>
                    <th>建言狀態</th>
                    <th>建言時間</th>
                    <th>覆議達成時間</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['advice_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['advice_title']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['agree']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['advice_state']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['announce_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['state_time']) . "</td>";
                        echo "<td><a href='assign_detail.php?advice_id=" . htmlspecialchars($row['advice_id']) . "' class='btn'>分派</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>沒有符合條件的建言</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        // 點擊其他地方關閉下拉選單
        window.onclick = function (event) {
            const dropdown = document.getElementById('dropdownMenu');
            if (!event.target.closest('.profile-info')) {
                dropdown.style.display = 'none';
            }
        }
    </script>
</body>

</html>

<?php
$conn->close();
?>