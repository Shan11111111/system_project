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
    $advice_id = $_POST['advice_id'];

    if (!empty($advice_id) && is_numeric($advice_id)) {
        // 使用參數化查詢來防止 SQL 注入
        $stmt = $conn->prepare("DELETE FROM advice WHERE advice_id = ?");
        $stmt->bind_param("i", $advice_id);

        if ($stmt->execute()) {
            // echo "建言 ID $advice_id 已成功刪除";
            echo "<script>alert('建言 ID $advice_id 已成功刪除');</script>";
            echo "<script>window.location.href='advice_manager.php';</script>";

        } else {
            echo "刪除失敗: " . $stmt->error;
            echo "<script>window.location.href='advice_manager.php';</script>";

        }

        $stmt->close();
    } else {
        echo "無效的建言 ID";
        echo "<script>window.location.href='advice_manager.php';</script>";

    }
} else {
    echo "未提交刪除請求";
    echo "<script>window.location.href='advice_manager.php';</script>";

}

$conn->close();

// 返回管理頁面
// header("Location: advice_manager.php");
exit();
?>