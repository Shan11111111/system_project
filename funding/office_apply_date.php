<?php
// filepath: c:\xampp\htdocs\analysis_project\funding\office_apply_date.php
// 申請延後募資截止日的頁面
// 這個頁面會顯示所有已過期的募資專案，並提供延後截止日的申請表單

// 資料庫連線
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}

session_start();
$office_id = $_SESSION['user_id']; // 從 session 中獲取處所 ID

// 查詢已過期的募資專案
$sql = "SELECT project_id, title, end_date, suggestion_assignments.office_id 
        FROM fundraising_projects f 
        INNER JOIN suggestion_assignments 
        ON f.suggestion_assignments_id = suggestion_assignments.suggestion_assignments_id
        WHERE f.end_date < CURDATE() AND f.status='進行中' AND suggestion_assignments.office_id = ?";


$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL 錯誤: " . $conn->error);
}
$stmt->bind_param("i", $office_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <title>處所分派建言</title>
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

        .tabs {
            display: flex;
            border-bottom: 1px solid #ccc;
            background-color: #fff8ec;
            width: 100%;
        }

        .tab {
            flex: 1;
            /* 每個 tab 平均分配寬度 */
            text-align: center;
            padding: 14px 0;
            cursor: pointer;
            font-weight: bold;
            color: #999;
            background-color: transparent;
            border: none;
            position: relative;
            border-radius: 12px 12px 0 0;
            transition: background-color 0.3s, color 0.3s;
        }

        /* 選取中 */
        .tab.active {
            background-color: #fff5dd;
            color: #5c3a00;
        }

        /* 下底線，佔滿 tab 寬度 */
        .tab.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background-color: var(--color-orange);
        }

        /* hover 效果 */
        .tab:hover:not(.active) {
            background-color: #fff0d6;
            color: #666;
        }


        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }






        input[type="number"] {
            padding: 8px;
            width: 80%;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 8px 12px;
            background-color: var(--color-orange);
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: var(--color-orange-brown);
        }

        .reply-records {
            margin-top: 10px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .reply-records h4 {
            margin: 0 0 10px;
            font-size: 1em;
            color: #333;
        }

        .reply-records ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .reply-records ul li {
            margin-bottom: 5px;
            font-size: 0.9em;
            color: #555;
        }




        table.expired-table {
            width: 1050px;
            margin: 20px auto;
            border-collapse: collapse;
            font-family: Arial;
        }

        .expired-table th,
        .expired-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        .expired-table th {
            background-color: #7c4d2b;
            color: white;
        }

        .expired-table td {
            background-color: white;
            color: black;
        }

        .expired-table form {
            margin: 0;
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


    <div style="margin-left: 300px; padding: 20px;">
        <h1>延後募款申請</h1>

        <!-- 這裡放專案卡片列表 -->
        <div class="tabs">
            <div class="tab active" data-target="applied">已申請延期</div>
            <div class="tab" data-target="not-applied">尚未申請延期</div>
        </div>




        <?php
        if ($result->num_rows > 0) {
            // 已申請延期 tab
            echo '<div class="tab-content active" data-tab="applied">';
            echo "<table class='expired-table'><thead><tr><th>專案名稱</th><th>截止日期</th><th>延期狀態</th><th>操作</th></tr></thead><tbody>";
            mysqli_data_seek($result, 0);
            while ($row = $result->fetch_assoc()) {
                $projectId = $row['project_id'];
                $title = htmlspecialchars($row['title']);
                $endDate = htmlspecialchars($row['end_date']);
                $check = $conn->query("SELECT * FROM fundraising_extension_requests WHERE fundraising_project_id = $projectId AND status = '待審核' LIMIT 1");
                if ($check->num_rows > 0) {
                    $extension = $check->fetch_assoc();
                    echo "<tr><td style='color:#black;'>$title</td><td>$endDate</td><td>已申請延期至：<strong>" . $extension['requested_extension_date'] . "</strong></td><td>
           
            <form method='POST' style='display:inline;'><input type='hidden' name='cancel_request_id' value='" . $extension['id'] . "'><button type='submit' style='background-color:#dc3545;color:#fff;border:none;padding:6px 10px;border-radius:5px;'>取消申請</button></form>
            </td></tr>";
                }
            }
            echo "</tbody></table></div>";

            // 尚未申請 tab
        
            $stmt->execute();
            $result = $stmt->get_result();
            echo '<div class="tab-content" data-tab="not-applied">';
            echo "<table class='expired-table'><thead><tr><th>專案名稱</th><th>截止日期</th><th>延期狀態</th><th>操作</th></tr></thead><tbody>";
            while ($row = $result->fetch_assoc()) {
                $projectId = $row['project_id'];
                $title = htmlspecialchars($row['title']);
                $endDate = htmlspecialchars($row['end_date']);
                $check = $conn->query("SELECT * FROM fundraising_extension_requests WHERE fundraising_project_id = $projectId AND status = '待審核' LIMIT 1");
                if ($check->num_rows === 0) {
                    echo "<tr><td style='color:black;'>$title</td><td>$endDate</td><td>尚未申請延期</td><td>
            <form method='POST'><input type='hidden' name='fundraising_project_id' value='$projectId'><input type='date' name='requested_extension_date' required>
            <button type='submit' style='color:white;border:none;padding:6px 10px;border-radius:5px;'>提交申請</button></form></td></tr>";
                }
            }
            echo "</tbody></table></div>";
        } else {
            echo "<p style='text-align:center;'>目前沒有已過期的募資專案。</p>";
        }

        if (isset($_POST['cancel_request_id'])) {
            $cancel_id = intval($_POST['cancel_request_id']);
            $conn->query("DELETE FROM fundraising_extension_requests WHERE id = $cancel_id");
            echo "<script>alert('申請已取消');location.href='office_apply_date.php';</script>";
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fundraising_project_id'])) {
            $fid = intval($_POST['fundraising_project_id']);
            $date = $_POST['requested_extension_date'];
            $sql = "INSERT INTO fundraising_extension_requests (fundraising_project_id, requested_by_office_id, requested_extension_date, status) VALUES (?, ?, ?, '待審核')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iis", $fid, $office_id, $date);
            if ($stmt->execute()) {
                echo "<script>alert('申請已送出');location.href='office_apply_date.php';</script>";
            } else {
                echo "<script>alert('送出失敗');</script>";
            }
            $stmt->close();
        }
        $conn->close();
        ?>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const tabs = document.querySelectorAll(".tab");
                const contents = document.querySelectorAll(".tab-content");

                tabs.forEach(tab => {
                    tab.addEventListener("click", () => {
                        const target = tab.dataset.target;

                        // 移除所有 active 狀態
                        tabs.forEach(t => t.classList.remove("active"));
                        contents.forEach(c => c.classList.remove("active"));

                        // 新增 active 狀態
                        tab.classList.add("active");
                        document.querySelector(`[data-tab="${target}"]`).classList.add("active");
                    });
                });
            });
        </script>

</body>

</html>