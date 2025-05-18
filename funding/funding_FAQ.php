<?php
// 資料庫連線
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}

// 假設 user_id 是從 session 中取得
session_start();
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    die("錯誤：您尚未登入，請先登入系統。");
}
$office_id = $_SESSION['user_id'];

// 新增或編輯常見問題
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $faq_id = isset($_POST['faq_id']) ? intval($_POST['faq_id']) : null;
    // 這裡優先用搜尋欄的 project_id
    $project_id = null;
    if (!empty($_POST['project_search'])) {
        $project_id = intval($_POST['project_search']);
    } else if (!empty($_POST['project_id'])) {
        $project_id = intval($_POST['project_id']);
    }
    $question = $_POST['question'];
    $reply = $_POST['reply'];

    if ($faq_id) {
        // 更新常見問題
        $stmt = $conn->prepare("UPDATE funding_faq SET project_id = ?, question = ?, reply = ?, updated_on = NOW() WHERE funding_FAQ_id = ?");
        $stmt->bind_param("issi", $project_id, $question, $reply, $faq_id);
    } else {
        // 新增常見問題
        $stmt = $conn->prepare("INSERT INTO funding_FAQ (project_id, user_id, question, reply, updated_on) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiss", $project_id, $office_id, $question, $reply);
    }

    if (!$stmt->execute()) {
        die("執行語句失敗: " . $stmt->error);
    }
    $stmt->close();
    header("Location: funding_FAQ.php");
    exit();
}

// 刪除常見問題
if (isset($_GET['delete'])) {
    $faq_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM funding_FAQ WHERE funding_FAQ_id = ?");
    $stmt->bind_param("i", $faq_id);
    $stmt->execute();
    $stmt->close();
    header("Location: funding_FAQ.php");
    exit();
}

// 設定每頁顯示的常見問題數量
$limit = 5; // 每頁顯示 5 筆
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // 當前頁數
$offset = ($page - 1) * $limit; // 計算偏移量

// 計算總常見問題數量
$count_sql = "SELECT COUNT(*) AS total FROM funding_FAQ WHERE user_id = ?";
$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param("i", $office_id);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit); // 計算總頁數
$count_stmt->close();

