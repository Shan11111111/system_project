<?php  
// 建立資料庫連接
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "system_project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

// 限制允許的排序與狀態值，防止 SQL 注入
$allowedSorts = ['hot_asc', 'hot_desc', 'new_asc', 'new_desc'];
$allowedStatuses = ['active', 'ended'];

$sort = isset($_GET['sort']) && in_array($_GET['sort'], $allowedSorts) ? $_GET['sort'] : 'new_asc';
$status = isset($_GET['status']) && in_array($_GET['status'], $allowedStatuses) ? $_GET['status'] : 'active';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$itemsPerPage = 10;
$offset = ($page - 1) * $itemsPerPage;

// 狀態條件
$statusCondition = ($status == 'active') ? "WHERE advice_state IN ('進行中', '未處理')" : "WHERE advice_state = '已結束'";

// 設定排序方式，避免 SQL 拼接注入
$orderByMap = [
    'hot_asc'  => "agree ASC",
    'hot_desc' => "agree DESC",
    'new_asc'  => "announce_date ASC",
    'new_desc' => "announce_date DESC",
];

$orderBy = isset($orderByMap[$sort]) ? $orderByMap[$sort] : "announce_date DESC";

// 執行 SQL 查詢
$sql = "SELECT advice_id, user_id, advice_title, advice_content, agree, category, advice_state, announce_date 
        FROM advice $statusCondition 
        ORDER BY $orderBy 
        LIMIT ?, ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $offset, $itemsPerPage);
$stmt->execute();
$result = $stmt->get_result();

// 取得建議資料
$suggestions = [];
while ($row = $result->fetch_assoc()) {
    $remainingDays = ceil((strtotime($row['announce_date'] . ' +30 days') - time()) / 86400);
    $remainingDays = max(0, $remainingDays); // 確保不會顯示負天數

    $suggestions[] = [
        'id' => $row['advice_id'],
        'userId' => $row['user_id'],
        'title' => $row['advice_title'],
        'content' => $row['advice_content'],
        'comments' => $row['agree'], 
        'category' => $row['category'],
        'status' => $row['advice_state'], 
        'publishDate' => $row['announce_date'],
        'deadline' => $remainingDays . ' 天',
    ];
}

// 計算總頁數
$totalSql = "SELECT COUNT(*) AS total FROM advice $statusCondition";
$totalResult = $conn->query($totalSql);
$totalRow = $totalResult->fetch_assoc();
$totalItems = $totalRow['total'];
$totalPages = ceil($totalItems / $itemsPerPage);

// 關閉資料庫連接
$conn->close();

// 設定回傳的 Content-Type 為 JSON
header('Content-Type: application/json');

// 回傳 JSON
echo json_encode([
    'suggestions' => $suggestions,
    'totalPages' => $totalPages,
    'currentPage' => $page
]);
?>
