<?php
ini_set('display_errors', 0); // 禁止直接顯示錯誤
ini_set('log_errors', 1); // 啟用錯誤日誌
ini_set('error_log', __DIR__ . '/error_log.txt'); // 設定錯誤日誌路徑

require 'db_connection.php'; // 確保你有一個資料庫連線文件

header('Content-Type: application/json');

// 獲取請求參數
$category = isset($_GET['category']) ? $_GET['category'] : '';
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$sortHot = isset($_GET['sort_hot']) ? $_GET['sort_hot'] : 'desc';
$sortNew = isset($_GET['sort_new']) ? $_GET['sort_new'] : 'desc';

// 驗證排序參數是否有效
$validSortOptions = ['asc', 'desc'];
if (!in_array(strtolower($sortHot), $validSortOptions)) {
    $sortHot = 'desc';
}
if (!in_array(strtolower($sortNew), $validSortOptions)) {
    $sortNew = 'desc';
}

// 構建 SQL 語句
$sql = "SELECT * FROM advice WHERE 1=1";
if (!empty($category)) {
    $sql .= " AND category = ?";
}
if (!empty($keyword)) {
    $sql .= " AND (title LIKE ? OR content LIKE ?)";
}
$sql .= " ORDER BY hot $sortHot, advice_id $sortNew";

// 記錄生成的 SQL 語句
error_log("Generated SQL: " . $sql);

// 準備 SQL 語句
$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("SQL Prepare failed: " . $conn->error);
    die(json_encode(['error' => 'SQL Prepare failed']));
}

// 綁定參數
$params = [];
if (!empty($category)) {
    $params[] = $category;
}
if (!empty($keyword)) {
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
}

// 執行 SQL 語句
if (!$stmt->execute($params)) {
    error_log("SQL Execute failed: " . $stmt->error);
    die(json_encode(['error' => 'SQL Execute failed']));
}

// 獲取結果
$result = [];
$res = $stmt->get_result();
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $result[] = $row;
    }
} else {
    error_log("Fetching result failed: " . $stmt->error);
    die(json_encode(['error' => 'Fetching result failed']));
}

// 回傳 JSON 結果
echo json_encode(['suggestions' => $result]);
?>
