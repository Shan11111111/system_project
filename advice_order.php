<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// 資料庫連線設定
$host = 'localhost';  // 資料庫伺服器
$dbname = 'system_project';  // 資料庫名稱
$username = 'root';  // 資料庫使用者名稱
$password = '';  // 資料庫密碼

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


// 獲取查詢參數
$category = isset($_GET['category']) ? htmlspecialchars($_GET['category'], ENT_QUOTES, 'UTF-8') : '';
$keyword = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword'], ENT_QUOTES, 'UTF-8') : '';
$sortHot = isset($_GET['sort_hot']) ? $_GET['sort_hot'] : 'desc';
$sortNew = isset($_GET['sort_new']) ? $_GET['sort_new'] : 'desc';
$itemsPerPage = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

// 構建查詢條件
$where = [];
$params = [];

if ($category) {
    $where[] = "category = :category";
    $params[':category'] = $category;
}

if ($keyword) {
    $where[] = "title LIKE :keyword";
    $params[':keyword'] = '%' . $keyword . '%';
}

// 構建 WHERE 子句
$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// 設定排序條件
$sortSql = "ORDER BY ";
if ($sortHot === 'asc') {
    $sortSql .= "agree ASC";
} else {
    $sortSql .= "agree DESC";
}

if ($sortNew === 'asc') {
    $sortSql .= ", announce_date ASC";
} else {
    $sortSql .= ", announce_date DESC";
}

// SQL 查詢建議，注意使用 LIMIT 來分頁
$query = "SELECT * FROM advice $whereSql $sortSql LIMIT :offset, :itemsPerPage";
$stmt = $pdo->prepare($query);

// 綁定參數
foreach ($params as $key => $value) {
    $stmt->bindParam($key, $value);
}
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);

// 執行查詢
if (!$stmt->execute()) {
    // 如果 SQL 執行失敗，返回錯誤信息
    echo json_encode(['error' => 'SQL query execution failed']);
    exit;
}

$suggestions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 獲取總項目數量，並計算總頁數
$countQuery = "SELECT COUNT(*) FROM advice $whereSql";
$countStmt = $pdo->prepare($countQuery);

// 綁定參數
foreach ($params as $key => $value) {
    $countStmt->bindParam($key, $value);
}

if (!$countStmt->execute()) {
    // 如果 SQL 執行失敗，返回錯誤信息
    echo json_encode(['error' => 'Count query execution failed']);
    exit;
}

$totalItems = $countStmt->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);

// 設置回應的內容類型為 JSON
header('Content-Type: application/json');

// 回傳結果
echo json_encode([
    'suggestions' => $suggestions,
    'totalPages' => $totalPages
]);
?>