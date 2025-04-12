<?php
header(header: 'Content-Type: application/json');
include('../db_connection.php'); 


// 取得參數（分類、關鍵字、排序）
$category = $_GET['category'] ?? 'all';
$keyword = $_GET['keyword'] ?? '';
$sort = $_GET['sort'] ?? 'newest';

// 建立排序 SQL 條件
switch ($sort) {
    case 'newest':
        $orderBy = 'a.announce_date DESC';
        break;
    case 'oldest':
        $orderBy = 'a.announce_date ASC';
        break;
    case 'hottest':
        $orderBy = 'support_count DESC';
        break;
    case 'coldest':
        $orderBy = 'support_count ASC';
        break;
    default:
        $orderBy = 'a.announce_date DESC';
}

// 基本 SQL
$sql = "
    SELECT 
        a.advice_id,
        a.advice_title,
        a.category,
        a.announce_date,
        DATEDIFF(CURDATE(), a.announce_date) AS days_elapsed,
        COUNT(DISTINCT ar.agree_record_id) AS support_count,
        COUNT(DISTINCT c.comment_id) AS comment_count,
        img.img_path,
        CASE
            WHEN COUNT(DISTINCT ar.agree_record_id) >= 100 THEN 'ended-passed'
            WHEN DATEDIFF(CURDATE(), a.announce_date) > 30 THEN 'ended-notpassed'
            ELSE 'active'
        END AS status
    FROM advice a
    LEFT JOIN agree_record ar ON a.advice_id = ar.advice_id
    LEFT JOIN comments c ON a.advice_id = c.advice_id
    LEFT JOIN advice_image img ON a.advice_id = img.advice_id
    WHERE 1 = 1
";

// 加入分類條件
$params = [];
if ($category !== 'all') {
    $sql .= " AND a.category = :category";
    $params[':category'] = $category;
}

// 加入關鍵字條件
if (!empty($keyword)) {
    $sql .= " AND a.advice_title LIKE :keyword";
    $params[':keyword'] = '%' . $keyword . '%';
}

$sql .= " GROUP BY a.advice_id ORDER BY $orderBy";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>




