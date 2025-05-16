<?php
session_start();
// // 檢查是否已登入
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }
// // 檢查使用者權限
// if ($_SESSION['user_role'] !== 'manager') {
//     echo "您沒有權限訪問此頁面。";
//     header("Location: ../homepage.php");
//     exit();
// }


// 資料庫連線設定
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "system_project"; // 替換為您的資料庫名稱

// 建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 處理篩選條件
$filter_level = isset($_GET['level']) ? $_GET['level'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// 分頁邏輯
$limit = 10; // 每頁顯示 10 筆資料
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// 動態生成 SQL 查詢
$sql = "SELECT user_id, name, level, email, department FROM users WHERE level != 'manager'";
if (!empty($filter_level)) {
    $sql .= " AND level = '" . $conn->real_escape_string($filter_level) . "'";
}
if (!empty($search_query)) {
    $sql .= " AND (name LIKE '%" . $conn->real_escape_string($search_query) . "%' 
                OR email LIKE '%" . $conn->real_escape_string($search_query) . "%' 
                OR department LIKE '%" . $conn->real_escape_string($search_query) . "%'
                OR user_id LIKE '%" . $conn->real_escape_string($search_query) . "%')";
}
$sql .= " LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// 計算總頁數
$total_sql = "SELECT COUNT(*) AS total FROM users WHERE level != 'manager'";
if (!empty($filter_level)) {
    $total_sql .= " AND level = '" . $conn->real_escape_string($filter_level) . "'";
}
if (!empty($search_query)) {
    $total_sql .= " AND (name LIKE '%" . $conn->real_escape_string($search_query) . "%' 
                    OR email LIKE '%" . $conn->real_escape_string($search_query) . "%' 
                    OR department LIKE '%" . $conn->real_escape_string($search_query) . "%'
                    OR user_id LIKE '%" . $conn->real_escape_string($search_query) . "%')";
}
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_pages = ceil($total_row['total'] / $limit);
?>

<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>人員管理頁面</title>
    <link rel="stylesheet" href="css\adv_manager.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- 左側導覽列 -->
    <div class="sidebar">
        <h2>管理系統</h2>
        <a href="../homepage.php">孵仁首頁</a>
        <a href="advice_manager.php">建言管理</a>
        <!-- <a href="assign_office.php">達標建言分配處所</a> -->
        <a href="review_proposals.php">募資專案審核</a>
        <!-- <a href="project_manager.php">募資管理</a> -->
        <a href="review_extension_requests.php">延後募資申請審核</a>
        <a href="people_manager.php">人員處理</a>
        <a href="../funding/announcement.php">發布公告</a>
        <!-- <a href="#">數據分析</a> -->
    </div>


    <!-- 頁面內容 -->
    <div class="content">

        <!-- 篩選表單 -->
        <h1>人員管理頁面</h1>
        <form method="GET" action="people_manager.php" class="filter-form">
            <div class="filter-left">
                <!-- 查詢欄 -->
                <div class="form-group">
                    <input type="text" name="search" id="search" placeholder="查詢:輸入學號、名字、email或科系" value="<?= htmlspecialchars($search_query) ?>">
                </div>

                <!-- 身分欄 -->
                <div class="form-group">
                    <select name="level" id="level">
                        <option value="">篩選身分:全部</option>
                        <option value="student" <?= $filter_level == 'student' ? 'selected' : '' ?>>學生</option>
                        <option value="teacher" <?= $filter_level == 'teacher' ? 'selected' : '' ?>>教職員</option>
                        <option value="office" <?= $filter_level == 'office' ? 'selected' : '' ?>>處所負責人</option>
                    </select>
                </div>
                <!-- 篩選按鈕 -->
                <div class="form-group">
                    <button type="submit">篩選</button>
                </div>
            </div>
            <button type="button" onclick="openAddUserModal()">📝 新增人員</button>
        </form>

        <!-- 表格內容 -->
        <table>
            <thead>
                <tr>
                    <th>學號ID/教職員編號</th>
                    <th>名字</th>
                    <th>科系</th>
                    <th>email</th>
                    <th>身分</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['user_id'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['department']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . $row['level'] . "</td>";
                        echo "<td>";
                        echo "<form action='delete_people.php' method='POST' style='display:inline-block; margin-left:10px;'>";
                        echo "<input type='hidden' name='user_id' value='" . $row['user_id'] . "'>";
                        echo "<button type='submit' onclick='return confirm(\"確定要刪除這個使用者嗎？\");'>刪除</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>沒有符合條件的使用者</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- 分頁 -->
        <div class="pagination">
            <?php
            $max_buttons = 5;
            $half = floor($max_buttons / 2);
            $start = max(1, $page - $half);
            $end = min($total_pages, $start + $max_buttons - 1);
            if ($end - $start + 1 < $max_buttons) {
                $start = max(1, $end - $max_buttons + 1);
            }

            // 上一頁
            if ($page > 1) {
                echo "<a href='people_manager.php?page=" . ($page - 1) . "&level=" . urlencode($filter_level) . "&search=" . urlencode($search_query) . "' class='page-link'>&laquo; 上一頁</a>";
            }

            // 頁碼
            for ($i = $start; $i <= $end; $i++) {
                $active = ($i == $page) ? 'active' : '';
                echo "<a href='people_manager.php?page=$i&level=" . urlencode($filter_level) . "&search=" . urlencode($search_query) . "' class='page-link $active'>$i</a>";
            }

            // 下一頁
            if ($page < $total_pages) {
                echo "<a href='people_manager.php?page=" . ($page + 1) . "&level=" . urlencode($filter_level) . "&search=" . urlencode($search_query) . "' class='page-link'>下一頁 &raquo;</a>";
            }
            ?>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        // 點擊其他地方關閉下拉選單
        window.onclick = function(event) {
            const dropdown = document.getElementById('dropdownMenu');
            if (!event.target.matches('.profile img')) {
                dropdown.style.display = 'none';
            }
        }

        function openAddUserModal() {
            Swal.fire({
                title: '新增人員',
                html: `<input id="userId" class="swal2-input" placeholder="學號 / 教職員編號">
       <input id="name" class="swal2-input" placeholder="姓名">
       <input id="email" class="swal2-input" placeholder="Email">
       <input id="department" class="swal2-input" placeholder="科系">
       <select id="level" class="swal2-select">
         <option value="disabled selected">選擇身分</option>
         <option value="student">學生</option>
         <option value="teacher">教職員</option>
         <option value="office">處所負責人</option>
         <option value="manager">管理員</option>

       </select>`,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: '送出',
                cancelButtonText: '取消',
                preConfirm: () => {
                    const data = {
                        userId: document.getElementById('userId').value,
                        name: document.getElementById('name').value,
                        email: document.getElementById('email').value,
                        department: document.getElementById('department').value,
                        level: document.getElementById('level').value
                    };
                    if (!data.userId || !data.name || !data.email || !data.department || !data.level) {
                        Swal.showValidationMessage('請填寫所有欄位');
                        return false;
                    }

                    // 這裡可以送 AJAX 請求到後端
                    fetch('add_user.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(data)
                        })
                        .then(res => res.json())
                        .then(response => {
                            if (response.success) {
                                Swal.fire('成功', '人員已新增', 'success').then(() => location.reload());
                            } else {
                                Swal.fire('錯誤', response.message || '新增失敗', 'error');
                            }
                        });
                }
            });
        }
    </script>

</body>

</html>
<?php
$conn->close();
?>