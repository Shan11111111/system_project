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

        /* 搜尋表單樣式 */
        .search-bar {
            margin-bottom: 20px;
        }

        .search-bar input[type="text"] {
            padding: 8px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-bar button {
            padding: 8px 12px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #0056b3;
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

    <div style="margin-left: 300px; padding: 20px;">
        <!-- 這裡放專案卡片列表 -->


        <?php

        echo "<h1>已過期的募資專案</h1>";

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div style='margin-bottom: 20px; padding: 20px; border: 1px solid #ddd; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);'>";
                echo "<h3>📌 專案名稱：<span style='color: #007bff;'>" . htmlspecialchars($row['title']) . "</span></h3>";
                echo "<p>📅 截止日期：" . htmlspecialchars($row['end_date']) . "</p>";

                // 這裡查詢該專案是否有"待審查"的延期申請
                $projectId = $row['project_id'];
                $extensionSql = "SELECT * FROM fundraising_extension_requests WHERE fundraising_project_id = $projectId AND status = '待審核' LIMIT 1";
                $extensionResult = $conn->query($extensionSql);

                if ($extensionResult->num_rows > 0) {
                    $extensionRow = $extensionResult->fetch_assoc();
                    echo "<p>⏳ 已申請延期至：" . htmlspecialchars($extensionRow['requested_extension_date']) . "</p>";

                    // 顯示「已提交」按鈕，不能按
                    echo "<button type='button' disabled style='background-color: #6c757d; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: not-allowed;'>已提交</button>";

                    // 顯示「取消申請」按鈕
                    echo "<form action='' method='POST' style='display:inline; margin-left:10px;'>";
                    echo "<input type='hidden' name='cancel_request_id' value='" . $extensionRow['id'] . "'>";
                    echo "<button type='submit' style='background-color: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 5px;'>取消申請</button>";
                    echo "</form>";
                } else {
                    // 沒有 待審查 申請，顯示可填寫的表單
                    echo "<form action='' method='POST'>";
                    echo "<input type='hidden' name='fundraising_project_id' value='" . $row['project_id'] . "'>";
                    echo "<label for='requested_extension_date'>申請延後至：</label>";
                    echo "<input type='date' name='requested_extension_date' required>";
                    echo "<button type='submit' style='background-color: #007bff; color: white; border: none; padding: 8px 16px; border-radius: 5px;'>提交申請</button>";
                    echo "</form>";
                }

                echo "</div>";
            }
        } else {
            echo "<p>目前沒有已過期的募資專案。</p>";
        }

        if (isset($_POST['cancel_request_id'])) {
            $cancelRequestId = intval($_POST['cancel_request_id']);
            $deleteSql = "DELETE FROM fundraising_extension_requests WHERE id = $cancelRequestId";

           ?>
            <?php
            if ($conn->query($deleteSql) === TRUE) {
                echo "<script>alert('申請已取消'); window.location.href='office_apply_date.php';</script>";
            } else {
                echo "<script>alert('取消申請失敗，請稍後再試'); window.location.href='office_apply_date.php';</script>";
            }
        }
        ?>



    </div>

    <?php
    $stmt->close();

    // 處理申請邏輯
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fundraising_project_id = intval($_POST['fundraising_project_id']);
        $requested_extension_date = $_POST['requested_extension_date'];

        // 插入申請到 fundraising_extension_requests 表
        $insert_sql = "INSERT INTO fundraising_extension_requests (fundraising_project_id, requested_by_office_id, requested_extension_date,status) 
                   VALUES (?, ?, ?, '待審核')";
        $insert_stmt = $conn->prepare($insert_sql);
        if (!$insert_stmt) {
            die("SQL 錯誤: " . $conn->error);
        }

        $insert_stmt->bind_param("iis", $fundraising_project_id, $office_id, $requested_extension_date);
        if ($insert_stmt->execute()) {
            echo "<script>alert('延後募款截止日申請已提交，等待管理者審核'); window.location.href='office_apply_date.php';</script>";
        } else {
            echo "<script>alert('申請提交失敗，請稍後再試'); window.location.href='office_apply_date.php';</script>";
        }

        $insert_stmt->close();
    }

    $conn->close();
    ?>