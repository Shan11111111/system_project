<?php
// filepath: c:\xampp\htdocs\analysis_project\office\process_proposal.php

$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $suggestion_assignments_id = intval($_POST['suggestion_assignments_id']);
    $proposal_text = $_POST['proposal_text'];
    $funding_amount = intval($_POST['funding_amount']);
    $proposal_file = $_FILES['proposal_file'];

    // 上傳資料夾路徑
    $upload_dir = realpath(__DIR__ . '/../uploads') . '/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // 檔名處理，避免覆蓋
    $timestamp = time();
    $file_name = $timestamp . '_' . basename($proposal_file['name']);
    $full_file_path = $upload_dir . $file_name;
    $relative_file_path = 'uploads/' . $file_name;

    if (!move_uploaded_file($proposal_file['tmp_name'], $full_file_path)) {
        die("檔案上傳失敗");
    }

    // 檢查當前的 status
    $check_sql = "SELECT status FROM suggestion_assignments WHERE suggestion_assignments_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    if (!$check_stmt) {
        die("SQL 錯誤: " . $conn->error);
    }

    $check_stmt->bind_param("i", $suggestion_assignments_id);
    $check_stmt->execute();
    $check_stmt->bind_result($current_state);
    $check_stmt->fetch();
    $check_stmt->close();

    // 更新資料庫
    $sql = "UPDATE suggestion_assignments s
            SET s.proposal_text = ?, s.funding_amount = ?, s.proposal_file_path = ?, s.submitted = TRUE, s.submitted_at = NOW(), s.status = '審核中' 
            WHERE s.suggestion_assignments_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL 錯誤: " . $conn->error);
    }

    $stmt->bind_param("sisi", $proposal_text, $funding_amount, $relative_file_path, $suggestion_assignments_id);
    if ($stmt->execute()) {
        if ($current_state === '被退回') {
            echo "<script>alert('提案已成功重新提交');</script>";
        } elseif ($current_state === '草擬中') {
            echo "<script>alert('提案已成功提交');</script>";
        } else {
            echo "<script>alert('提案已成功更新');</script>";
        }
        echo "<script>window.location.href = 'office_assignments.php';</script>";
    } else {
        die("提案提交失敗: " . $stmt->error);
    }

    $stmt->close();
} else {
    die("無效的請求方法");
}

$conn->close();
?>
