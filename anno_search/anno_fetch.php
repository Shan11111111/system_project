<?php 
header('Content-Type: application/json');
include('../db_connection.php');

// 取得前端參數
$keyword  = $_GET['keyword']  ?? '';
$sort     = $_GET['sort']     ?? 'new'; // 'new', 'old'
$category = $_GET['category'] ?? 'all'; // '建言', '募資', '系統', 或 'all'

// 查詢公告資料 + 使用 JOIN 查發布單位（users）
$sql = "
    SELECT 
        a.announcement_id,
        a.title,
        a.content,
        a.category,
        a.update_at,
        u.department AS author
    FROM announcement a
    JOIN users u ON a.user_id = u.user_id
    WHERE 1=1
";

// 條件：分類過濾
if ($category !== 'all') {
    $sql .= " AND a.category = :category ";
}

// 條件：關鍵字搜尋（標題與內容模糊比對）
if (!empty($keyword)) {
    $sql .= " AND (a.title LIKE :keyword OR a.content LIKE :keyword) ";
}

// 預設排序（先交給 PHP 排）
$sql .= " ORDER BY a.update_at DESC ";

try {
    $stmt = $pdo->prepare($sql);

    // 綁定參數
    if ($category !== 'all') {
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
    }
    if (!empty($keyword)) {
        $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
    }

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($results)) {
        echo json_encode(['no_result' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 排序：根據 sort 參數決定新→舊或舊→新
    if ($sort === 'old') {
        usort($results, fn($a, $b) => strtotime($a['update_at']) <=> strtotime($b['update_at']));
    } else {
        usort($results, fn($a, $b) => strtotime($b['update_at']) <=> strtotime($a['update_at']));
    }

    echo json_encode($results, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
