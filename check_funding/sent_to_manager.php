<?php
session_start();

header('Content-Type: application/json; charset=utf-8'); // 設定回應為 JSON 格式

$link = new mysqli("localhost", "root", "", "system_project");
if ($link->connect_error) {
    echo json_encode(['error' => 'Database connection failed: ' . $link->connect_error]);
    exit();
}

// 準備 SQL 語句
$sql = "SELECT advice_title, advice_content, category, announce_date, target FROM advice WHERE agree >= 3 AND advice_state = '未處理'";
$stmt = $link->prepare($sql);

if (!$stmt) {
    echo json_encode(['error' => 'SQL prepare failed: ' . $link->error]);
    exit();
}

if (!$stmt->execute()) {
    echo json_encode(['error' => 'SQL execute failed: ' . $stmt->error]);
    exit();
}

$result = $stmt->get_result();
$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// 返回 JSON 資料
echo json_encode(['data' => $data]);
exit();
?>