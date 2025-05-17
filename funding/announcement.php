<?php
// 資料庫連線
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}
session_start();

// 確保使用者已登入
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
// 獲取使用者的 level
$user_level = $_SESSION['level'] ?? '';

// 處理刪除公告
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM announcement WHERE announcement_id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('公告已刪除！');</script>";
        echo "<script>window.location.href='announcement.php';</script>";
    } else {
        echo "<script>alert('刪除失敗：" . $conn->error . "');</script>";
    }
}

// 處理修改公告（填充表單）
$edit_id = null;
$edit_category = '';
$edit_title = '';
$edit_content = '';
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $stmt = $conn->prepare("SELECT * FROM announcement WHERE announcement_id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $edit_category = $row['category'];
        $edit_title = $row['title'];
        $edit_content = $row['content'];
    }
}




// 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'] ?? '';
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $file_path = null;

    // 驗證表單資料
    if (empty($category) || empty($title) || empty($content)) {
        die("錯誤：所有欄位均為必填。");
    }

    // 處理檔案上傳
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'file_upload/'; // 上傳目錄
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // 建立目錄（若不存在）
        }

        $file_name = basename($_FILES['file']['name']);
        $target_file = $upload_dir . uniqid() . '_' . $file_name;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $file_path = $target_file; // 儲存檔案路徑
        } else {
            die("檔案上傳失敗。");
        }
    }

    // 更新或插入公告資料
    if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
        // 更新公告
        $edit_id = intval($_POST['edit_id']);

        // 先查詢原本的 file_path
        $stmt = $conn->prepare("SELECT file_path FROM announcement WHERE announcement_id = ?");
        $stmt->bind_param("i", $edit_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $original_file_path = $row['file_path'];

        // 如果沒有新上傳檔案，保留原本的 file_path
        if (empty($file_path)) {
            $file_path = $original_file_path;
        }

        $stmt = $conn->prepare("UPDATE announcement SET title = ?, content = ?, category = ?, update_at = NOW(), file_path = ? WHERE announcement_id = ?");
        $stmt->bind_param("ssssi", $title, $content, $category, $file_path, $edit_id);
        if ($stmt->execute()) {
            echo "<script>alert('公告已成功更新！');</script>";
            echo "<script>window.location.href='announcement.php';</script>";
        } else {
            echo "<script>alert('公告更新失敗：" . $conn->error . "');</script>";
        }
    } else {
        // 插入新公告
        $stmt = $conn->prepare("INSERT INTO announcement (title, content, category, update_at, user_id, file_path) VALUES (?, ?, ?, NOW(), ?, ?)");
        $stmt->bind_param("sssis", $title, $content, $category, $user_id, $file_path);
        if ($stmt->execute()) {
            echo "<script>alert('公告已成功發布！');</script>";
            echo "<script>window.location.href='announcement.php';</script>";
        } else {
            echo "<script>alert('公告發布失敗：" . $conn->error . "');</script>";
        }
    }
}
//分頁功能
$limit = 5;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// 搜尋處理（如果未來要加）
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$search_condition = "";
if (!empty($search)) {
    $search_condition = "AND (title LIKE '%$search%' OR content LIKE '%$search%')";
}

// 根據權限選擇查詢條件
if ($user_level === 'manager') {
    $total_result = $conn->query("SELECT COUNT(*) AS total FROM announcement WHERE 1 $search_condition");
    $result = $conn->query("SELECT * FROM announcement WHERE 1 $search_condition ORDER BY update_at DESC LIMIT $limit OFFSET $offset");
} else {
    // office：只能看自己的
    $total_result = $conn->query("SELECT COUNT(*) AS total FROM announcement WHERE user_id = $user_id $search_condition");
    $result = $conn->query("SELECT * FROM announcement WHERE user_id = $user_id $search_condition ORDER BY update_at DESC LIMIT $limit OFFSET $offset");
}

$total_row = $total_result->fetch_assoc();
$total_announcements = $total_row['total'];
$total_pages = ceil($total_announcements / $limit);

