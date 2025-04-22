<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <title>審核提案</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
        }

        table th {
            background-color: #007BFF;
            color: #fff;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn.reject {
            background-color: #FF4D4D;
        }

        .btn.reject:hover {
            background-color: #CC0000;
        }
    </style>
</head>

<body>
    <!-- 左側導覽列 -->
    <div class="sidebar">
        <h2>管理系統</h2>
        <a href="../homepage.php">孵仁首頁</a>
        <a href="../manager/advice_manager.php">建言管理</a>
        <a href="assign_office.php">達標建言分配處所</a>
        <a href="review_proposals.php">審核</a>
        <a href="../manager/people_manager.php">人員處理</a>
        <a href="#">數據分析</a>
    </div>

    <!-- 頁面內容 -->
    <div class="content">
        <h1>審核提案</h1>
        <table>
            <thead>
                <tr>
                    <th>提案 ID</th>
                    <th>建言 ID</th>
                    <th>提案內容</th>
                    <th>資金金額</th>
                    <th>檔案</th>
                    <th>狀態</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // 資料庫連線
                $conn = new mysqli("localhost", "root", "", "system_project");
                if ($conn->connect_error) {
                    die("資料庫連線失敗: " . $conn->connect_error);
                }

                // 查詢所有待審核的提案
                $sql = "SELECT suggestion_assignments_id, advice_id, proposal_text, funding_amount, proposal_file_path, status 
                        FROM suggestion_assignments 
                        WHERE status = '審核中'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['suggestion_assignments_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['advice_id']) . "</td>";
                        echo "<td>" . nl2br(htmlspecialchars($row['proposal_text'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['funding_amount']) . "</td>";
                        echo "<td><a href='" . htmlspecialchars($row['proposal_file_path']) . "' download>下載檔案</a></td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "<td>
                                <form action='approve_proposal.php' method='POST'>
                                    <input type='hidden' name='suggestion_assignments_id' value='" . $row['suggestion_assignments_id'] . "'>
                                    <label for='admin_feedback'>回饋意見:</label>
                                    <textarea name='admin_feedback' id='admin_feedback' rows='3' placeholder='請輸入回饋意見...'></textarea>
                                    <button type='submit'>批准</button>
                                </form>
                                <form action='reject_proposal.php' method='POST' style='margin-top: 10px;'>
                                    <input type='hidden' name='suggestion_assignments_id' value='" . $row['suggestion_assignments_id'] . "'>
                                    <label for='admin_feedback'>回饋意見:</label>
                                    <textarea name='admin_feedback' id='admin_feedback' rows='3' placeholder='請輸入拒絕原因...'></textarea>
                                    <button type='submit' class='btn reject'>退回</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>目前沒有待審核的提案</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>