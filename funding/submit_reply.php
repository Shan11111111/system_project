<?php
session_start();

// 資料庫連線
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}

function redirect_with_alert($message, $location = 'office_assignments.php')
{
    echo "<script>alert('$message'); window.location.href='$location';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $suggestion_assignments_id = intval($_POST['suggestion_assignments_id']);
    $reply_text = htmlspecialchars(trim($_POST['reply_text']), ENT_QUOTES, 'UTF-8');
    $office_id = $_SESSION['user_id'] ?? null;

    if (!$office_id) {
        redirect_with_alert('尚未登入或 session 過期，請重新登入。');
    }

    // 查詢 advice_id
    $advice_stmt = $conn->prepare("SELECT advice_id FROM suggestion_assignments WHERE suggestion_assignments_id = ?");
    if (!$advice_stmt)
        die("查詢 advice_id 錯誤: " . $conn->error);

    $advice_stmt->bind_param("i", $suggestion_assignments_id);
    $advice_stmt->execute();
    $advice_result = $advice_stmt->get_result();
    $advice_stmt->close();

    if ($advice_result->num_rows === 0) {
        redirect_with_alert('無法找到對應的建言 ID！');
    }

    $advice_id = $advice_result->fetch_assoc()['advice_id'];

    // 插入 reply
    $reply_stmt = $conn->prepare("INSERT INTO replies (suggestion_assignments_id, office_id, reply_text) VALUES (?, ?, ?)");
    if (!$reply_stmt)
        die("插入回覆錯誤: " . $conn->error);

    $reply_stmt->bind_param("iis", $suggestion_assignments_id, $office_id, $reply_text);
    if (!$reply_stmt->execute()) {
        redirect_with_alert('回覆提交失敗，請稍後再試！');
    }
    $reply_stmt->close();

    // 檢查並更新 advice_state
    $check_state_stmt = $conn->prepare("SELECT 1 FROM advice_state WHERE advice_id = ? AND content = '校方已回應'");
    $check_state_stmt->bind_param("i", $advice_id);
    $check_state_stmt->execute();
    $check_state_result = $check_state_stmt->get_result();
    $check_state_stmt->close();

    if ($check_state_result->num_rows === 0) {
        $insert_state_stmt = $conn->prepare("INSERT INTO advice_state (advice_id, content, state_time) VALUES (?, '校方已回應', NOW())");
        $insert_state_stmt->bind_param("i", $advice_id);
        if ($insert_state_stmt->execute()) {
            $update_advice_stmt = $conn->prepare("UPDATE advice SET advice_state = '已回覆' WHERE advice_id = ?");
            $update_advice_stmt->bind_param("i", $advice_id);
            $update_advice_stmt->execute();
            $update_advice_stmt->close();
            redirect_with_alert($advice_id . '回覆提交成功，狀態已記錄！');
        }
        $insert_state_stmt->close();
        redirect_with_alert('回覆提交成功，但狀態記錄失敗！');
    } else {
        $update_advice_stmt = $conn->prepare("UPDATE advice SET advice_state = '已回覆' WHERE advice_id = ?");
        $update_advice_stmt->bind_param("i", $advice_id);
        $update_advice_stmt->execute();
        $update_advice_stmt->close();
        redirect_with_alert($advice_id . '回覆提交成功，狀態已存在！');
    }
}

$conn->close();
?>