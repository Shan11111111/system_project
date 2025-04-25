<?php
// filepath: c:\xampp\htdocs\analysis_project\manager\review_extension_requests.php

// 資料庫連線
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = intval($_POST['request_id']);
    $action = $_POST['action']; // 'accept' 或 'reject'
    $admin_response = $_POST['admin_response'];

    if ($action === 'accept') {
        // 接受申請，更新募款專案的截止日
        $update_sql = "UPDATE fundraising_projects fp
                       JOIN fundraising_extension_requests fer ON fp.id = fer.fundraising_project_id
                       SET fp.end_date = fer.requested_extension_date
                       WHERE fer.id = ?";
        $update_stmt = $conn->prepare($update_sql);
        if (!$update_stmt) {
            die("SQL 錯誤: " . $conn->error);
        }
        $update_stmt->bind_param("i", $request_id);
        $update_stmt->execute();
        $update_stmt->close();
    }

    // 更新申請的狀態
    $status = ($action === 'accept') ? '已接受' : '已拒絕';
    $review_sql = "UPDATE fundraising_extension_requests 
                   SET status = ?, admin_response = ?, reviewed_at = NOW() 
                   WHERE id = ?";
    $review_stmt = $conn->prepare($review_sql);
    if (!$review_stmt) {
        die("SQL 錯誤: " . $conn->error);
    }
    $review_stmt->bind_param("ssi", $status, $admin_response, $request_id);
    if ($review_stmt->execute()) {
        echo "<script>alert('申請已處理'); window.location.href='review_extension_requests.php';</script>";
    } else {
        echo "<script>alert('處理失敗，請稍後再試'); window.location.href='review_extension_requests.php';</script>";
    }

    $review_stmt->close();
}

$conn->close();
?>