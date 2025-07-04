<?php
// filepath: c:\xampp\htdocs\analysis_project\funding\reset_notification.php

// 資料庫連線
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}

// 確認是否有傳遞 suggestion_assignments_id
if (isset($_GET['suggestion_assignments_id'])) {
    $suggestion_assignments_id = intval($_GET['suggestion_assignments_id']);

    // 查詢管理者回饋
    $feedback_sql = "SELECT admin_feedback FROM suggestion_assignments WHERE suggestion_assignments_id = ?";
    $feedback_stmt = $conn->prepare($feedback_sql);
    $admin_feedback = '';
    if ($feedback_stmt) {
        $feedback_stmt->bind_param("i", $suggestion_assignments_id);
        $feedback_stmt->execute();
        $feedback_stmt->bind_result($admin_feedback);
        $feedback_stmt->fetch();
        $feedback_stmt->close();
    }

    // 更新 notification 欄位為 FALSE
    $sql = "UPDATE suggestion_assignments SET notification = FALSE WHERE suggestion_assignments_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL 錯誤: " . $conn->error);
    }

    $stmt->bind_param("i", $suggestion_assignments_id);
    if ($stmt->execute()) {
        echo "<script>alert('通知已查看，管理者回饋: " . htmlspecialchars($admin_feedback) . "');</script>";
        echo "<script>window.location.href = 'office_assignments.php';</script>";
    } else {
        die("重置通知失敗: " . $stmt->error);
    }

    $stmt->close();
} else {
    die("無效的提案 ID");
}

$conn->close();
?>