<?php
header('Content-Type: application/json');
include('../db_connection.php');

// 取得前端參數
$category = $_GET['category'] ?? 'all';
$keyword  = $_GET['keyword']  ?? '';
$sort     = $_GET['sort']     ?? 'new'; // 'hot', 'new', 'deadline'

// Step 1. 查詢所有建言基本資料 + 計算狀態（active / ended-passed / ended-notpassed）
$sql = "
    SELECT 
        a.advice_id,
        a.advice_title,
        a.category,
        a.announce_date,
        a.advice_state,
        DATEDIFF(CURDATE(), a.announce_date) AS days_elapsed,
        COUNT(DISTINCT ar.agree_record_id) AS support_count,
        COUNT(DISTINCT c.comment_id)       AS comment_count,
        img.file_path
    FROM advice a
    LEFT JOIN agree_record ar ON a.advice_id = ar.advice_id
    LEFT JOIN comments c      ON a.advice_id = c.advice_id
    LEFT JOIN advice_image img ON a.advice_id = img.advice_id
    WHERE 1=1
";

// 條件：分類 category
if ($category !== 'all') {
    $sql .= " AND a.category = :category ";
}

// 條件：關鍵字 keyword
if (!empty($keyword)) {
    $sql .= " AND a.advice_title LIKE :keyword ";
}

$sql .= " GROUP BY a.advice_id ";

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

    // ⭐ 新增這段檢查：如果沒有資料
    if (empty($results)) {
        echo json_encode(['no_result' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Step 2. 根據每筆資料決定「狀態」
    foreach ($results as &$row) {
        $daysElapsed = (int)$row['days_elapsed'];
        $supportCount = (int)$row['support_count'];

        if ($daysElapsed > 30) {
            $row['status'] = ($supportCount >= 3) ? 'ended-passed' : 'ended-notpassed';
        } else {
            $row['status'] = ($supportCount >= 3) ? 'ended-passed' : 'active';
        }
        // 額外加上是否已回覆的布林值
        $row['has_response'] = $row['advice_state'] === '已回覆';

        // 額外計算剩餘天數（for deadline 排序用）
        $row['remaining_days'] = max(0, 30 - $daysElapsed);
    }

    // Step 3. 排序
    if ($sort === 'hot') {
        usort($results, function ($a, $b) {
            return $b['support_count'] <=> $a['support_count'];
        });
    } elseif ($sort === 'deadline') {
        usort($results, function ($a, $b) {
            return $a['remaining_days'] <=> $b['remaining_days'];
        });
    } else { // 'new' 或其他，按發布日新→舊
        usort($results, function ($a, $b) {
            return strtotime($b['announce_date']) <=> strtotime($a['announce_date']);
        });
    }

    echo json_encode($results, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

//target
