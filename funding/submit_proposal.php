<?php
session_start();
?>
<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <title>提交提案</title>
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
            padding: 40px;
            background-color: var(--color-cream);
            min-height: 100vh;
        }

        .content h1 {
            font-size: 2em;
            color: var(---color-dark-brown);
            margin-bottom: 20px;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 90%;
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
            background-color: var(--color-orange);
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .form-container button:hover {
            background-color: var(--color-orange-brown);
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
        <h2>行政專區</h2>
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

    <!-- 頁面內容 -->
    <div class="content">
        <h1 style="color: #7c4d2b;">提交提案</h1>
        <div class="form-container">
            <form action="process_proposal.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="suggestion_assignments_id"
                    value="<?php echo htmlspecialchars($_GET['suggestion_assignments_id']); ?>">
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