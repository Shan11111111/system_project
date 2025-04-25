<?php
// 資料庫連線
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}

// 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = intval($_POST['request_id']);
    $admin_response = htmlspecialchars($_POST['admin_response']);
    $action = $_POST['action'];

    if ($action === 'accept') {
        $status = '已接受';
    } elseif ($action === 'reject') {
        $status = '已拒絕';
    } else {
        die("無效的操作。");
    }

    // 更新資料庫中的申請狀態
$stmt = $conn->prepare("UPDATE fundraising_extension_requests SET status = ?, admin_response = ? WHERE id = ?");
if (!$stmt) {
    die("SQL prepare failed: " . $conn->error);
}
$stmt->bind_param("ssi", $status, $admin_response, $request_id);

if ($stmt->execute()) {
    if ($action === 'accept') {
        // 更新募款專案的截止日
        $update_stmt = $conn->prepare(
"UPDATE fundraising_projects f SET f.status='進行中', f.end_date = (SELECT requested_extension_date FROM fundraising_extension_requests WHERE fundraising_project_id = ?) 
        WHERE project_id = (SELECT fundraising_project_id FROM fundraising_extension_requests WHERE id = ?)"
        );
        if (!$update_stmt) {
            die("SQL prepare failed: " . $conn->error);
        }
        $update_stmt->bind_param("ii", $request_id, $request_id);
        if ($update_stmt->execute()) {
            echo "<script>alert('申請已成功更新。');</script>";
        } else {
            echo "<script>alert('更新募款專案的截止日失敗: " . addslashes($conn->error) . "');</script>";
        }
        $update_stmt->close();
    } else {
        echo "<script>alert('申請已成功更新。');</script>";
    }
} else {
    echo "<script>alert('更新失敗: " . addslashes($conn->error) . "');</script>";
}

$stmt->close();

}

// 查詢待審核的延後申請
$sql = "SELECT fer.id, fp.title, fer.requested_extension_date, fer.created_at 
        FROM fundraising_extension_requests fer
        JOIN fundraising_projects fp ON fer.fundraising_project_id = fp.project_id
        WHERE fer.status = '待審核'";
$result = $conn->query($sql);

?>



<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>延期申請審核頁面</title>
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

        /* 頭部個人資料 */
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
            background-color:rgb(159, 193, 255);
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
            background-color:rgb(167, 185, 255);
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
    </style>
</head>

<body>
    <!-- 左側導覽列 -->
    <div class="sidebar">
        <h2>管理系統</h2>
        <a href="../homepage.php">孵仁首頁</a>
        <a href="advice_manager.php">建言管理</a>
        <a href="assign_office.php">達標建言分配處所</a>
        <a href="review_proposals.php">募資專案審核</a>
        <a href="project_manager.php">募資管理</a>
        <a href="review_extension_requests.php">延後募資申請審核</a>
        <a href="people_manager.php">人員處理</a>
        <a href="#">數據分析</a>
    </div>

    <!-- 頁面內容 -->
    <div class="content">
        <!-- 頭部 -->
        <div class="header">
            <div class="profile">
                <img src="../img/logo.png" alt="頭像" onclick="toggleDropdown()">
                <div class="dropdown" id="dropdownMenu">
                    <a href="#">個人資料</a>
                    <a href="#">設定</a>
                    <a href="#">登出</a>
                </div>
            </div>
        </div>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h3>專案名稱：" . htmlspecialchars($row['title']) . "</h3>";
        echo "<p>申請延後至：" . htmlspecialchars($row['requested_extension_date']) . "</p>";
        echo "<p>申請時間：" . htmlspecialchars($row['created_at']) . "</p>";
        echo "<form action='review_extension_requests.php' method='POST'>";
        echo "<input type='hidden' name='request_id' value='" . htmlspecialchars($row['id']) . "'>";
        echo "<textarea name='admin_response' placeholder='輸入回應...' required></textarea>";
        echo "<button type='submit' name='action' value='accept'>接受</button>";
        echo "<button type='submit' name='action' value='reject'>拒絕</button>";
        echo "</form>";
        echo "</div>";
    }
} else {
    echo "<p>目前沒有待審核的申請。</p>";
}

$conn->close();
?>