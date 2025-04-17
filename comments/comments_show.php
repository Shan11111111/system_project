<!-- 展示留言 -->
<?php
session_start();

$advice_id = $_GET['advice_id'] ?? null;
if ($advice_id === null) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "請提供建議的ID"]);
    exit();
}


$link = new mysqli("localhost", "root", "", "system_project");
if ($link->connect_error) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "資料庫連線失敗"]);
    exit();
}

$stmt = $link->prepare("SELECT * FROM comments WHERE advice_id = ?");
$stmt->bind_param("i", $advice_id);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
}

// 傳回 JSON 格式的留言資料
header("Content-Type: application/json");
echo json_encode($comments);

$stmt->close();
$link->close();
?>