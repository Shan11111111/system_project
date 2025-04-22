<?php
// filepath: c:\xampp\htdocs\analysis_project\manager\process_assignment.php

// 啟用 session（如果需要驗證使用者身份）
session_start();

// 資料庫連線
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}

// 檢查是否有提交表單
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 接收表單資料
    $advice_id = isset($_POST['advice_id']) ? intval($_POST['advice_id']) : 0;
    $office_id = isset($_POST['office']) ? intval($_POST['office']) : 0;

    // 驗證資料
    if ($advice_id <= 0 || $office_id <= 0) {
        die("無效的建言 ID 或處所 ID");
    }

    // 插入分派資料到 suggestion_assignments 表
    $sql = "INSERT INTO suggestion_assignments (advice_id, office_id, status, submitted) 
            VALUES (?, ?, '草擬中', FALSE)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL 錯誤: " . $conn->error);
    }

    $stmt->bind_param("ii", $advice_id, $office_id);
    if ($stmt->execute()) {
        // 更新建言狀態為「已分派」
        // 這裡假設建言狀態的欄位名稱為 advice_state
        $sql= "UPDATE advice SET advice_state = '已分派' WHERE advice_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("SQL 錯誤: " . $conn->error);
        }
        $stmt->bind_param("i", $advice_id);
        if (!$stmt->execute()) {
            die("更新建言狀態失敗: " . $stmt->error);
        }
        // 分派成功，重定向回建言管理頁面
        echo "<script>alert('建言已成功分派給處所');</script>";
        echo "<script>window.location.href = '../manager/advice_manager.php';</script>";
    } else {
        // 分派失敗
        die("分派失敗: " . $stmt->error);
    }

    $stmt->close();
} else {
    // 如果不是 POST 請求，顯示錯誤
    die("無效的請求方法");
}

// 關閉資料庫連線
$conn->close();
?>