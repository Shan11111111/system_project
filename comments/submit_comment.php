<?php
session_start();
header('Content-Type: application/json');

// 確認使用者是否登入
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => '請先登入後再提交留言']);
    exit;
}
// 確認請求方法是否為 POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $advice_id = $_POST['advice_id'];
    $comment_text = $_POST['comment_text'];
    $user_id = $_SESSION['user_id'] ?? '匿名';

    // 連接資料庫
    $link = mysqli_connect('localhost', 'root', '', 'system_project');
    if (!$link) {
        echo json_encode(['status' => 'error', 'message' => '資料庫連線失敗']);
        exit;
    }

    // 插入留言
    $stmt = $link->prepare("INSERT INTO comments (advice_id, user_id, comment_content, comment_time) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param('iss', $advice_id, $user_id, $comment_text);

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => '留言提交成功',
            'username' => htmlspecialchars($user_id),
            'comment_text' => htmlspecialchars($comment_text)
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => '留言提交失敗']);
    }

    $stmt->close();
    $link->close();
}
?>