// 查詢當前頁的常見問題
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT funding_FAQ_id, project_id, question, reply, updated_on FROM funding_FAQ WHERE user_id = ?";
if (!empty($search)) {
    $sql .= " AND (funding_FAQ_id LIKE ? OR project_id LIKE ? OR question LIKE ? OR reply LIKE ?)";
}
$sql .= " LIMIT ? OFFSET ?"; // 加入分頁的限制條件
$stmt = $conn->prepare($sql);
if (!empty($search)) {
    $search_param = '%' . $search . '%';
    $stmt->bind_param("issssii", $office_id, $search_param, $search_param, $search_param, $search_param, $limit, $offset);
} else {
    $stmt->bind_param("iii", $office_id, $limit, $offset);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <title>募資常見問題</title>
    <!-- cdn link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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

        .content {
            margin-left: 280px;
            padding: 20px;
            width: calc(100% - 320px);
        }

        .tabs {
            display: flex;
            border-bottom: 1px solid #ccc;
            background-color: #fff8ec;
            width: 100%;
            margin-bottom: 25px;
        }

        .tab {
            flex: 1;
            /* 每個 tab 平均分配寬度 */
            text-align: center;
            padding: 14px 0;
            cursor: pointer;
            font-weight: bold;
            color: #999;
            background-color: transparent;
            border: none;
            position: relative;
            border-radius: 12px 12px 0 0;
            transition: background-color 0.3s, color 0.3s;
        }

        /* 選取中 */
        .tab.active {
            background-color: #fff5dd;
            color: #5c3a00;
        }

        /* 下底線，佔滿 tab 寬度 */
        .tab.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background-color: var(--color-orange);
        }

        /* hover 效果 */
        .tab:hover:not(.active) {
            background-color: #fff0d6;
            color: #666;
        }


        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        h1 {
            color: var(--color-dark-brown);
        }



        .table-wrapper {
            width: 100%;

        }

        .faq-table {
            width: 920px;
            /* 或可用 max-width */
            table-layout: fixed;
            border-collapse: collapse;
            background-color: #fff;
            margin-left: 0;
            margin-top: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }





        .faq-table th,
        .faq-table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .faq-table td.text-left {
            text-align: left;
            word-break: break-word;
            white-space: pre-wrap;
        }

        .faq-content {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            word-break: break-all;

        }

        .clickable {
            cursor: pointer;

        }

        .clickable:hover {
            text-decoration: underline;
        }






        .action-links {
            display: flex;
            flex-direction: column;
            gap: 6px;
            align-items: center;
        }

        .action-links a {
            text-decoration: none;
            font-size: 14px;
        }

        .action-links a.edit {
            color: #007BFF;
        }

        .action-links a.delete {
            color: #FF0000;
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

        #faq-form {
            scroll-margin-left: 1000px;
            /* 根據你的 navbar 高度微調 */
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .page-link {
            display: inline-block;
            min-width: 36px;
            padding: 10px 3px;
            margin: 0 4px;
            background-color: #fff8cc;
            color: #222;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .page-link:hover {
            background-color: #ffe885;
            color: #000;
        }

        .page-link.active {
            background-color: #f4d35e;
            color: #000;
            font-weight: bold;
            border: 1px solid #f4d35e;
        }

        #faq-form form {
            width: 90%;
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

    <div class="content">
        <h1>募資常見問題</h1>
        <div class="tabs">
            <div class="tab active" onclick="switchTab('faq-list', this)">發布紀錄</div>
            <div class="tab" onclick="switchTab('faq-form', this)">新增修改常見問題</div>
        </div>

        <div id="faq-list">
            <div class="search-bar">
                <form action="funding_FAQ.php" method="GET">
                    <input type="text" name="search" placeholder="輸入常見問題 ID、募資專案 ID、問題或回覆進行搜尋"
                        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                        style="width: 80%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <button type="submit"
                        style="padding: 10px 20px; background-color: #f4d35e; color: #333; border: none; border-radius: 4px; cursor: pointer;"><i
                            class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>
            <div class="table-wrapper">
                <table class="faq-table">
                    <thead>
                        <tr style="background-color: #7c4d2b; color: #fff;">
                            <th style="width: 8%;">問題 ID</th>
                            <th style="width: 8%;">募資 ID</th>
                            <th style="width: 15%;">問題</th>
                            <th style="width: 30%;">回覆</th>
                            <th style="width: 12%;">更新時間</th>
                            <th style="width: 8%;">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['funding_FAQ_id']) . "</td>";
                                echo "<td><a href='../funding_detail.php?project_id=" . htmlspecialchars($row['project_id']) . "' style='color: #333;'>" . htmlspecialchars($row['project_id']) . "</a></td>";

                                echo "<td class='faq-content clickable' onclick=\"showFullText('" . htmlspecialchars($row['question'], ENT_QUOTES) . "', '" . htmlspecialchars($row['reply'], ENT_QUOTES) . "')\">" . htmlspecialchars($row['question']) . "</td>";

                                echo "<td class='faq-content clickable' onclick=\"showFullText('" . htmlspecialchars($row['question'], ENT_QUOTES) . "', '" . htmlspecialchars($row['reply'], ENT_QUOTES) . "')\">" . htmlspecialchars($row['reply']) . "</td>";

                                echo "<td>" . htmlspecialchars($row['updated_on']) . "</td>";
                                echo "<td>
                    <div class='action-links'>
                     <a href='funding_FAQ.php?edit=" . $row['funding_FAQ_id'] . "' class='edit'>編輯</a>
                      <a href='funding_FAQ.php?delete=" . $row['funding_FAQ_id'] . "' class='delete' onclick='return confirm(\"確定要刪除嗎？\");'>刪除</a>
                    </div>
                  </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>目前沒有常見問題</td></tr>";
                        }
                        $stmt->close();
                        ?>
                    </tbody>
                </table>
            </div>


            <!-- 分頁按鈕 -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>&search=<?= htmlspecialchars($search) ?>" class="page-link">←</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?= $i ?>&search=<?= htmlspecialchars($search) ?>"
                        class="page-link <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?= $page + 1 ?>&search=<?= htmlspecialchars($search) ?>" class="page-link">→</a>
                <?php endif; ?>
            </div>

        </div>
        <div id="faq-form" style="display: none;margin-left:25px;">
            <h2 style="color: #7c4d2b;"><?php echo isset($_GET['edit']) ? "編輯常見問題" : "新增常見問題"; ?></h2>
            <?php if (isset($_GET['edit'])): ?>
                <div id="switch-to-create" style="margin: 10px 0;">
                    <button type="button" onclick="switchTabAndClearForm()"
                        style="background-color:  #f6a623; color: white; padding: 6px 12px; border:rgba(249, 182, 75, 0.21) solid 5px ; border-radius: 20px; cursor: pointer;">
                        切換為新增
                    </button>
                </div>
            <?php endif; ?>



            <?php
            $faq_id = $question = $reply = '';
            if (isset($_GET['edit'])) {
                $faq_id = intval($_GET['edit']);
                $stmt = $conn->prepare("SELECT * FROM funding_FAQ WHERE funding_FAQ_id = ?");
                $stmt->bind_param("i", $faq_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $project_id = $row['project_id'];
                    $question = $row['question'];
                    $reply = $row['reply'];
                }
                $stmt->close();
            }
            ?>
            <?php
            // 從資料庫中取得所有募資專案
            $projects = $conn->query("SELECT project_id, title FROM fundraising_projects");
            ?>
            <form action="funding_FAQ.php" method="POST"
                style="background-color: #f9f9f9; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); ">
                <input type="hidden" name="faq_id" value="<?php echo $faq_id; ?>">
                <div style="margin-bottom: 15px;">
                    <label for="project_id"
                        style="font-weight: bold; display: block; margin-bottom: 5px;">專案名稱：</label>
                    <?php if (isset($_GET['edit'])): ?>
                        <?php
                        // 取得該常見問題的專案名稱
                        $project_title = '';
                        if (!empty($project_id)) {
                            $stmt = $conn->prepare("SELECT title FROM fundraising_projects WHERE project_id = ?");
                            $stmt->bind_param("i", $project_id);
                            $stmt->execute();
                            $stmt->bind_result($project_title);
                            $stmt->fetch();
                            $stmt->close();
                        }
                        ?>
                        <input type="hidden" name="project_id" value="<?php echo htmlspecialchars($project_id); ?>">
                        <div style="padding: 10px 0 0 0; font-size: 1.1em; color: #333;">
                            <?php echo htmlspecialchars($project_title); ?>
                        </div>
                    <?php else: ?>
                        <select name="project_id" id="project_id" required
                            style="width: 92%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="">請選擇專案</option>
                            <?php
                            // 這裡要重新查詢一次專案資料
                            $projects = $conn->query("SELECT project_id, title FROM fundraising_projects");
                            if ($projects && $projects->num_rows > 0) {
                                while ($project = $projects->fetch_assoc()) {
                                    echo "<option value='" . $project['project_id'] . "'>" . htmlspecialchars($project['title']) . "</option>";
                                }
                            }
                            ?>
                        </select>
                        <div style="margin-top:8px; position:relative;">
                            <label for="project_search" style="font-size: 0.95em; color: #666;">或搜尋募資專案：</label>
                            <input type="text" id="project_search" name="project_search"
                                placeholder="輸入專案ID或名稱搜尋"
                                autocomplete="off"
                                style="width: 92%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; margin-top:2px;">
                            <div id="search_suggestions" style="position:absolute; z-index:10; background:#fff; border:1px solid #ccc; width:92%; display:none; max-height:180px; overflow-y:auto; border-radius:4px;"></div>
                        </div>
                        <script>
                        // 互斥控制
                        const select = document.getElementById('project_id');
                        const search = document.getElementById('project_search');
                        const suggestions = document.getElementById('search_suggestions');

                        select.addEventListener('change', function() {
                            if (this.value) {
                                search.disabled = true;
                                suggestions.style.display = 'none';
                            } else {
                                search.disabled = false;
                            }
                        });

                        search.addEventListener('input', function() {
                            if (this.value.trim() !== '') {
                                select.disabled = true;
                                select.required = false;
                                // AJAX 搜尋
                                fetch('search_projects.php?keyword=' + encodeURIComponent(this.value.trim()))
                                    .then(res => res.json())
                                    .then(data => {
                                        if (Array.isArray(data) && data.length > 0) {
                                            suggestions.innerHTML = data.map(item =>
                                                `<div class="suggest-item" style="padding:8px; cursor:pointer; border-bottom:1px solid #eee;" data-id="${item.project_id}">${item.project_id} - ${item.title}</div>`
                                            ).join('');
                                            suggestions.style.display = 'block';
                                        } else {
                                            suggestions.innerHTML = '<div style="padding:8px; color:#888;">查無相關專案</div>';
                                            suggestions.style.display = 'block';
                                        }
                                    });
                            } else {
                                select.disabled = false;
                                select.required = true;
                                suggestions.style.display = 'none';
                            }
                        });

                        // 點選建議
                        suggestions.addEventListener('click', function(e) {
                            if (e.target.classList.contains('suggest-item')) {
                                // 讓搜尋欄顯示「project_id - title」
                                search.value = e.target.textContent;
                                // 另外存 project_id 到一個隱藏欄位（送出時用）
                                let hidden = document.getElementById('project_search_id');
                                if (!hidden) {
                                    hidden = document.createElement('input');
                                    hidden.type = 'hidden';
                                    hidden.name = 'project_search';
                                    hidden.id = 'project_search_id';
                                    search.form.appendChild(hidden);
                                }
                                hidden.value = e.target.getAttribute('data-id');
                                suggestions.style.display = 'none';
                            }
                        });

                        // 點擊外部隱藏建議
                        document.addEventListener('click', function(e) {
                            if (!search.contains(e.target) && !suggestions.contains(e.target)) {
                                suggestions.style.display = 'none';
                            }
                        });
                        </script>
                    <?php endif; ?>
                </div>
                <div style="margin-bottom: 15px;">
                    <label for="question" style="font-weight: bold; display: block; margin-bottom: 5px;">問題：</label>
                    <textarea name="question" id="question" rows="3" required
                        style="width: 90%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"><?php echo htmlspecialchars($question); ?></textarea>
                </div>
                <div style="margin-bottom: 15px;">
                    <label for="reply" style="font-weight: bold; display: block; margin-bottom: 5px;">回覆：</label>
                    <textarea name="reply" id="reply" rows="5" required
                        style="width: 90%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"><?php echo htmlspecialchars($reply); ?></textarea>
                </div>
                <button type="submit"
                    style="background-color:  #f6a623; color: #fff; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">
                    <?php echo isset($_GET['edit']) ? "更新" : "新增"; ?>
                </button>
            </form>
        </div>
    </div>

    <script>
        function switchTab(showId, clickedTab) {
            // 顯示該區塊
            document.getElementById('faq-list').style.display = (showId === 'faq-list') ? 'block' : 'none';
            document.getElementById('faq-form').style.display = (showId === 'faq-form') ? 'block' : 'none';

            // 更新 tab 樣式
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            clickedTab.classList.add('active');
        }
    </script>
    <script>
        function showFullText(question, reply) {
            Swal.fire({
                title: question || '（無問題內容）',
                text: reply || '（無回覆內容）',
                confirmButtonText: '關閉',
                customClass: {
                    popup: 'swal-wide'
                }
            });
        }
    </script>
    <?php if (isset($_GET['edit'])): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // 自動切換 tab
                const targetTab = document.querySelector('.tab[onclick*="faq-form"]');
                if (targetTab) {
                    switchTab('faq-form', targetTab);
                }

                // 自動捲動表單
                const formBlock = document.getElementById('faq-form');
                if (formBlock) {
                    formBlock.scrollIntoView({ behavior: 'instant' });
                }
            });
        </script>

        <script>
            function switchTabAndClearForm() {
                // 取得目前網址
                const url = new URL(window.location.href);
                // 移除 edit 參數
                url.searchParams.delete('edit');
                // 重新導向到沒有 edit 參數的頁面（即新增狀態）
                window.location.href = url.toString();
            }
        </script>


    <?php endif; ?>




</body>

</html>