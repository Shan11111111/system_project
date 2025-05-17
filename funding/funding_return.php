<?php
// 資料庫連線
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}

// 假設 user_id 是從 session 中取得
session_start();
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    die("錯誤：您尚未登入，請先登入系統。");
}
$office_id = $_SESSION['user_id'];

// 確認募資專案狀態
$project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;

// 設定每頁顯示的專案數量
$limit = 5; // 每頁顯示 5 筆
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // 當前頁數
$offset = ($page - 1) * $limit; // 計算偏移量

// 計算總專案數量
$count_sql = "SELECT COUNT(*) AS total FROM fundraising_projects WHERE status = '已完成'";
$count_result = $conn->query($count_sql);
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit); // 計算總頁數

// 查詢當前頁的專案
$completed_projects_sql = "SELECT project_id, title, description, funding_goal, start_date, end_date 
                           FROM fundraising_projects 
                           WHERE status = '已完成'
                           LIMIT $limit OFFSET $offset";
$completed_projects_result = $conn->query($completed_projects_sql);

$status_message = "";
if ($completed_projects_result->num_rows === 0) {
    $status_message = "目前沒有已完成專案。";
} else {
    $status_message = "此專案已完成，您可以提交回報。";
}

// 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = isset($_POST['project_id']) ? intval($_POST['project_id']) : 0;

    if ($project_id <= 0) {
        die("錯誤：請選擇有效的專案。");
    }

    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $file_path = '';

    // 處理上傳檔案
    if (!empty($_FILES['file']['name'])) {
        $upload_dir = "../file_upload/";

        // 確保目錄存在
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // 檢查檔案類型
        $allowed_types = ['application/pdf'];
        $file_type = mime_content_type($_FILES['file']['tmp_name']);
        if (!in_array($file_type, $allowed_types)) {
            die("只允許上傳 PDF 檔案。");
        }

        // 生成唯一檔案名稱
        $file_name = uniqid() . '_' . basename($_FILES['file']['name']);
        $file_path = $upload_dir . $file_name;

        // 移動檔案
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
            die("檔案上傳失敗。");
        }
    }

    // 插入回報資料
    $sql = "INSERT INTO execution_report (project_id, title, content, file_path, updated_time) 
            VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $project_id, $title, $content, $file_path);

    if ($stmt->execute()) {
        echo "<script>alert('回報已成功提交！');location.href='funding_return.php';</script>";
    } else {
        echo "回報提交失敗：" . $conn->error;
        echo "<script>alert('回報提交失敗！');location.href='funding_return.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>募資專案進度回報</title>

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


        /* 表格樣式 */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        thead {
            background-color: var(--color-dark-brown);
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

        /* 表單樣式 */
        form {
            max-width: 750px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        input,
        textarea,
        select {
            width: 95%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: var(--color-orange-brown);
            outline: none;
            box-shadow: 0 0 5px rgba(94, 85, 1, 0.5);
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: var(--color-orange);
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: var(--color-orange-brown);
        }

        .status-message {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            color: #333;
            border-radius: 4px;
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

    <!-- 右側內容區域 -->
    <div class="content">


        <!-- 金額達標專案清單 -->
        <h1>募資進度回報</h1>
        <!-- HTML 開始 -->
        <div class="tabs">
            <div class="tab active" data-target="achieve">金額達標的募資專案</div>
            <div class="tab" data-target="report_progress">提交回報</div> <!-- ⚠️ 修正 data-target 名稱 -->
        </div>

        <!-- 第一個 tab 內容 -->
        <div class="tab-content active" data-tab="achieve">
            <?php if ($completed_projects_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>專案 ID</th>
                            <th>標題</th>
                            <th>描述</th>
                            <th>募資目標</th>
                            <th>開始日期</th>
                            <th>結束日期</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $completed_projects_result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <a href="../funding_detail.php?project_id=<?= htmlspecialchars($row['project_id']); ?>">
                                        <?= htmlspecialchars($row['project_id']); ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($row['title']); ?></td>
                                <td><?= htmlspecialchars($row['description']); ?></td>
                                <td><?= htmlspecialchars($row['funding_goal']); ?></td>
                                <td><?= htmlspecialchars($row['start_date']); ?></td>
                                <td><?= htmlspecialchars($row['end_date']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <!-- 分頁 -->
                <div class="pagination" style="text-align: center; margin-top: 20px;">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>"
                            style="margin-right: 10px; text-decoration: none; color: #ffd966;">上一頁</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?= $i ?>"
                            style="margin-right: 10px; text-decoration: none; <?= $i == $page ? 'font-weight: bold; color: #000;' : 'color: #ffd966;' ?>"><?= $i ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?= $page + 1 ?>" style="text-decoration: none; color: #ffd966;">下一頁</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <p>目前沒有已完成的募資專案。</p>
            <?php endif; ?>
        </div>

        <!-- 第二個 tab 內容 -->
        <div class="tab-content" data-tab="report_progress">
            <form action="" method="POST" enctype="multipart/form-data">
                <?php $completed_projects_result = $conn->query($completed_projects_sql); ?>
                <label for="project_id">選擇專案：</label>
                <select id="project_id" name="project_id" required>
                    <option value="">請選擇專案</option>
                    <?php while ($row = $completed_projects_result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['project_id']); ?>"><?= htmlspecialchars($row['title']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label for="title">回報標題：</label>
                <input type="text" id="title" name="title" required>

                <label for="content">回報內容：</label>
                <textarea id="content" name="content" rows="5" required></textarea>

                <label for="file">上傳附件：</label>
                <input type="file" id="file" name="file">

                <button type="submit">提交回報</button>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const tabs = document.querySelectorAll(".tab");
            const contents = document.querySelectorAll(".tab-content");

            tabs.forEach(tab => {
                tab.addEventListener("click", () => {
                    const target = tab.dataset.target;
                    const content = document.querySelector(`[data-tab="${target}"]`);
                    if (!content) return; // 容錯

                    tabs.forEach(t => t.classList.remove("active"));
                    contents.forEach(c => c.classList.remove("active"));

                    tab.classList.add("active");
                    content.classList.add("active");
                });
            });
        });

        document.querySelector('form').addEventListener('submit', function (e) {
            const projectId = document.getElementById('project_id').value;
            if (!projectId) {
                e.preventDefault();
                alert('請選擇一個專案！');
            }
        });
    </script>