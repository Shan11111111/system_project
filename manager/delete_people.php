<?php
// 資料庫連線設定
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "system_project"; // 替換為您的資料庫名稱

// 建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 檢查是否有提交刪除請求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];

    if (!empty($user_id) && is_numeric($user_id)) {
        // 使用參數化查詢來防止 SQL 注入
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            echo "<script>alert('使用者 ID $user_id 已成功刪除');</script>";
            echo "<script>window.location.href='people_manager.php';</script>";

        } else {
            echo "刪除失敗: " . $stmt->error;
            echo "<script>window.location.href='people_manager.php';</script>";

        }

        $stmt->close();
    } else {
        echo "無效的使用者 ID $user_id";
    }
} else {
    echo "未提交刪除請求";
    echo "<script>alert('未提交刪除請求');</script>";
    echo "<script>window.location.href='people_manager.php';</script>";
}

$conn->close();

// 返回管理頁面
// header("Location: people_manager.php");
exit();
?>