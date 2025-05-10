<?php
header('Content-Type: application/json');
include('../db_connection.php');

// 取得前端參數
$categoryMap = [
    'advice' => '建言',
    'fund'   => '募資',
    'system' => '系統',
];
$categoryKey = $_GET['category'] ?? 'all';     // 'advice', 'fund', 'system', or 'all'
$categoryVal = $categoryMap[$categoryKey] ?? ''; // '建言', '募資', '系統' 對應 DB 中資料

$keyword = trim($_GET['keyword'] ?? '');
$sort    = $_GET['sort'] ?? 'new'; // 'new' or 'old'

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
if ($categoryKey !== 'all') {
    $sql .= " AND a.category = :category ";
}

// 條件：關鍵字搜尋（標題）
if ($keyword !== '') {
    $sql .= " AND a.title LIKE :keyword ";
}


// 排序
$sql .= " ORDER BY a.update_at DESC ";

try {
    $stmt = $pdo->prepare($sql);

    // ✅ 綁定參數必須在 prepare 之後
    if ($categoryKey !== 'all') {
        $stmt->bindValue(':category', $categoryVal, PDO::PARAM_STR);
    }
    if ($keyword !== '') {
        $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
    }

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($results)) {
        echo json_encode(['no_result' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 排序（保險起見，如果前面 SQL 沒完全控制順序）
    if ($sort === 'old') {
        usort($results, fn($a, $b) => strtotime($a['update_at']) <=> strtotime($b['update_at']));
    } else {
        usort($results, fn($a, $b) => strtotime($b['update_at']) <=> strtotime($a['update_at']));
    }

    echo json_encode($results, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
