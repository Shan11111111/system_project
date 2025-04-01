<?php
include('db_connection.php');  // 假設有一個db_connection.php，包含資料庫連接的設定

// 獲取查詢參數
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$status = isset($_GET['status']) ? $_GET['status'] : 'active';
$itemsPerPage = 10; // 每頁顯示10條建議

// 計算偏移量
$offset = ($page - 1) * $itemsPerPage;

// 進行 SQL 查詢，根據狀態和頁數篩選建議
$query = "SELECT * FROM suggestions WHERE status = :status LIMIT :offset, :itemsPerPage";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':status', $status, PDO::PARAM_STR);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
$stmt->execute();
$suggestions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 獲取總頁數
$countQuery = "SELECT COUNT(*) FROM suggestions WHERE status = :status";
$countStmt = $pdo->prepare($countQuery);
$countStmt->bindParam(':status', $status, PDO::PARAM_STR);
$countStmt->execute();
$totalItems = $countStmt->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);

// 將結果轉為 JSON 格式回傳
header('Content-Type: application/json');
echo json_encode([
    'suggestions' => $suggestions,
    'totalPages' => $totalPages
]);
?>
