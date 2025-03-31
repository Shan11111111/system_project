<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "system_project";

// 檢查資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die('資料庫連線錯誤: ' . $conn->connect_error);
}

// 檢查是否有傳遞 'advice_id' 參數
if (isset($_POST['advice_id'])) {
    $advice_id = intval($_POST['advice_id']); // 確保 'advice_id' 是整數

    // 準備 SQL 查詢語句
    $sql = "INSERT INTO agree_record (advice_id) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $advice_id);

    // 執行 SQL 查詢，並回應結果
    if ($stmt->execute()) {
        echo "成功附議";
    } else {
        echo "錯誤: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
