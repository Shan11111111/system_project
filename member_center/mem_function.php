<?php
header('Content-Type: application/json');
include('../db_connection.php');
session_start();

$loginUserId = $_SESSION['user_id'] ?? null;
if (!$loginUserId) {
    echo json_encode(['error' => '未登入'], JSON_UNESCAPED_UNICODE);
    exit;
}

// 取得前端參數
$keyword  = $_GET['keyword']  ?? '';
$sort     = $_GET['sort']     ?? 'new'; // 'hot', 'new', 'deadline'

// Step 1. 查詢所有建言基本資料 + 計算狀態（active / ended-passed / ended-notpassed）
$sql = "
    SELECT 
        a.advice_id,
        a.user_id,
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
    WHERE a.user_id = :user_id
";

// 加入關鍵字搜尋
if (!empty($keyword)) {
    $sql .= " AND a.advice_title LIKE :keyword ";
}

$sql .= " GROUP BY a.advice_id ";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $loginUserId, PDO::PARAM_INT);
    if (!empty($keyword)) {
        $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
    }

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($results)) {
        echo json_encode(['no_result' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }

    foreach ($results as &$row) {
        $daysElapsed = (int)$row['days_elapsed'];
        $support = (int)$row['support_count'];
        $row['has_response'] = $row['advice_state'] === '已回覆';
        $row['remaining_days'] = max(0, 30 - $daysElapsed);
    
        if ($row['has_response']) {
            $row['status'] = 'responed';
            $row['type'] = 'responed';
        } elseif ($support >= 3 && $daysElapsed <= 30) {
            $row['status'] = 'passed'; // 已達標但尚未回覆
            $row['type'] = 'active';
        } elseif ($daysElapsed <= 30) {
            $row['status'] = 'active'; // 進行中
            $row['type'] = 'active';
        } else {
            $row['status'] = 'expired'; // 失效
            $row['type'] = 'ended';
        }
    }
    
    // 排序
    if ($sort === 'hot') {
        usort($results, fn($a, $b) => $b['support_count'] <=> $a['support_count']);
    } elseif ($sort === 'deadline') {
        usort($results, fn($a, $b) => $a['remaining_days'] <=> $b['remaining_days']);
    } else {
        usort($results, fn($a, $b) => strtotime($b['announce_date']) <=> strtotime($a['announce_date']));
    }

    echo json_encode($results, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
