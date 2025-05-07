<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    die("錯誤：未登入");
}

$user_id = $_SESSION["user_id"];

// 檢查是否是 POST 請求
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("無效的請求方法");
}

// 檢查是否有傳入 advice_id
if (!isset($_POST['advice_id'])) {
    die("錯誤：未提供建言 ID");
}

$advice_id = intval($_POST['advice_id']);

// 連接資料庫
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("連接失敗：" . $conn->connect_error);
}

// 檢查是否已經認領
$check_stmt = $conn->prepare("SELECT COUNT(*) FROM suggestion_assignments WHERE advice_id = ? AND office_id = ?");
if (!$check_stmt) {
    die("SQL 準備失敗：" . $conn->error);
}

$check_stmt->bind_param("ii", $advice_id, $user_id);
$check_stmt->execute();
$check_stmt->bind_result($count);
$check_stmt->fetch();
$check_stmt->close();

if ($count > 0) {
    echo "<script>alert('該建言已被分配了！您無法認領!');</script>";
    echo "<script>window.location.href = 'adapt.php';</script>";
    exit();
}

// 插入認領資料
$insert_stmt = $conn->prepare("INSERT INTO suggestion_assignments (advice_id, office_id, status, submitted) 
            VALUES (?, ?, '草擬中', FALSE)");
if (!$insert_stmt) {
    die("SQL 準備失敗：" . $conn->error);
}

$insert_stmt->bind_param("ii", $advice_id, $user_id);

if ($insert_stmt->execute()) {
    // 更新建言狀態為「已分派」
    $update_stmt = $conn->prepare("UPDATE advice SET advice_state = '已分派' WHERE advice_id = ?");
    if (!$update_stmt) {
        die("SQL 準備失敗：" . $conn->error);
    }

    $update_stmt->bind_param("i", $advice_id);
    if ($update_stmt->execute()) {
        echo "<script>alert('建言已成功分派給處所');</script>";
    } else {
        die("更新建言狀態失敗：" . $update_stmt->error);
    }

    $update_stmt->close();
} else {
    die("分派失敗：" . $insert_stmt->error);
}

$insert_stmt->close();
$conn->close();

// 重定向回 adapt.php
echo "<script>window.location.href = 'adapt.php';</script>";
exit();
?>