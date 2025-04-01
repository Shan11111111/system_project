<?php
header("Content-Type: application/json"); // 確保返回 JSON 格式

session_start(); // 啟動 session
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "system_project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    // 返回錯誤並以 JSON 格式返回
    echo json_encode(["status" => "error", "message" => "資料庫連線錯誤: " . $conn->connect_error]);
    exit();
}

// 確保使用者已登入，並接收 `advice_id`
if (isset($_POST['advice_id']) && isset($_SESSION['user_id'])) {
    $advice_id = intval($_POST['advice_id']);
    $user_id = intval($_SESSION['user_id']);

    // 避免重複附議
    $check_sql = "SELECT * FROM agree_record WHERE advice_id = ? AND user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $advice_id, $user_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "你已經附議過這個建言"]);
    } else {
        // 插入附議記錄
        $sql = "INSERT INTO agree_record (advice_id, user_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $advice_id, $user_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "成功附議"]);
        } else {
            echo json_encode(["status" => "error", "message" => "錯誤: " . $stmt->error]);
        }

        $stmt->close();
    }
    $check_stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "錯誤: 未登入或缺少參數"]);
}

$conn->close();
?>