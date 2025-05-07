<?php
// filepath: c:\xampp\htdocs\analysis_project\includes\header.php

// 啟動 Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 確保使用者已登入
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// 獲取使用者資訊
$user_id = $_SESSION['user_id'];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "system_project";

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線
if ($conn->connect_error) {
    die("資料庫連線失敗：" . $conn->connect_error);
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
?>
<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理系統</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            background-color: #f4f6f9;
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

        .content {
            margin-left: 280px;
            padding: 20px;
            width: calc(100% - 280px);
        }

        .header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 10px 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>管理系統</h2>
        <a href="../homepage.php">孵仁首頁</a>
        <a href="../manager/advice_manager.php">建言管理</a>
        <a href="../manager/assign_office.php">達標建言分配處所</a>
        <a href="../manager/review_proposals.php">募資專案審核</a>
        <a href="../manager/review_extension_requests.php">延後募資申請審核</a>
        <a href="../manager/people_manager.php">人員處理</a>
        <a href="#">數據分析</a>
    </div>

    <div class="content">
        <div class="header">
            <div class="profile">
                <div class="profile-info" onclick="toggleDropdown()">
                    <img src="../img/logo.png" alt="頭像">
                    <p><?php echo htmlspecialchars($name); ?> - <?php echo htmlspecialchars($department); ?></p>
                </div>
                <div class="dropdown" id="dropdownMenu">
                    <a href="#">個人資料</a>
                    <a href="#">設定</a>
                    <a href="../logout.php">登出</a>
                </div>
            </div>
        </div>