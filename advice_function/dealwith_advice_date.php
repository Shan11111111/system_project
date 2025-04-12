<?php
header('Content-Type: application/json');
include('../db_connection.php'); // 請改成你的實際連線檔案路徑

// 取得前端傳來的參數
$category = $_GET['category'] ?? 'all';   // e.g. 'all', 'equipment', ...
$keyword  = $_GET['keyword']  ?? '';      // 搜尋關鍵字
$sort     = $_GET['sort']     ?? 'new';   // 'hot' 或 'new'
$order    = $_GET['order']    ?? 'desc';  // 'desc' 或 'asc'

// 建立基礎查詢：同時保留計算 status（active / ended-passed / ended-notpassed）的邏輯
$sql = "
    SELECT 
        a.advice_id,
        a.advice_title,
        a.category,
        a.announce_date,
        DATEDIFF(CURDATE(), a.announce_date) AS days_elapsed,
        COUNT(DISTINCT ar.agree_record_id) AS support_count,
        COUNT(DISTINCT c.comment_id)       AS comment_count,
        img.img_path,
        CASE
            WHEN COUNT(DISTINCT ar.agree_record_id) >= 100 THEN 'ended-passed'
            WHEN DATEDIFF(CURDATE(), a.announce_date) > 30 THEN 'ended-notpassed'
            ELSE 'active'
        END AS status
    FROM advice a
    LEFT JOIN agree_record ar ON a.advice_id = ar.advice_id
    LEFT JOIN comments c     ON a.advice_id = c.advice_id
    LEFT JOIN advice_image img ON a.advice_id = img.advice_id
    WHERE 1=1
";

// 如果前端選擇了特定分類（非 'all'），則加上篩選
if ($category !== 'all') {
    $sql .= " AND a.category = :category ";
}

// 如果有輸入關鍵字，則同時搜尋 title 或 content
if (!empty($keyword)) {
    $sql .= " AND (a.advice_title LIKE :keyword ) ";
}

// 先 group 再做排序
$sql .= " GROUP BY a.advice_id ";

// 根據 sort 決定排序欄位
if ($sort === 'hot') {
    // 熱門排序就是依 support_count （附議數）決定
    $sql .= " ORDER BY support_count ";
} else {
    // 預設或 sort=new 時，就依 announce_date 排序
    $sql .= " ORDER BY a.announce_date ";
}

// 決定升冪或降冪
if ($order === 'asc') {
    $sql .= " ASC ";
} else {
    $sql .= " DESC ";
}

try {
    $stmt = $pdo->prepare($sql);

    // 綁定參數
    if ($category !== 'all') {
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
    }
    if (!empty($keyword)) {
        $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
    }

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 回傳 JSON
    echo json_encode($results, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
