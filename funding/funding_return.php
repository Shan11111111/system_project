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

$sql = "SELECT status 
        FROM fundraising_projects 
        WHERE project_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL 錯誤：" . $conn->error);
}

$stmt->bind_param("i", $project_id);
$stmt->execute();
$result = $stmt->get_result();
$project = $result->fetch_assoc();

$status_message = "";
if (!$project) {
    $status_message = "目前沒有以完成專案。";
} elseif ($project['status'] !== '已完成') {
    $status_message = "注意：此專案尚未完成，您仍可提交回報。";
}

// 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $file_path = '';

    // 處理上傳檔案
    if (!empty($_FILES['file']['name'])) {
        $upload_dir = "../uploads/reports/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = basename($_FILES['file']['name']);
        $file_path = $upload_dir . $file_name;

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
        /* 保留原有的 CSS */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
        }

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
            margin: 0 0 20px;
            font-size: 24px;
            text-align: center;
        }

        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #0056b3;
        }

        .content {
            margin-left: 280px;
            padding: 20px;
            width: calc(100% - 250px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #ddd;
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

        .status-message {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            color: #333;
        }
    </style>
</head>

<body>
    <!-- 左側導覽列 -->
    <div class="sidebar">
        <h2>管理系統</h2>
        <a href="../homepage.php">孵仁首頁</a>
        <a href="../funding/office_assignments.php">提交提案</a>
        <a href="office_apply_date.php">延後募款申請</a>
        <a href="funding_FAQ.php">募資常見問題</a>
        <a href="funding_return.php">募資專案進度回報</a>
    </div>

    <!-- 右側內容區域 -->
    <div class="content">
        <h1>募資專案進度回報</h1>

        <!-- 狀態訊息 -->
        <?php if (!empty($status_message)) : ?>
            <div class="status-message">
                <?php echo htmlspecialchars($status_message); ?>
            </div>
        <?php endif; ?>

        <!-- 回報表單 -->
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="title">回報標題：</label><br>
            <input type="text" id="title" name="title" required><br><br>

            <label for="content">回報內容：</label><br>
            <textarea id="content" name="content" rows="5" required></textarea><br><br>

            <label for="file">上傳附件：</label><br>
            <input type="file" id="file" name="file"><br><br>

            <button type="submit">提交回報</button>
        </form>
    </div>
</body>

</html>