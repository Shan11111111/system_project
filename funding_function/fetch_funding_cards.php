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
// 查詢募資專案資料 
$sql_projects = "SELECT 
    p.project_id, 
    p.title, 
    p.description, 
    p.funding_goal, 
    p.start_date, 
    p.end_date, 
    p.status,
    p.suggestion_assignments_id,
    COUNT(fp.funding_people_id) AS supporter
FROM fundraising_projects p
LEFT JOIN funding_people fp ON p.project_id = fp.funding_id
GROUP BY p.project_id
ORDER BY p.start_date DESC";

$stmt_projects = $pdo->query($sql_projects);
$projects = $stmt_projects->fetchAll(PDO::FETCH_ASSOC); // 取得所有募資專案資料

$cards = []; // 初始化卡片資料

foreach ($projects as $project) {
    $advice = null;
    $image_url = 'uploads/homepage.png'; // 預設圖片

    if (!empty($project['suggestion_assignments_id'])) {
        $sql_advice = "SELECT a.advice_id, a.category, ai.file_path 
                       FROM suggestion_assignments sa
                       LEFT JOIN advice a ON sa.advice_id = a.advice_id
                       LEFT JOIN advice_image ai ON a.advice_id = ai.advice_id
                       WHERE sa.suggestion_assignments_id = ?";

        $stmt_advice = $pdo->prepare($sql_advice);
        $stmt_advice->execute([$project['suggestion_assignments_id']]);
        $advice = $stmt_advice->fetch(PDO::FETCH_ASSOC);

        if (!empty($advice['file_path'])) {
            $image_url = $advice['file_path'];
        }
    }

    $cards[] = [
        'id' => $project['project_id'],
        'title' => $project['title'],
        'description' => $project['description'],
        'raised' => $project['funding_goal'],
        'start_date' => $project['start_date'],
        'end_date' => $project['end_date'],
        'status' => $project['status'],
        'supporter' => $project['supporter'],
        'file_path' => $image_url,
        'category' => !empty($advice['category']) ? $advice['category'] : '未分類'
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
