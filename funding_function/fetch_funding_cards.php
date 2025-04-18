<?php
header('Content-Type: application/json');

$total = 100;
$perPage = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $perPage;

$category = $_GET['category'] ?? 'all';
$keyword  = $_GET['keyword'] ?? '';
$sort     = $_GET['sort'] ?? 'new';

$cards = [];

$categoryOptions = ['equipment', 'academic', 'club', 'welfare', 'environment', 'other'];

for ($i = 1; $i <= $total; $i++) {
    $cards[] = [
        'id' => $i,
        'title' => "範例募資專案 #{$i}",
        'category' => $categoryOptions[$i % 6],
        'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQk_5XgR2ZDah4v8eTfVCvgYJ4amCbsXWZt8g&s',
        'progress' => rand(30, 120),
        'raised' => number_format(rand(50000, 300000)),
        'supporter' => rand(10, 120),
        'date' => date('Y-m-d', strtotime("-{$i} days")),
        'deadline' => date('Y-m-d', strtotime("+".rand(5, 60)." days"))
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
usort($cards, function($a, $b) use ($sort) {
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
