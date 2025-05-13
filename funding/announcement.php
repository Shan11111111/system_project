<?php
// 資料庫連線
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}
session_start();

// 確保使用者已登入
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
// 獲取使用者的 level
$user_level = $_SESSION['level'] ?? '';

// 處理刪除公告
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM announcement WHERE announcement_id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('公告已刪除！');</script>";
        echo "<script>window.location.href='announcement.php';</script>";
    } else {
        echo "<script>alert('刪除失敗：" . $conn->error . "');</script>";
    }
}

// 處理修改公告（填充表單）
$edit_id = null;
$edit_category = '';
$edit_title = '';
$edit_content = '';
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $stmt = $conn->prepare("SELECT * FROM announcement WHERE announcement_id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $edit_category = $row['category'];
        $edit_title = $row['title'];
        $edit_content = $row['content'];
    }
}




// 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'] ?? '';
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $file_path = null;

    // 驗證表單資料
    if (empty($category) || empty($title) || empty($content)) {
        die("錯誤：所有欄位均為必填。");
    }

    // 處理檔案上傳
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'file_upload/'; // 上傳目錄
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // 建立目錄（若不存在）
        }

        $file_name = basename($_FILES['file']['name']);
        $target_file = $upload_dir . uniqid() . '_' . $file_name;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $file_path = $target_file; // 儲存檔案路徑
        } else {
            die("檔案上傳失敗。");
        }
    }

    // 更新或插入公告資料
    if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
        // 更新公告
        $edit_id = intval($_POST['edit_id']);
        $stmt = $conn->prepare("UPDATE announcement SET title = ?, content = ?, category = ?, update_at = NOW(), file_path = ? WHERE announcement_id = ?");
        $stmt->bind_param("ssssi", $title, $content, $category, $file_path, $edit_id);
        if ($stmt->execute()) {
            echo "<script>alert('公告已成功更新！');</script>";
            echo "<script>window.location.href='announcement.php';</script>";
        } else {
            echo "<script>alert('公告更新失敗：" . $conn->error . "');</script>";
        }
    } else {
        // 插入新公告
        $stmt = $conn->prepare("INSERT INTO announcement (title, content, category, update_at, user_id, file_path) VALUES (?, ?, ?, NOW(), ?, ?)");
        $stmt->bind_param("sssis", $title, $content, $category, $user_id, $file_path);
        if ($stmt->execute()) {
            echo "<script>alert('公告已成功發布！');</script>";
            echo "<script>window.location.href='announcement.php';</script>";
        } else {
            echo "<script>alert('公告發布失敗：" . $conn->error . "');</script>";
        }
    }
}
// 分頁邏輯
$limit = 5; // 每頁顯示的公告數量
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // 當前頁數
$offset = ($page - 1) * $limit; // 計算偏移量

// 獲取總公告數量
$total_result = $conn->query("SELECT COUNT(*) AS total FROM announcement");
$total_row = $total_result->fetch_assoc();
$total_announcements = $total_row['total'];
$total_pages = ceil($total_announcements / $limit); // 計算總頁數

// 獲取當前頁的公告
$result = $conn->query("SELECT * FROM announcement ORDER BY update_at DESC LIMIT $limit OFFSET $offset");
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>發布公告</title>
    <style>
        /* 保留原本的 CSS */
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

        .content {
            margin-left: 280px;
            padding: 20px;
            width: calc(100% - 280px);
        }

        h1,
        h2 {
            color: #f9f9f9;
            text-align: center;
        }

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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .action-buttons a {
            margin-right: 10px;
            text-decoration: none;
            color: #007BFF;
        }

        .action-buttons a:hover {
            text-decoration: underline;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            margin: 0 5px;
            text-decoration: none;
            color: #007BFF;
            padding: 5px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .pagination a:hover {
            background-color: #007BFF;
            color: white;
        }

        .pagination .active {
            background-color: #007BFF;
            color: white;
            border: 1px solid #007BFF;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>管理系統</h2>
        <?php if ($user_level === 'office'): ?>
            <!-- Office 使用者的導覽列 -->
            <a href="../homepage.php">孵仁首頁</a>
            <a href="announcement.php">發布公告</a>
            <a href="adapt.php">自由認領達標建言區</a>
            <a href="office_assignments.php">提交提案與專案管理</a>
            <a href="office_apply_date.php">延後募款申請</a>
            <a href="funding_FAQ.php">募資常見問題</a>
            <a href="funding_return.php">募資進度回報</a>
            <a href="data">數據分析</a>
        <?php elseif ($user_level === 'manager'): ?>
            <!-- Manager 使用者的導覽列 -->
            <a href="../homepage.php">孵仁首頁</a>
            <a href="../manager/advice_manager.php">建言管理</a>
            <!-- <a href="../manager/assign_office.php">達標建言分配處所</a> -->
            <a href="../manager/review_proposals.php">募資專案審核</a>
            <a href="../manager/review_extension_requests.php">延後募資申請審核</a>
            <a href="../manager/people_manager.php">人員處理</a>
            <a href="../funding/announcement.php">發布公告</a>
            <a href="#">數據分析</a>
        <?php else: ?>
            <!-- 預設顯示 -->
            <p>您沒有權限訪問此頁面。</p>
        <?php endif; ?>
    </div>

    <div class="content">
        <h1 style="color: #333;">發布公告</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="edit_id" value="<?= $edit_id ?>">
            <label for="category">選擇類別：</label>
            <select id="category" name="category" required>
                <option value="">請選擇類別</option>
                <option value="建言" <?= $edit_category == '建言' ? 'selected' : '' ?>>建言</option>
                <option value="募資" <?= $edit_category == '募資' ? 'selected' : '' ?>>募資</option>
                <option value="系統" <?= $edit_category == '系統' ? 'selected' : '' ?>>系統</option>
            </select>

            <label for="title">公告標題：</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($edit_title) ?>" required>

            <label for="content">公告內容：</label>
            <textarea id="content" name="content" rows="5" required><?= htmlspecialchars($edit_content) ?></textarea>

            <label for="file">上傳檔案：</label>
            <input type="file" id="file" name="file" required>

            <button type="submit"><?= $edit_id ? '更新公告' : '發布公告' ?></button>
        </form>


        <h2 style="color: #333;">已發布公告</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>標題</th>
                    <th>內容</th>
                    <th>類別</th>
                    <th>發布時間</th>
                    <th>檔案</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php if ($row['user_id'] == $user_id): // 只顯示屬於當前使用者的公告 
                    ?>
                        <tr>
                            <td><?= $row['announcement_id'] ?></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars($row['content']) ?></td>
                            <td><?= htmlspecialchars($row['category']) ?></td>
                            <td><?= $row['update_at'] ?></td>
                            <td>
                                <?php if (!empty($row['file_path'])): ?>
                                    <?php
                                    $file_name = basename($row['file_path']);
                                    $original_file_name = explode('_', $file_name, 2)[1] ?? $file_name;
                                    ?>
                                    <a href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank"><?= htmlspecialchars($original_file_name) ?></a>
                                <?php else: ?>
                                    無
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="announcement.php?edit_id=<?= $row['announcement_id'] ?>">修改</a>
                                <a href="announcement.php?delete_id=<?= $row['announcement_id'] ?>" onclick="return confirm('確定要刪除嗎？');">刪除</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- 分頁功能 -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>">上一頁</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1 ?>">下一頁</a>
            <?php endif; ?>
        </div>

    </div>
</body>

</html>