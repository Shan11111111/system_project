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

// 限制允許的狀態值，防止 SQL 注入
$allowedStatuses = ['active', 'ended'];
$status = isset($_GET['status']) && in_array($_GET['status'], $allowedStatuses) ? $_GET['status'] : 'active';

// 頁數與類別
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$itemsPerPage = 10;
$offset = ($page - 1) * $itemsPerPage;

// 排序參數
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'new'; // 預設為 "new"
$order = isset($_GET['order']) && $_GET['order'] === 'asc' ? 'ASC' : 'DESC';

// 定義允許的排序欄位
$allowedSortColumns = ['agree', 'announce_date'];

// 檢查傳入的 sort 參數是否合法
$sortColumn = (in_array($sort, $allowedSortColumns)) ? $sort : 'announce_date';


// 狀態條件
$statusCondition = ($status == 'active') ? "WHERE advice_state IN ('進行中', '未處理')" : "WHERE advice_state = '已結束'";

// 執行 SQL 查詢
$sql = "SELECT advice_id, user_id, advice_title, advice_content, agree, category, advice_state, announce_date 
        FROM advice 
        $statusCondition 
        ORDER BY $sortColumn $order 
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
        'comments' => $row['agree'],  // 這裡仍然使用 agree 代表贊成數
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
