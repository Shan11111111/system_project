<?php
// filepath: c:\xampp\htdocs\analysis_project\office\process_proposal.php

// 資料庫連線
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $suggestion_assignments_id = intval($_POST['suggestion_assignments_id']);
    $proposal_text = $_POST['proposal_text'];
    $funding_amount = intval($_POST['funding_amount']);
    $proposal_file = $_FILES['proposal_file'];

    // 檔案上傳處理
    $upload_dir = "../uploads/";
    $path="uploads/";
    $file_path = $path . basename($proposal_file['name']);
    if (!move_uploaded_file($proposal_file['tmp_name'], $file_path)) {
        die("檔案上傳失敗");
    }

    // 更新提案內容
    $sql = "UPDATE suggestion_assignments 
            SET proposal_text = ?, funding_amount = ?, proposal_file_path = ?, submitted = TRUE, submitted_at = NOW(), status = '審核中' 
            WHERE suggestion_assignments_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL 錯誤: " . $conn->error);
    }

    $stmt->bind_param("sisi", $proposal_text, $funding_amount, $file_path, $suggestion_assignments_id);
    if ($stmt->execute()) {
        echo "<script>alert('提案已成功重新提交');</script>";
        echo "<script>window.location.href = 'office_assignments.php';</script>";
    } else {
        die("提案重新提交失敗: " . $stmt->error);
    }

    $stmt->close();
} else {
    die("無效的請求方法");
}

$conn->close();
?>