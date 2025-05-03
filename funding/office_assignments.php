<?php
                // 資料庫連線
                $conn = new mysqli("localhost", "root", "", "system_project");
                if ($conn->connect_error) {
                    die("資料庫連線失敗: " . $conn->connect_error);
                }

                // 假設 office_id 是從 session 中取得
                session_start();
                $office_id = $_SESSION['user_id'];

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
        <a href="../funding/office_assignments.php">提交提案</a>
        <a href="office_apply_date.php">延後募款申請</a>
        <a href="funding_FAQ.php">募資常見問題</a>
        <a href="funding_return.php">募資進度回報</a>
    </div>

    <div class="content">
        <h1>處所分派建言</h1>
        <div class="search-bar">
    <form action="office_assignments.php" method="GET">
        <input type="text" name="search" placeholder="輸入建言 ID 或標題進行搜尋" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit">搜尋</button>
    </form>
</div>
        <table>
            <thead>
                <tr>
                    <th>建言 ID</th>
                    <th>建言標題</th>
                    <th>狀態</th>
                    <th>操作</th>
                    <th>回覆</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
$search=isset($_GET['search']) ? $_GET['search'] : '';
                // 查詢分派給該處所的建言
                $sql = "SELECT sa.suggestion_assignments_id, sa.advice_id, a.advice_title, sa.status, sa.notification, sa.admin_feedback 
                        FROM suggestion_assignments sa
                        JOIN advice a ON sa.advice_id = a.advice_id
                        WHERE sa.office_id = ?";

                if (!empty($search)) {
                    // 如果搜尋條件是數字，則搜尋建言 ID，否則搜尋建言標題
                    if (is_numeric($search)) {
                        $sql .= " AND sa.advice_id = ?";
                    } else {
                        $sql .= " AND a.advice_title LIKE ?";
                    }
                }

                $stmt = $conn->prepare($sql);

                if (!empty($search)) {
                    if (is_numeric($search)) {
                        $stmt->bind_param("ii", $office_id, $search);
                    } else {
                        $search_param = '%' . $search . '%';
                        $stmt->bind_param("is", $office_id, $search_param);
                    }
                } else {
                    $stmt->bind_param("i", $office_id);
                }

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['advice_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['advice_title']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "<td>";
                        if ($row['notification']) {
                            echo "<span style='color: red;'>有新的審核結果</span>";
                            echo " <a class='btn' href='reset_notification.php?suggestion_assignments_id=" . $row['suggestion_assignments_id'] . "'>查看通知</a>";
                        }
                        if ($row['status'] === '草擬中') {
                            echo " <a class='btn' href='submit_proposal.php?suggestion_assignments_id=" . $row['suggestion_assignments_id'] . "'>提交提案</a>";
                        } elseif ($row['status'] === '被退回') {
                            echo " <a class='btn' href='submit_proposal.php?suggestion_assignments_id=" . $row['suggestion_assignments_id'] . "'>重新提交</a>";
                        } elseif ($row['status'] === '審核中') {
                            echo " <span>等待審核</span>";
                        } elseif ($row['status'] === '已通過') {
                            echo " <span>已通過</span>";
                        }
                        if (!empty($row['admin_feedback'])) {
                            echo "<p><strong>管理者回饋:</strong> " . htmlspecialchars($row['admin_feedback']) . "</p>";
                        }
                        echo "</td>";

                        // 新增回覆表單
                        echo "<td>";
                        echo "<form action='submit_reply.php' method='POST'>";
                        echo "<input type='hidden' name='suggestion_assignments_id' value='" . $row['suggestion_assignments_id'] . "'>";
                        echo "<textarea name='reply_text' rows='3' placeholder='輸入回覆內容...' required></textarea>";
                        echo "<button type='submit'>提交回覆</button>";
                        echo "</form>";

                        // 顯示回覆紀錄
                        echo "<div class='reply-records'>";
                        echo "<h4 onclick='toggleReplyRecords(this)' style='cursor: pointer; color: #007BFF;'>回覆紀錄"."<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-caret-down-fill' viewBox='0 0 16 16'>
                        <path d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/>
                      </svg>"."</h4>";
                        echo "<div class='reply-content' style='display: none;'>"; // 默認隱藏
                        $reply_sql = "SELECT reply_text, replied_at FROM replies WHERE suggestion_assignments_id = ?";
                        $reply_stmt = $conn->prepare($reply_sql);
                        $reply_stmt->bind_param("i", $row['suggestion_assignments_id']);
                        $reply_stmt->execute();
                        $reply_result = $reply_stmt->get_result();

                        if ($reply_result->num_rows > 0) {
                            echo "<ul>";
                            while ($reply_row = $reply_result->fetch_assoc()) {
                                echo "<li>" . htmlspecialchars($reply_row['reply_text']) . " - " . htmlspecialchars($reply_row['replied_at']) . "</li>";
                            }
                            echo "</ul>";
                        } else {
                            echo "<p>尚無回覆</p>";
                        }
                        $reply_stmt->close();
                        echo "</div>"; // 關閉 reply-content
                        echo "</div>"; // 關閉 reply-records

                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>目前沒有分派的建言</td></tr>";
                }

                $stmt->close();
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    <script>
        function toggleReplyRecords(element) {
            const replyContent = element.nextElementSibling;
            if (replyContent.style.display === "none" || replyContent.style.display === "") {
                replyContent.style.display = "block"; // 展開
            } else {
                replyContent.style.display = "none"; // 收起
            }
        }
    </script>
</body>

</html>