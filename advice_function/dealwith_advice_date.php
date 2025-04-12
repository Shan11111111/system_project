<?php
header(header: 'Content-Type: application/json');

include('../db_connection.php'); // 根據你的目錄結構，回上一層引用

//判斷時間且分類進行中與以結束
try {
    $stmt = $pdo->query("
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
    GROUP BY a.advice_id
    ORDER BY a.announce_date DESC
");

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

//



?>
