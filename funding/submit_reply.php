<?php
// filepath: c:\xampp\htdocs\analysis_project\funding\submit_reply.php

// 資料庫連線
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $suggestion_assignments_id = intval($_POST['suggestion_assignments_id']);
    $reply_text = trim($_POST['reply_text']);
    session_start();
    $office_id = $_SESSION['user_id']; // 從 session 中獲取處所 ID

    // 插入回覆內容到 replies 表
    $sql = "INSERT INTO replies (suggestion_assignments_id, office_id, reply_text) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL 錯誤: " . $conn->error);
    }

    $stmt->bind_param("iis", $suggestion_assignments_id, $office_id, $reply_text);
    if ($stmt->execute()) {
        echo "<script>alert('回覆已提交');</script>";
        echo "<script>window.location.href = 'office_assignments.php';</script>";
    } else {
        die("回覆提交失敗: " . $stmt->error);
    }

    $stmt->close();
}

$conn->close();
?>