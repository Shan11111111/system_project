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
    $project_id = intval($_POST['project_id']);
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
?>
<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <title>募資常見問題</title>
    <style>
        /* 保留原有的 CSS */
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
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            margin: 0 0 20px;
            font-size: 24px;
            text-align: center;
        }

        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #0056b3;
        }

        .content {
            margin-left: 280px;
            padding: 20px;
            width: calc(100% - 250px);
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
    </style>
</head>

<body>
    <!-- 左側導覽列 -->
    <div class="sidebar">
        <h2>管理系統</h2>
        <a href="../homepage.php">孵仁首頁</a>
        <a href="#">發布公告</a>
        <a href="adapt.php">自由認領達標建言區</a>
        <a href="office_assignments.php">提交提案與專案管理</a>
        <a href="office_apply_date.php">延後募款申請</a>
        <a href="funding_FAQ.php">募資常見問題</a>
        <a href="funding_return.php">募資進度回報</a>
        <a href="data">數據分析</a>
    </div>

    <div class="content">
        <h1>募資常見問題</h1>
        <div class="search-bar">
            <form action="funding_FAQ.php" method="GET">
                <input type="text" name="search" placeholder="輸入常見問題 ID、募資專案 ID、問題或回覆進行搜尋" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="width: 80%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <button type="submit" style="padding: 10px 20px; background-color: #007BFF; color: #fff; border: none; border-radius: 4px; cursor: pointer;">搜尋</button>
            </form>
        </div>
        <table style="width: 100%; border-collapse: collapse; margin: 20px 0; background-color: #fff; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            <thead>
                <tr style="background-color: #007BFF; color: #fff;">
                    <th style="padding: 12px 15px; text-align: center; border: 1px solid #ddd;">常見問題 ID</th>
                    <th style="padding: 12px 15px; text-align: center; border: 1px solid #ddd;">募資專案 ID</th>
                    <th style="padding: 12px 15px; text-align: center; border: 1px solid #ddd;">問題</th>
                    <th style="padding: 12px 15px; text-align: center; border: 1px solid #ddd;">回覆</th>
                    <th style="padding: 12px 15px; text-align: center; border: 1px solid #ddd;">更新時間</th>
                    <th style="padding: 12px 15px; text-align: center; border: 1px solid #ddd;">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                $sql = "SELECT funding_FAQ_id, project_id, question, reply, updated_on FROM funding_FAQ";
                if (!empty($search)) {
                    $sql .= " WHERE funding_FAQ_id LIKE ? OR project_id LIKE ? OR question LIKE ? OR reply LIKE ?";
                }
                $stmt = $conn->prepare($sql);
                if (!empty($search)) {
                    $search_param = '%' . $search . '%';
                    $stmt->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
                }
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td style='padding: 12px 15px; text-align: center; border: 1px solid #ddd;'>" . htmlspecialchars($row['funding_FAQ_id']) . "</td>";
                        echo "<td style='padding: 12px 15px; text-align: center; border: 1px solid #ddd;'><a href='../funding_detail.php?project_id=" . htmlspecialchars($row['project_id']) . "' style='color: #007BFF; text-decoration: none;'>" . htmlspecialchars($row['project_id']) . "</a></td>";
                        echo "<td style='padding: 12px 15px; text-align: center; border: 1px solid #ddd;'>" . htmlspecialchars($row['question']) . "</td>";
                        echo "<td style='padding: 12px 15px; text-align: center; border: 1px solid #ddd;'>" . htmlspecialchars($row['reply']) . "</td>";
                        echo "<td style='padding: 12px 15px; text-align: center; border: 1px solid #ddd;'>" . htmlspecialchars($row['updated_on']) . "</td>";
                        echo "<td style='padding: 12px 15px; text-align: center; border: 1px solid #ddd;'>";
                        echo "<a href='funding_FAQ.php?edit=" . $row['funding_FAQ_id'] . "' style='color: #007BFF; text-decoration: none; margin-right: 10px;'>編輯</a>";
                        echo "<a href='funding_FAQ.php?delete=" . $row['funding_FAQ_id'] . "' style='color: #FF0000; text-decoration: none;' onclick='return confirm(\"確定要刪除嗎？\");'>刪除</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='padding: 12px 15px; text-align: center; border: 1px solid #ddd;'>目前沒有常見問題</td></tr>";
                }
                $stmt->close();
                ?>
            </tbody>
        </table>

        <h2><?php echo isset($_GET['edit']) ? "編輯常見問題" : "新增常見問題"; ?></h2>
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
<form action="funding_FAQ.php" method="POST" style="background-color: #f9f9f9; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
    <input type="hidden" name="faq_id" value="<?php echo $faq_id; ?>">
    <div style="margin-bottom: 15px;">
        <label for="project_id" style="font-weight: bold; display: block; margin-bottom: 5px;">選擇募資專案：</label>
        <select name="project_id" id="project_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            <option value="">請選擇專案</option>
            <?php
            if ($projects->num_rows > 0) {
                while ($project = $projects->fetch_assoc()) {
                    $selected = ($project['project_id'] == $project_id) ? 'selected' : '';
                    echo "<option value='" . $project['project_id'] . "' $selected>" . htmlspecialchars($project['title']) . "</option>";
                }
            }
            ?>
        </select>
    </div>
    <div style="margin-bottom: 15px;">
        <label for="question" style="font-weight: bold; display: block; margin-bottom: 5px;">問題：</label>
        <textarea name="question" id="question" rows="3" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"><?php echo htmlspecialchars($question); ?></textarea>
    </div>
    <div style="margin-bottom: 15px;">
        <label for="reply" style="font-weight: bold; display: block; margin-bottom: 5px;">回覆：</label>
        <textarea name="reply" id="reply" rows="5" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"><?php echo htmlspecialchars($reply); ?></textarea>
    </div>
    <button type="submit" style="background-color: #007BFF; color: #fff; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">
        <?php echo isset($_GET['edit']) ? "更新" : "新增"; ?>
    </button>
</form>
    </div>
</body>

</html>