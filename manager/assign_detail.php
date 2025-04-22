<?php
$advice_id = isset($_GET['advice_id']) ? intval($_GET['advice_id']) : 0;

if ($advice_id <= 0) {
    // 如果沒有提供建言 ID，則重定向回建言管理頁面
    echo "<script>alert('無效的建言 ID');</script>";
    echo "<script>window.location.href = '../manager/advice_manager.php';</script>";
    exit();
}

// 資料庫連線
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 查詢建言詳細資訊，加入 files 表
$sql = "SELECT user_id,a.advice_id, a.advice_title, a.advice_content, a.category, a.agree, a.advice_state,announce_date, 
               ai.file_path, f.file_name,f.file_path AS file_path2
        FROM advice a 
        LEFT JOIN advice_image ai ON a.advice_id = ai.advice_id 
        LEFT JOIN files f ON a.advice_id = f.advice_id 
        WHERE a.advice_id = ?";

// 使用預處理語句以防止 SQL 注入
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $advice_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $advice = $result->fetch_assoc();
} else {
    die("找不到該建言");
}
?>

<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <title>分派建言</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
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
            padding: 20px;
        }

        .content h1 {
            font-size: 2em;
            color: #333;
            margin-bottom: 20px;
        }

        .content p {
            font-size: 1.1em;
            color: #555;
            line-height: 1.6;
        }

        .content strong {
            color: #333;
        }

        .content img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-top: 10px;
        }

        .content a {
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
        }

        .content a:hover {
            text-decoration: underline;
        }

        /* 表單樣式 */
        form {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        form label {
            font-size: 1.1em;
            color: #333;
            display: block;
            margin-bottom: 10px;
        }

        form select {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        form button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #0056b3;
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
        <h1>分派建言</h1>
        <p><strong>建言 ID:</strong> <?php echo $advice['advice_id']; ?></p>
        <p><strong>建言標題:</strong> <?php echo htmlspecialchars($advice['advice_title']); ?></p>
        <p><strong>建言類別:</strong> <?php echo htmlspecialchars($advice['category']); ?></p>
        <p><strong>建言狀態:</strong> <?php echo htmlspecialchars($advice['advice_state']); ?></p>
        <p><strong>建言人:</strong> <?php echo htmlspecialchars($advice['user_id']); ?></p>
        <p><strong>建言圖片:</strong></p>
        <?php if (!empty($advice['file_path'])): ?>
            <img src="../<?php echo htmlspecialchars($advice['file_path']); ?>" alt="建言圖片">
        <?php else: ?>
            <p>無圖片</p>
        <?php endif; ?>
        <p><strong>建言時間:</strong> <?php echo htmlspecialchars($advice['announce_date']); ?></p>
        <p><strong>建言人同意:</strong> <?php echo htmlspecialchars($advice['agree']); ?></p>
        <p><strong>建言附檔:</strong></p>
        <?php if (!empty($advice['file_path2'])): ?>
            <a href="<?php echo htmlspecialchars($advice['file_path2']); ?>" download><?php echo htmlspecialchars($advice['file_name']); ?></a>
        <?php else: ?>
            <p>無附檔</p>
        <?php endif; ?>
        <p><strong>建言內容:</strong> <?php echo nl2br(htmlspecialchars($advice['advice_content'])); ?></p>

        <form action="process_assignment.php" method="POST">
            <input type="hidden" name="advice_id" value="<?php echo $advice['advice_id']; ?>">
            <label for="office">選擇處所:</label>
            <select name="office" id="office" required>
                <option value="123">教務處</option>
                <option value="2222">總務處</option>
                <option value="345678">學務處</option>
                <option value="0909">資金與資源中心</option>
                <option value="0908">資訊處</option>
                <option value="0907">人事處</option>
                <option value="0906">國際事務處</option>
                <option value="0905">研究發展處</option>
                <option value="0904">環境發展中心</option>
            </select>
            <button type="submit">提交</button>
        </form>
    </div>
</body>

</html>
<?php
$conn->close();
?>