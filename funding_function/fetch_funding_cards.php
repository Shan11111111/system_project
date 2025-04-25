<?php 
header('Content-Type: application/json');
include '../db_connection.php'; // 資料庫連線

// 分頁設定
$perPage = 12;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$start = ($page - 1) * $perPage;

// 前端傳入的篩選條件
$category   = $_GET['category'] ?? 'all';
$keyword    = trim($_GET['keyword'] ?? '');
$sort       = $_GET['sort'] ?? 'new';
$page_type  = $_GET['page_type'] ?? 'all';

// 判斷是 ongoing 或 due 頁面
$status_filter = null;
if ($page_type === 'ongoing') {
    $status_filter = 'active';
} elseif ($page_type === 'due') {
    $status_filter = 'expired';
}

// 查詢所有專案
$sql = "SELECT 
    p.project_id, 
    p.title, 
    p.description, 
    p.funding_goal, 
    p.start_date, 
    p.end_date, 
    p.status,
    p.suggestion_assignments_id
FROM fundraising_projects p
ORDER BY p.start_date DESC";

$stmt = $pdo->query($sql);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

$cards = [];

foreach ($projects as $project) {
    $image_url = 'uploads/homepage.png';
    $category_val = '未分類';

    // 查圖片與分類
    if (!empty($project['suggestion_assignments_id'])) {
        $sql_advice = "SELECT a.advice_id, a.category, ai.file_path 
                       FROM suggestion_assignments sa
                       LEFT JOIN advice a ON sa.advice_id = a.advice_id
                       LEFT JOIN advice_image ai ON a.advice_id = ai.advice_id
                       WHERE sa.suggestion_assignments_id = ?";
        $stmt_advice = $pdo->prepare($sql_advice);
        $stmt_advice->execute([$project['suggestion_assignments_id']]);
        $advice = $stmt_advice->fetch(PDO::FETCH_ASSOC);
        if ($advice) {
            $category_val = $advice['category'] ?? '未分類';
            if (!empty($advice['file_path'])) {
                $image_url = $advice['file_path'];
            }
        }
    }

    // 募資金額總和
    $stmt = $pdo->prepare("SELECT SUM(donation_amount) FROM donation_record WHERE project_id = ?");
    $stmt->execute([$project['project_id']]);
    $raised = $stmt->fetchColumn() ?: 0;

    // 支持人數
    $stmt = $pdo->prepare("SELECT COUNT(DISTINCT donor) FROM donation_record WHERE project_id = ?");
    $stmt->execute([$project['project_id']]);
    $supporter = $stmt->fetchColumn() ?: 0;

    // 判斷是否過期
    try {
        $endDate = new DateTime($project['end_date']);
        $now = new DateTime();
        $is_expired = $endDate < $now ? true : false;
    } catch (Exception $e) {
        $is_expired = false; // 若有錯，視為尚未過期
    }


    // 組合卡片資料
    $cards[] = [
        'id' => $project['project_id'],
        'title' => $project['title'],
        'description' => $project['description'],
        'raised' => $project['funding_goal'],
        'start_date' => date('Y-m-d', strtotime($project['start_date'])),
        'end_date'   => date('Y-m-d', strtotime($project['end_date'])),
        'status' => $project['status'],
        'supporter' => $supporter,
        'file_path' => $image_url,
        'category' => $category_val,
        'progress' => $project['funding_goal'] > 0 ? round($raised / $project['funding_goal'] * 100) : 0,
        'is_expired' => $is_expired
    ];
}

if ($status_filter === 'active') { //ongoing
    $cards = array_filter($cards, function ($c) {
        return isset($c['is_expired']) && $c['is_expired'] === false;
    });
} elseif ($status_filter === 'expired') { //due
    $cards = array_filter($cards, function ($c) {
        return isset($c['is_expired']) && $c['is_expired'] === true;
    });
}

// 篩選分類
if ($category !== 'all') {
    $cards = array_filter($cards, fn($c) => $c['category'] === $category);
}
// 關鍵字搜尋（標題模糊搜尋）
if ($keyword !== '') {
    $cards = array_filter($cards, fn($c) => stripos($c['title'], $keyword) !== false);
}

// ongoing排序
usort($cards, function ($a, $b) use ($sort) {
    if ($sort === 'hot') return $b['supporter'] - $a['supporter'];
    if ($sort === 'target') return $b['progress'] - $a['progress'];
    if ($sort === 'deadline') return strtotime($a['end_date']) - strtotime($b['end_date']);
    return strtotime($b['start_date']) - strtotime($a['start_date']); // 預設為最新
});

//due排序
if ($status_filter === 'expired') {
    // 先根據 sort 篩選成功或失敗
    if ($sort === 'successful') {
        $cards = array_filter($cards, fn($c) => $c['progress'] >= 100);
    } elseif ($sort === 'fail') {
        $cards = array_filter($cards, fn($c) => $c['progress'] < 100);
    }

    // 接著根據排序邏輯再排一次
    usort($cards, function ($a, $b) use ($sort) {
        if ($sort === 'successful') {
            return $b['supporter'] - $a['supporter'];
        }
        if ($sort === 'fail') {
            return strtotime($a['end_date']) - strtotime($b['end_date']);
        }
        return 0;
    });
}



// 分頁
$totalFiltered = count($cards);
$pagedData = array_slice(array_values($cards), $start, $perPage);

// 回傳 JSON 結果
echo json_encode([
    'data' => $pagedData,
    'page' => $page,
    'totalPages' => ceil($totalFiltered / $perPage)
]);


