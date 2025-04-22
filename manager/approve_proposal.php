<?php
// filepath: c:\xampp\htdocs\analysis_project\manager\approve_proposal.php

// 資料庫連線
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}

if (isset($_GET['suggestion_assignments_id'])) {
    $suggestion_assignments_id = intval($_GET['suggestion_assignments_id']);

    // 更新提案狀態為 "已通過"
    $sql = "UPDATE suggestion_assignments 
            SET status = '已通過', reviewed_at = NOW(), approved_by_admin = TRUE 
            WHERE suggestion_assignments_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL 錯誤: " . $conn->error);
    }

    $stmt->bind_param("i", $suggestion_assignments_id);
    if ($stmt->execute()) {
        echo "<script>alert('提案已批准');</script>";
        echo "<script>window.location.href = 'review_proposals.php';</script>";
    } else {
        die("批准提案失敗: " . $stmt->error);
    }

    $stmt->close();
} else {
    die("無效的提案 ID");
}

$conn->close();
?>