<!-- filepath: c:\xampp\htdocs\analysis_project\funding\load_replies.php -->
<?php
// 資料庫連線
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}

// 獲取 advice_id
$advice_id = isset($_GET['advice_id']) ? (int)$_GET['advice_id'] : 0;

// 檢查 advice_id 是否有效
if ($advice_id <= 0) {
    die("無效的 advice_id");
}

// 修正後的 SQL 語句
$sql = "SELECT reply_text, replied_at 
        FROM replies r 
        JOIN suggestion_assignments s 
        ON r.suggestion_assignments_id = s.suggestion_assignments_id 
        WHERE s.advice_id = ? 
        ORDER BY r.replied_at DESC";

$stmt = $conn->prepare($sql);

// 檢查 SQL 語句是否準備成功
if (!$stmt) {
    die("SQL 語句準備失敗: " . $conn->error);
}

$stmt->bind_param("i", $advice_id);
$stmt->execute();
$result = $stmt->get_result();

// 輸出回覆紀錄
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='reply'>";
        echo "<p><strong>回覆時間:</strong> " . htmlspecialchars($row['replied_at']) . "</p>";
        echo "<p>" . htmlspecialchars($row['reply_text']) . "</p>";
        echo "<hr>";
        echo "</div>";
    }
} else {
    echo "<p>目前沒有回覆紀錄。</p>";
}

$stmt->close();
$conn->close();
?>