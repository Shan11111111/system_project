<?php

session_start();

// 資料庫連線
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}

// 分頁參數
$limit = 6;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// 查詢待審核的延後申請
$sql = "SELECT fer.id, fp.title, fer.requested_extension_date, fer.created_at
        FROM fundraising_extension_requests fer
        JOIN fundraising_projects fp ON fer.fundraising_project_id = fp.project_id
        WHERE fer.status = '待審核'
        LIMIT $limit OFFSET $offset";
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

        .sidebar {
            width: 250px;
            background-color: #007BFF;
            color: #fff;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
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
            width: calc(100% - 250px);
        }

        .tab-bar {
            display: flex;
            margin-bottom: 20px;
        }

        .tab-btn {
            padding: 10px 20px;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            font-size: 16px;
            cursor: pointer;
        }

        .tab-btn.active {
            border-color: #007BFF;
            color: #007BFF;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .card {
            background: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 15px;
        }

        .card h3 {
            margin: 0 0 10px;
            color: #007BFF;
        }

        .card textarea {
            width: 100%;
            margin: 10px 0;
            resize: vertical;
        }

        .card button {
            margin-right: 10px;
        }

        .table-ellipsis {
            max-width: 200px;
            /* 可依實際需求調整 */
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            display: inline-block;
            vertical-align: middle;
            text-align: left;
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

        thead {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #d1e0ff;
        }

        .pagination {
            text-align: center;
            margin: 20px 0;
        }

        .pagination a {
            padding: 8px 12px;
            margin: 0 5px;
            background: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }

        .pagination a.active {
            background: #0056b3;
            pointer-events: none;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>管理系統</h2>
        <a href="../homepage.php">孵仁首頁</a>
        <a href="advice_manager.php">建言管理</a>
        <a href="review_proposals.php">募資專案審核</a>
        <a href="review_extension_requests.php">延後募資申請審核</a>
        <a href="people_manager.php">人員處理</a>
        <a href="../funding/announcement.php">發布公告</a>
    </div>

    <div class="content">
        <div class="tab-bar">
            <button class="tab-btn active" data-target="#tab-pending">待審核申請</button>
            <button class="tab-btn" data-target="#tab-approved">已批准紀錄</button>
        </div>

        <div id="tab-pending" class="tab-content active">
            <div class="card-grid">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='card'>";
                        echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                        echo "<p>申請延後至：" . htmlspecialchars($row['requested_extension_date']) . "</p>";
                        echo "<p>申請時間：" . htmlspecialchars($row['created_at']) . "</p>";
                        echo "<form action='apply_date.php' method='POST'>";
                        echo "<input type='hidden' name='request_id' value='" . htmlspecialchars($row['id']) . "'>";
                        echo "<input type='hidden' name='end_date' value='" . htmlspecialchars($row['requested_extension_date']) . "'>";
                        echo "<textarea name='admin_response' placeholder='輸入回應...' required></textarea>";
                        echo "<button type='submit' name='action' value='accept'>接受</button>";
                        echo "<button type='submit' name='action' value='reject'>拒絕</button>";
                        echo "</form>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>目前沒有待審核的申請。</p>";
                }
                ?>
            </div>

            <?php
            $total_sql = "SELECT COUNT(*) AS total FROM fundraising_extension_requests WHERE status = '待審核'";
            $total_result = $conn->query($total_sql);
            $total_row = $total_result->fetch_assoc();
            $total_pages = ceil($total_row['total'] / $limit);

            echo '<div class="pagination">';
            for ($i = 1; $i <= $total_pages; $i++) {
                $active = ($i == $page) ? "active" : "";
                echo "<a href='review_extension_requests.php?page=$i' class='$active'>$i</a> ";
            }
            echo '</div>';

            $conn->close();
            ?>
        </div>
        <!-- 已批准的延期歷史紀錄 -->
        <div id="tab-approved" class="tab-content">
            <h2 style="margin-top: 0;">已批准的延期歷史紀錄</h2>
            <table>
                <thead>
                    <tr>
                        <th>申請 ID</th>
                        <th>專案名稱</th>
                        <th>延後至</th>
                        <th>審核時間</th>
                        <th>管理員回應</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // 資料庫連線
                    $conn = new mysqli("localhost", "root", "", "system_project");
                    if ($conn->connect_error) {
                        die("資料庫連線失敗: " . $conn->connect_error);
                    }

                    $approved_search = isset($_GET['approved_search']) ? $conn->real_escape_string($_GET['approved_search']) : '';
                    $approved_limit = 6;
                    $approved_page = isset($_GET['approved_page']) ? intval($_GET['approved_page']) : 1;
                    $approved_offset = ($approved_page - 1) * $approved_limit;

                    $approved_sql = "SELECT fer.id, fp.title, fer.requested_extension_date, fer.created_at, fer.admin_response 
                            FROM fundraising_extension_requests fer
                            JOIN fundraising_projects fp ON fer.fundraising_project_id = fp.project_id
                            WHERE fer.status = '已接受'";

                    if (!empty($approved_search)) {
                        $approved_sql .= " AND (fp.title LIKE '%$approved_search%' OR fer.admin_response LIKE '%$approved_search%')";
                    }

                    $approved_sql .= " LIMIT $approved_limit OFFSET $approved_offset";
                    $approved_result = $conn->query($approved_sql);

                    if ($approved_result->num_rows > 0) {
                        while ($row = $approved_result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['requested_extension_date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                            echo "<td>" . nl2br(htmlspecialchars($row['admin_response'])) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>目前沒有已批准的延期紀錄。</td></tr>";
                    }

                    $approved_total_sql = "SELECT COUNT(*) AS total FROM fundraising_extension_requests fer
                                JOIN fundraising_projects fp ON fer.fundraising_project_id = fp.project_id
                                WHERE fer.status = '已接受'";
                    if (!empty($approved_search)) {
                        $approved_total_sql .= " AND (fp.title LIKE '%$approved_search%' OR fer.admin_response LIKE '%$approved_search%')";
                    }
                    $approved_total_result = $conn->query($approved_total_sql);
                    $approved_total_row = $approved_total_result->fetch_assoc();
                    $approved_total_pages = ceil($approved_total_row['total'] / $approved_limit);
                    ?>
                </tbody>
            </table>

            <div class="pagination">
                <?php for ($i = 1; $i <= $approved_total_pages; $i++): ?>
                    <a href="review_extension_requests.php?approved_page=<?php echo $i; ?>&approved_search=<?php echo htmlspecialchars($approved_search); ?>"
                        class="<?php echo $i == $approved_page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', () => {
                // 移除所有按鈕的 active 狀態
                document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
                // 隱藏所有 tab-content
                document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));

                // 加入目前按鈕與對應內容的 active 狀態
                button.classList.add('active');
                const target = button.getAttribute('data-target');
                document.querySelector(target).classList.add('active');
            });
        });
    </script>

</body>

</html>