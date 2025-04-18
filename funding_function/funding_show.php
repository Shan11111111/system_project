<?php

session_start();
header("Content-Type: application/json"); // 確保輸出為 JSON 格式

$link = new mysqli("localhost", "root", "", "system_project");
if ($link->connect_error) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "資料庫連線失敗", "details" => $link->connect_error]);
    exit();
}

// 修正 SQL 語法
$stmt = $link->prepare("
    SELECT 
        user_id, 
        advice_title, 
        advice_content, 
        category, 
        announce_date, 
        f.target 
    FROM 
        advice a 
    INNER JOIN 
        funding f 
    ON 
        a.advice_id = f.advice_id 
    LEFT JOIN 
        advice_image ai 
    ON 
        a.advice_id = ai.advice_id
");

if (!$stmt) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "SQL 語法錯誤", "details" => $link->error]);
    exit();
}

$stmt->execute();
$result = $stmt->get_result();
$funding = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $funding[] = $row;
    }
}

// 傳回 JSON 格式的建議資料
echo json_encode(["debug" => "資料庫查詢成功", "data" => $funding]);
$stmt->close();
$link->close();
?>