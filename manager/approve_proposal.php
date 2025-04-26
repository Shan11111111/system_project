<?php
// filepath: c:\xampp\htdocs\analysis_project\manager\approve_proposal.php

// 資料庫連線
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $suggestion_assignments_id = intval($_POST['suggestion_assignments_id']);
    $admin_feedback = $_POST['admin_feedback']; // 接收管理者的回饋內容

    // 更新提案狀態為 "已通過" 並存入回饋
    $sql = "UPDATE suggestion_assignments 
            SET status = '已通過', reviewed_at = NOW(), approved_by_admin = TRUE, notification = TRUE, admin_feedback = ? 
            WHERE suggestion_assignments_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL 錯誤: " . $conn->error);
    }

    $stmt->bind_param("si", $admin_feedback, $suggestion_assignments_id);
    if ($stmt->execute()) {
        // 將提案資料插入到募資專案表
        $fundraising_sql = "INSERT INTO fundraising_projects (suggestion_assignments_id, title, description, funding_goal, start_date,end_date status) 
                            SELECT sa.suggestion_assignments_id, a.advice_title, sa.proposal_text, sa.funding_amount, NOW(),DATE_ADD(NOW(), INTERVAL 30 DAY), '進行中'
                            FROM suggestion_assignments sa
                            JOIN advice a ON sa.advice_id = a.advice_id
                            WHERE sa.suggestion_assignments_id = ?";
        $fundraising_stmt = $conn->prepare($fundraising_sql);
        if (!$fundraising_stmt) {
            die("募資專案插入失敗: " . $conn->error);
        }

        $fundraising_stmt->bind_param("i", $suggestion_assignments_id);
        if ($fundraising_stmt->execute()) {
            echo "<script>alert('提案已批准，募資已啟動');</script>";
        } else {
            die("募資啟動失敗: " . $fundraising_stmt->error);
        }

        $fundraising_stmt->close();
        echo "<script>window.location.href = 'review_proposals.php';</script>";
    } else {
        die("批准提案失敗: " . $stmt->error);
    }

    $stmt->close();
} else {
    die("無效的請求方法");
}

$conn->close();
?>