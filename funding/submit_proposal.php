<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <title>提交提案</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
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
            padding: 40px;
            background-color: #f4f6f9;
            min-height: 100vh;
        }

        .content h1 {
            font-size: 2em;
            color: #333;
            margin-bottom: 20px;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        .form-container label {
            font-size: 1.1em;
            color: #333;
            display: block;
            margin-bottom: 10px;
        }

        .form-container textarea,
        .form-container input[type="text"],
        .form-container input[type="file"] {
            width: 100%;
            padding: 12px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        .form-container button {
            padding: 12px 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .form-container .note {
            font-size: 0.9em;
            color: #666;
            margin-top: -15px;
            margin-bottom: 20px;
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

    <!-- 頁面內容 -->
    <div class="content">
        <h1>提交提案</h1>
        <div class="form-container">
            <form action="process_proposal.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="suggestion_assignments_id" value="<?php echo htmlspecialchars($_GET['suggestion_assignments_id']); ?>">
                <label for="proposal_text">提案內容:</label>
                <textarea name="proposal_text" id="proposal_text" rows="5" required><?php
                    // 如果是重新提交，預填舊的提案內容
                    $conn = new mysqli("localhost", "root", "", "system_project");
                    $suggestion_assignments_id = intval($_GET['suggestion_assignments_id']);
                    $sql = "SELECT proposal_text FROM suggestion_assignments WHERE suggestion_assignments_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $suggestion_assignments_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo htmlspecialchars($row['proposal_text']);
                    }
                    $stmt->close();
                    $conn->close();
                ?></textarea>
                <label for="funding_amount">資金金額:</label>
                <input type="text" name="funding_amount" id="funding_amount" placeholder="請輸入資金金額..." required>
                <label for="proposal_file">上傳提案文件:</label>
                <input type="file" name="proposal_file" id="proposal_file" required>
                <span class="note">檔案格式限制：PDF、Word 或圖片檔案</span>
                <button type="submit">提交提案</button>
            </form>
        </div>
    </div>
</body>

</html>