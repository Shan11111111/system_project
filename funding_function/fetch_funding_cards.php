<?php
header('Content-Type: application/json');
include '../db_connection.php'; // 換成你資料庫連線檔案的正確路徑

$total = 100;
$perPage = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $perPage;

$category = $_GET['category'] ?? 'all';
$keyword  = $_GET['keyword'] ?? '';
$sort     = $_GET['sort'] ?? 'new';

$cards = [];

// 查詢資料庫中的募資資料
$sql = "SELECT a.advice_id, a.advice_title, a.advice_content, a.category, a.agree, 
                        ai.file_path FROM funding f 
                        INNER JOIN advice a ON f.advice_id = a.advice_id 
                        LEFT JOIN advice_image ai ON a.advice_id = ai.advice_id 
                        ORDER BY a.announce_date DESC";

$stmt = $pdo->query($sql);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC); // 這裡直接拿到所有資料列

foreach ($result as $row) {

    $supporter = rand(50, 200);  // 假設支持者數量隨機範圍從 50 到 200

    // 將需要的數據提取到卡片陣列
    $cards[] = [
        'id' => $row['advice_id'],  // 使用advice_id作為識別ID
        'title' => $row['advice_title'],  // 建議標題
        'content' => $row['advice_content'],  // 建議內容
        'category' => $row['category'],  // 類別
        'agree' => $row['agree'],  // 同意數
        'file_path' => $row['file_path'] ? $row['file_path'] : '',  // 檔案路徑，若無則為空字串
        'supporter' => $supporter // 假設支持者數量
    ];
}



// 篩選
if ($category !== 'all') {
    $cards = array_filter($cards, fn($c) => $c['category'] === $category);
}
if ($keyword !== '') {
    $cards = array_filter($cards, fn($c) => stripos($c['title'], $keyword) !== false);
}

// 排序
usort($cards, function ($a, $b) use ($sort) {
    if ($sort === 'hot') return $b['supporter'] - $a['supporter'];
    if ($sort === 'deadline') return strtotime($a['deadline']) - strtotime($b['deadline']);
    return strtotime($b['date']) - strtotime($a['date']);
});

// 分頁
$totalFiltered = count($cards);
$pagedData = array_slice(array_values($cards), $start, $perPage);

echo json_encode([
    'data' => $pagedData,
    'page' => $page,
    'totalPages' => ceil($totalFiltered / $perPage)
]);
