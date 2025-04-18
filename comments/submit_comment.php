<?php
session_start();
header('Content-Type: application/json');

// 確認使用者是否登入
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => '請先登入後再提交留言']);
    exit;
}

// 確認請求方法是否為 POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => '無效的請求方法']);
    exit;
}

// 接收 POST 資料
$advice_id = isset($_POST['advice_id']) ? intval($_POST['advice_id']) : null;
$comment_text = isset($_POST['comment_text']) ? trim($_POST['comment_text']) : null;
$user_id = $_SESSION['user_id']; // 從 session 中取得 user_id

// 驗證資料
if (!$advice_id || !$comment_text) {
    echo json_encode(['status' => 'error', 'message' => '建議 ID 或留言內容不可為空']);
    exit;
}

// 連接資料庫
$link = mysqli_connect('localhost', 'root', '', 'system_project');
if (!$link) {
    echo json_encode(['status' => 'error', 'message' => '資料庫連線失敗']);
    exit;
}

// 插入留言到資料庫
$sql = "INSERT INTO comments (advice_id, user_id, comment_content, comment_time) VALUES (?, ?, ?, NOW())";
$stmt = mysqli_prepare($link, $sql);
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'SQL 準備失敗']);
    exit;
}

mysqli_stmt_bind_param($stmt, 'iis', $advice_id, $user_id, $comment_text);
if (!mysqli_stmt_execute($stmt)) {
    echo json_encode(['status' => 'error', 'message' => '留言提交失敗']);
    exit;
}

// 取得剛插入的留言時間
$comment_time = date('Y-m-d H:i:s'); // 使用伺服器時間

// 成功回應
echo json_encode([
    'status' => 'success',
    'message' => '留言已提交',
    'data' => [
        'advice_id' => $advice_id,
        'comment_text' => htmlspecialchars($comment_text),
        'user_id' => htmlspecialchars($user_id),
        'comment_time' => $comment_time
    ]
]);

// 關閉連線
mysqli_stmt_close($stmt);
mysqli_close($link);
?>