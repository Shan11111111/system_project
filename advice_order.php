<?php
require 'db_connection.php'; // 確保資料庫連線

header('Content-Type: application/json');

try {
    // 獲取請求參數
    $category = isset($_GET['category']) ? trim($_GET['category']) : '';
    $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
    $sortHot = isset($_GET['sort_hot']) ? strtolower($_GET['sort_hot']) : 'desc';
    $sortNew = isset($_GET['sort_new']) ? strtolower($_GET['sort_new']) : 'desc';

    // 驗證排序參數
    $validSortOptions = ['asc', 'desc'];
    if (!in_array($sortHot, $validSortOptions)) {
        $sortHot = 'desc';
    }
    if (!in_array($sortNew, $validSortOptions)) {
        $sortNew = 'desc';
    }

    // 構建 SQL 語句
    $sql = "SELECT * FROM advice WHERE 1=1";
    $params = [];

    if (!empty($category)) {
        $sql .= " AND category = ?";
        $params[] = $category;
    }

    if (!empty($keyword)) {
        $sql .= " AND (title LIKE ? OR content LIKE ?)";
        $params[] = "%$keyword%";
        $params[] = "%$keyword%";
    }

    // 排序條件，先按 agree（讚數）排序，再按 advice_id（最新排序）
    $sql .= " ORDER BY agree $sortHot, advice_id $sortNew";

    // 準備 SQL 語句
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("SQL 準備錯誤: " . $conn->error);
    }

    // 綁定參數
    if (!empty($params)) {
        $paramTypes = str_repeat("s", count($params)); // 根據參數數量建立對應的類型
        $stmt->bind_param($paramTypes, ...$params);
    }

    // 執行 SQL
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    echo json_encode(['suggestions' => $result]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
