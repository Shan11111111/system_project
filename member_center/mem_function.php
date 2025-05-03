<?php
header('Content-Type: application/json');
include('../db_connection.php');
session_start();

$loginUserId = $_SESSION['user_id'] ?? null;
if (!$loginUserId) {
    echo json_encode(['error' => '未登入'], JSON_UNESCAPED_UNICODE);
    exit;
}

$agreeType = $_GET['agree_type'] ?? 'mine'; // mine: 發起的建言, agreed: 附議過的建言
$keyword   = $_GET['keyword']    ?? '';
$sort      = $_GET['sort']       ?? 'new';  // new | hot | deadline

try {
    if ($agreeType === 'agreed') {
        // ✅ 查詢我附議過的建言
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
        INNER JOIN agree_record ar_user 
            ON a.advice_id = ar_user.advice_id
        LEFT JOIN agree_record ar 
            ON a.advice_id = ar.advice_id
        LEFT JOIN comments c 
            ON a.advice_id = c.advice_id
        LEFT JOIN advice_image img 
            ON a.advice_id = img.advice_id
        WHERE ar_user.user_id = :user_id
        ";
    }elseif ($agreeType === 'collect') {
        // ✅ 查詢我收藏過的建言
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
            img.file_path,
            1 AS is_favorite
        FROM collection col
        INNER JOIN advice a 
            ON col.advice_id = a.advice_id
        LEFT JOIN agree_record ar 
            ON a.advice_id = ar.advice_id
        LEFT JOIN comments c 
            ON a.advice_id = c.advice_id
        LEFT JOIN advice_image img 
            ON a.advice_id = img.advice_id
        WHERE col.user_id = :user_id
        ";
    } else {
        // ✅ 查詢我發起的建言
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
            LEFT JOIN agree_record ar 
                ON a.advice_id = ar.advice_id
            LEFT JOIN comments c 
                ON a.advice_id = c.advice_id
            LEFT JOIN advice_image img 
                ON a.advice_id = img.advice_id
            WHERE a.user_id = :user_id
        ";
    }

    // 加入關鍵字查詢
    if (!empty($keyword)) {
        $sql .= " AND a.advice_title LIKE :keyword ";
    }

    // 加入 GROUP BY
    $sql .= " GROUP BY a.advice_id ";

    // 執行查詢
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $loginUserId, PDO::PARAM_INT);
    if (!empty($keyword)) {
        $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
    }
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 若查無資料
    if (empty($results)) {
        echo json_encode(['no_result' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 補上建言狀態與天數分類
    foreach ($results as &$row) {
        $daysElapsed = (int)$row['days_elapsed'];
        $support     = (int)$row['support_count'];
        $row['has_response'] = $row['advice_state'] === '已回覆';
        $row['remaining_days'] = max(0, 30 - $daysElapsed);

        if ($row['has_response']) {
            $row['status'] = 'responed';
            $row['type']   = 'responed';
        } elseif ($support >= 3 && $daysElapsed <= 30) {
            $row['status'] = 'passed';
            $row['type']   = 'active';
        } elseif ($daysElapsed <= 30) {
            $row['status'] = 'active';
            $row['type']   = 'active';
        } else {
            $row['status'] = 'expired';
            $row['type']   = 'ended';
        }
    }

    // 排序邏輯
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
