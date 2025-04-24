<?php
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

    // 獲取 advice_id
    $advice_query = "SELECT advice_id FROM suggestion_assignments WHERE suggestion_assignments_id = ?";
    $advice_stmt = $conn->prepare($advice_query);
    if (!$advice_stmt) {
        die("SQL 錯誤: " . $conn->error);
    }
    $advice_stmt->bind_param("i", $suggestion_assignments_id);
    $advice_stmt->execute();
    $advice_result = $advice_stmt->get_result();
    if ($advice_result->num_rows > 0) {
        $advice_row = $advice_result->fetch_assoc();
        $advice_id = $advice_row['advice_id'];
    } else {
        echo "<script>alert('無法找到對應的建言 ID！'); window.location.href='office_assignments.php';</script>";
        exit;
    }
    $advice_stmt->close();

    // 插入回覆內容到 replies 表
    $sql = "INSERT INTO replies (suggestion_assignments_id, office_id, reply_text) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL 錯誤: " . $conn->error);
    }

    $stmt->bind_param("iis", $suggestion_assignments_id, $office_id, $reply_text);
    if ($stmt->execute()) {
        // 檢查是否已存在相同的狀態
        $check_state_sql = "SELECT 1 FROM advice_state WHERE advice_id = ? AND content = '校方已回應'";
        $check_state_stmt = $conn->prepare($check_state_sql);
        if ($check_state_stmt === false) {
            die("SQL 語句準備失敗: " . $conn->error);
        }
        $check_state_stmt->bind_param("i", $advice_id);
        $check_state_stmt->execute();
        $check_state_result = $check_state_stmt->get_result();

        if ($check_state_result->num_rows === 0) {
            // 如果不存在，插入新的記錄
            $insert_state_sql = "INSERT INTO advice_state (advice_id, content, state_time) VALUES (?, '校方已回應', NOW())";
            $insert_state_stmt = $conn->prepare($insert_state_sql);
            if ($insert_state_stmt === false) {
                die("SQL 語句準備失敗: " . $conn->error);
            }
            $insert_state_stmt->bind_param("i", $advice_id);
            if ($insert_state_stmt->execute()) {
                echo "<script>alert('回覆提交成功，狀態已記錄！'); window.location.href='office_assignments.php';</script>";
            } else {
                echo "<script>alert('回覆提交成功，但狀態記錄失敗！'); window.location.href='office_assignments.php';</script>";
            }
            $insert_state_stmt->close();
        } else {
            // 如果已存在，提示用戶
            echo "<script>alert('回覆提交成功，狀態已存在！'); window.location.href='office_assignments.php';</script>";
        }
        $check_state_stmt->close();
    } else {
        echo "<script>alert('回覆提交失敗，請稍後再試！'); window.location.href='office_assignments.php';</script>";
    }

    $stmt->close();
}

$conn->close();
?>