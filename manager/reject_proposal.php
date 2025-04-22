<?php
// filepath: c:\xampp\htdocs\analysis_project\manager\reject_proposal.php

// 資料庫連線
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $suggestion_assignments_id = intval($_POST['suggestion_assignments_id']);
    $admin_feedback = $_POST['admin_feedback']; // 接收管理者的回饋意見

    // 更新提案狀態為 "被退回" 並存入回饋
    $sql = "UPDATE suggestion_assignments 
            SET status = '被退回', reviewed_at = NOW(), approved_by_admin = FALSE, notification = TRUE, admin_feedback = ? 
            WHERE suggestion_assignments_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL 錯誤: " . $conn->error);
    }

    $stmt->bind_param("si", $admin_feedback, $suggestion_assignments_id);
    if ($stmt->execute()) {
        echo "<script>alert('提案已退回');</script>";
        echo "<script>window.location.href = 'review_proposals.php';</script>";
    } else {
        die("退回提案失敗: " . $stmt->error);
    }

    $stmt->close();
} else {
    die("無效的請求方法");
}

$conn->close();
?>