// 獲取當前頁的公告
$result = $conn->query("SELECT * FROM announcement ORDER BY update_at DESC LIMIT $limit OFFSET $offset");
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>發布公告</title>
    <link rel="stylesheet" href="ano.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>
    <div class="sidebar">
        <h2>管理系統</h2>

        <?php if ($user_level === 'office'): ?>
            <!-- Office 使用者的導覽列 -->
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
        <?php elseif ($user_level === 'manager'): ?>
            <!-- Manager 使用者的導覽列 -->
            <a href="../homepage.php">孵仁首頁</a>
            <a href="../manager/advice_manager.php">建言管理</a>
            <!-- <a href="../manager/assign_office.php">達標建言分配處所</a> -->
            <a href="../manager/review_proposals.php">募資專案審核</a>
            <a href="../manager/review_extension_requests.php">延後募資申請審核</a>
            <a href="../manager/people_manager.php">人員處理</a>
            <a href="../funding/announcement.php">發布公告</a>
            <!-- <a href="#">數據分析</a> -->
        <?php else: ?>
            <!-- 預設顯示 -->
            <p>您沒有權限訪問此頁面。</p>
        <?php endif; ?>
    </div>

    <div class="content">
        <h1>公告功能</h1>
        <div class="tab-wrapper">
            <button class="tab-btn active" onclick="switchTab(this, 'publishForm')">發布公告</button>
            <button class="tab-btn" onclick="switchTab(this, 'announcementList')">已發布公告</button>
        </div>
        <div id="publishForm" class="tab-content" style="display: block;">

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="edit_id" value="<?= $edit_id ?>">
                <label for="category">選擇類別：</label>
                <select id="category" name="category" required>
                    <option value="">請選擇類別</option>
                    <option value="建言" <?= $edit_category == '建言' ? 'selected' : '' ?>>建言</option>
                    <option value="募資" <?= $edit_category == '募資' ? 'selected' : '' ?>>募資</option>
                    <option value="系統" <?= $edit_category == '系統' ? 'selected' : '' ?>>系統</option>
                </select>

                <label for="title">公告標題：</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($edit_title) ?>" required>

                <label for="content">公告內容：</label>
                <textarea id="content" name="content" rows="5" required><?= htmlspecialchars($edit_content) ?></textarea>

                <label for="file">上傳檔案：</label>
                <input type="file" id="file" name="file" >

                <button type="submit"><?= $edit_id ? '更新公告' : '發布公告' ?></button>
            </form>
        </div>
        <!-- 已發布公告區塊 -->
        <div id="announcementList" class="tab-content" style="display: none;">
            <!--
  <form class="search-bar" method="GET">
                <input
                    type="text"
                    name="search"
                    class="search-input"
                    placeholder="搜尋標題或內容…"
                    value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <button type="submit" class="search-btn">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        -->

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>標題</th>
                        <th>內容</th>
                        <th>類別</th>
                        <th>發布時間</th>
                        <th>檔案</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $hasData = false;
                    while ($row = $result->fetch_assoc()):
                        if ($row['user_id'] == $user_id):
                            $hasData = true;
                    ?>
                            <tr>
                                <td><?= $row['announcement_id'] ?></td>
                                <td>
                                    <span class="table-ellipsis"
                                        onclick="showFullText('<?= addslashes(htmlspecialchars($row['title'])) ?>', '<?= addslashes(htmlspecialchars($row['content'])) ?>')"
                                        title="點擊查看完整內容">
                                        <?= htmlspecialchars($row['title']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="table-ellipsis"
                                        onclick="showFullText('<?= addslashes(htmlspecialchars($row['title'])) ?>', '<?= addslashes(htmlspecialchars($row['content'])) ?>')"
                                        title="點擊查看完整內容">
                                        <?= htmlspecialchars($row['content']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($row['category']) ?></td>
                                <td><?= $row['update_at'] ?></td>
                                <td>
                                    <?php if (!empty($row['file_path'])): ?>
                                        <?php
                                        $file_name = basename($row['file_path']);
                                        $original_file_name = explode('_', $file_name, 2)[1] ?? $file_name;
                                        ?>
                                        <a href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank"><?= htmlspecialchars($original_file_name) ?></a>
                                    <?php else: ?>
                                        無
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="announcement.php?edit_id=<?= $row['announcement_id'] ?>">修改</a>
                                    <a href="announcement.php?delete_id=<?= $row['announcement_id'] ?>" onclick="return confirm('確定要刪除嗎？');">刪除</a>
                                </td>
                            </tr>
                        <?php
                        endif;
                    endwhile;
                    if (!$hasData):
                        ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">查無資料</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- 分頁功能 -->
            <?php
            $max_buttons = 5;
            $half = floor($max_buttons / 2);

            $start = max(1, $page - $half);
            $end = min($total_pages, $start + $max_buttons - 1);
            if ($end - $start + 1 < $max_buttons) {
                $start = max(1, $end - $max_buttons + 1);
            }

            $tab = 'announcementList';
            ?>

            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a class="page-link" href="announcement.php?tab=<?= $tab ?>&page=<?= $page - 1 ?>">&laquo; 上一頁</a>
                <?php endif; ?>

                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <a class="page-link <?= $i == $page ? 'active' : '' ?>" href="announcement.php?tab=<?= $tab ?>&page=<?= $i ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a class="page-link" href="announcement.php?tab=<?= $tab ?>&page=<?= $page + 1 ?>">下一頁 &raquo;</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
        function switchTab(clicked, tabId) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = 'none');

            if (clicked) clicked.classList.add('active');
            document.getElementById(tabId).style.display = 'block';
        }

        window.addEventListener('DOMContentLoaded', () => {
            const params = new URLSearchParams(window.location.search);
            const tab = params.get('tab');

            if (tab === 'announcementList') {
                // 觸發第二顆 tab 的按鈕（已發布公告）
                const secondTabBtn = document.querySelector('.tab-btn:nth-child(2)');
                switchTab(secondTabBtn, 'announcementList');
            } else {
                // 預設是第一顆 tab（發布公告）
                const firstTabBtn = document.querySelector('.tab-btn:nth-child(1)');
                switchTab(firstTabBtn, 'publishForm');
            }
        });

        function showFullText(title, content) {
            Swal.fire({
                title: '完整公告',
                html: `
      <div style="text-align:left; max-height:300px; overflow:auto;">
        <strong>標題：</strong>${title}<br><br>
        <strong>內容：</strong>${content}
      </div>
    `,
                confirmButtonText: '關閉'
            });
        }
    </script>

</body>

</html>