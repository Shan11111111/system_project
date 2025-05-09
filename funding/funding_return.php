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

// 查詢所有已完成的募資專案
$completed_projects_sql = "SELECT project_id, title, description, funding_goal, start_date, end_date 
                           FROM fundraising_projects 
                           WHERE status = '已完成'";
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
        echo "回報已成功提交！";
    } else {
        echo "回報提交失敗：" . $conn->error;
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

        h1,
        h2 {
            text-align: center;
            color: #f9f9f9;
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

        /* 表單樣式 */
        form {
            max-width: 600px;
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
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #007BFF;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
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
    </div>

    <!-- 右側內容區域 -->
    <div class="content">


        <!-- 已完成專案清單 -->
        <h2 style="color: black;">已完成的募資專案</h2>
        <?php if ($completed_projects_result->num_rows > 0) : ?>
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
                    <?php while ($row = $completed_projects_result->fetch_assoc()) : ?>
                        <tr>
                            <td>
                                <a href="../funding_detail.php?project_id=<?php echo htmlspecialchars($row['project_id']); ?>">
                                    <?php echo htmlspecialchars($row['project_id']); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['funding_goal']); ?></td>
                            <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>目前沒有已完成的募資專案。</p>
        <?php endif; ?>

        <!-- 回報表單 -->
        <h2 style="color: black;">提交回報</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <?php
            // 重新執行查詢以獲取已完成的專案
            $completed_projects_result = $conn->query($completed_projects_sql);
            ?>
            <label for="project_id">選擇專案：</label>
            <select id="project_id" name="project_id" required>
                <option value="">請選擇專案</option>
                <?php while ($row = $completed_projects_result->fetch_assoc()) : ?>
                    <option value="<?php echo htmlspecialchars($row['project_id']); ?>">
                        <?php echo htmlspecialchars($row['title']); ?>
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

</body>

<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        const projectId = document.getElementById('project_id').value;
        if (!projectId) {
            e.preventDefault();
            alert('請選擇一個專案！');
        }
    });
</script>


</html>