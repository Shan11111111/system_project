<?php
session_start()
    ?>

<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <title>審核提案</title>
    <style>
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


        /* 搜尋表單樣式 */
        .search-bar {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .search-bar input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .search-bar button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }

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
            /* margin-left: 280px; */
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

        /* 分頁樣式 */
        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            margin: 0 5px;
            padding: 10px 15px;
            text-decoration: none;
            background-color: #007BFF;
            color: #fff;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .pagination a.active {
            background-color: #0056b3;
            font-weight: bold;
        }

        .pagination a:hover {
            background-color: #0056b3;
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
        <a href="review_proposals.php">募資專案審核</a>
        <a href="review_extension_requests.php">延後募資申請審核</a>
        <a href="people_manager.php">人員處理</a>
        <a href="../funding/announcement.php">發布公告</a>
        <a href="#">數據分析</a>
    </div>
    <?php include 'header.php'; ?>

    <!-- 頁面內容 -->
    <div>

        <!-- <div class="profile" style="background-color: #fff; padding: 10px 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); margin-bottom: 20px; text-align: right;">
        <p style="margin: 0; font-size: 1.2em; font-weight: bold;">歡迎, <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
        
    </div> -->


        <h1>審核提案</h1>

        <!-- 搜尋欄 -->
        <div class="search-bar">
            <form action="review_proposals.php" method="GET">
                <input type="text" name="search" placeholder="搜尋提案..."
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit">搜尋</button>
            </form>
        </div>

        <!-- 表格 -->
        <table>
            <thead>
                <tr>
                    <th>提案編號</th>
                    <th>建言連結</th>
                    <th>提案內容</th>
                    <th>提案金額</th>
                    <th>企劃書</th>
                    <th>提案處所</th>
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

                // 搜尋條件
                $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

                // 分頁邏輯
                $limit = 4; // 每頁顯示 4 筆資料
                $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                $offset = ($page - 1) * $limit;

                // 查詢提案
                $sql = "SELECT department,suggestion_assignments_id, advice_id, proposal_text, funding_amount, proposal_file_path, status 
                        FROM suggestion_assignments join users on suggestion_assignments.office_id = users.user_id
                        WHERE status = '審核中'";

                if (!empty($search)) {
                    $sql .= " AND (proposal_text LIKE '%$search%' OR advice_id LIKE '%$search%')";
                }

                $sql .= " LIMIT $limit OFFSET $offset";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['suggestion_assignments_id']) . "</td>";
                        echo "<td><a href='../advice_detail.php?advice_id=" . urlencode($row['advice_id']) . "' style='text-decoration: none;'>" . htmlspecialchars($row['advice_id']) . "</a></td>";
                        echo "<td>" . nl2br(htmlspecialchars($row['proposal_text'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['funding_amount']) . "</td>";


                        if (!empty($row['proposal_file_path'])) {
                            $filePath = $row['proposal_file_path'];      // e.g., uploads/chicken.png
                            $fullPath = '../' . $filePath;               // 相對於 manager/ 資料夾
                            $safePath = htmlspecialchars($fullPath);     // 安全處理路徑
                            $fileName = basename($filePath);             // 取得檔案名稱（不含路徑）
                
                            if (file_exists($fullPath)) {
                                // 不管是圖片或文件，一律下載
                                echo "<td><a href='$safePath' download='$fileName' style='background-color:rgb(43, 61, 255); /* 綠色背景 */
        border: none;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        border-radius: 8px;
        transition: 0.3s;'>下載檔案</a></td>";
                            } else {
                                echo "<td>檔案不存在</td>";
                            }
                        } else {
                            echo "<td>無檔案</td>";
                        }



                        echo "<td>" . htmlspecialchars($row['department']) . "</td>";
                        echo "<td>
                                <form action='approve_proposal.php' method='POST'>
                                    <input type='hidden' name='suggestion_assignments_id' value='" . $row['suggestion_assignments_id'] . "'>
                                    <textarea name='admin_feedback' rows='3' placeholder='請輸入回饋意見...'></textarea>
                                    <button type='submit' class='btn'>批准</button>
                                </form>
                                <form action='reject_proposal.php' method='POST' style='margin-top: 10px;'>
                                    <input type='hidden' name='suggestion_assignments_id' value='" . $row['suggestion_assignments_id'] . "'>
                                    <textarea name='admin_feedback' rows='3' placeholder='請輸入拒絕原因...'></textarea>
                                    <button type='submit' class='btn reject'>退回</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>目前沒有待審核的提案</td></tr>";
                }

                // 計算總頁數
                $total_sql = "SELECT COUNT(*) AS total FROM suggestion_assignments WHERE status = '審核中'";
                if (!empty($search)) {
                    $total_sql .= " AND (proposal_text LIKE '%$search%' OR advice_id LIKE '%$search%')";
                }
                $total_result = $conn->query($total_sql);
                $total_row = $total_result->fetch_assoc();
                $total_pages = ceil($total_row['total'] / $limit);

                $conn->close();
                ?>
            </tbody>
        </table>

        <!-- 分頁 -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="review_proposals.php?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>"
                    class="<?php echo $i == $page ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>

        <!-- 已批准提案區塊 -->
        <h2 style="margin-top: 40px;">已批准的提案</h2>
        <table>
            <thead>
                <tr>
                    <th>提案編號</th>
                    <th>建言連結</th>
                    <th>提案內容</th>
                    <th>提案金額</th>
                    <th>企劃書</th>
                    <th>負責處所</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // 資料庫連線
                $conn = new mysqli("localhost", "root", "", "system_project");
                if ($conn->connect_error) {
                    die("資料庫連線失敗: " . $conn->connect_error);
                }

                // 搜尋條件
                $approved_search = isset($_GET['approved_search']) ? $conn->real_escape_string($_GET['approved_search']) : '';

                // 分頁邏輯
                $approved_limit = 4; // 每頁顯示 4 筆資料
                $approved_page = isset($_GET['approved_page']) ? intval($_GET['approved_page']) : 1;
                $approved_offset = ($approved_page - 1) * $approved_limit;

                // 查詢已批准的提案
                $approved_sql = "SELECT department,suggestion_assignments_id, advice_id, proposal_text, funding_amount, proposal_file_path, status 
                                 FROM suggestion_assignments join users on suggestion_assignments.office_id = users.user_id
                                 WHERE status = '已通過'";

                if (!empty($approved_search)) {
                    $approved_sql .= " AND (proposal_text LIKE '%$approved_search%' OR advice_id LIKE '%$approved_search%')";
                }

                $approved_sql .= " LIMIT $approved_limit OFFSET $approved_offset";
                $approved_result = $conn->query($approved_sql);

                if ($approved_result->num_rows > 0) {
                    while ($row = $approved_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['suggestion_assignments_id']) . "</td>";
                         echo "<td><a href='../advice_detail.php?advice_id=" . urlencode($row['advice_id']) . "' style='text-decoration: none;'>" . htmlspecialchars($row['advice_id']) . "</a></td>";
                        echo "<td>" . nl2br(htmlspecialchars($row['proposal_text'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['funding_amount']) . "</td>";

                        if (!empty($row['proposal_file_path'])) {
                            $filePath = $row['proposal_file_path'];      // e.g., uploads/chicken.png
                            $fullPath = '../' . $filePath;               // 相對於 manager/ 資料夾
                            $safePath = htmlspecialchars($fullPath);     // 安全處理路徑
                            $fileName = basename($filePath);             // 取得檔案名稱（不含路徑）
                
                            if (file_exists($fullPath)) {
                                // 不管是圖片或文件，一律下載
                                echo "<td><a href='$safePath' download='$fileName' style='background-color:rgb(43, 61, 255); /* 綠色背景 */
        border: none;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        border-radius: 8px;
        transition: 0.3s;'>下載檔案</a></td>";
                            } else {
                                echo "<td>檔案不存在</td>";
                            }
                        } else {
                            echo "<td>無檔案</td>";
                        }

                        echo "<td>" . htmlspecialchars($row['department']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>目前沒有已批准的提案</td></tr>";
                }

                // 計算總頁數
                $approved_total_sql = "SELECT COUNT(*) AS total FROM suggestion_assignments WHERE status = '已通過'";
                if (!empty($approved_search)) {
                    $approved_total_sql .= " AND (proposal_text LIKE '%$approved_search%' OR advice_id LIKE '%$approved_search%')";
                }
                $approved_total_result = $conn->query($approved_total_sql);
                $approved_total_row = $approved_total_result->fetch_assoc();
                $approved_total_pages = ceil($approved_total_row['total'] / $approved_limit);

                $conn->close();
                ?>
            </tbody>
        </table>

        <!-- 已批准提案分頁 -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $approved_total_pages; $i++): ?>
                <a href="review_proposals.php?approved_page=<?php echo $i; ?>&approved_search=<?php echo htmlspecialchars($approved_search); ?>"
                    class="<?php echo $i == $approved_page ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</body>

</html>