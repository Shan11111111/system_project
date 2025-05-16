<?php
ob_start();
header('Content-Type: application/json');

// 引入 PDO 連線（你提供的版本）
include '../db_connection.php'; // 這裡取得 $pdo 而不是 $conn

// 解析 JSON 輸入
$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    ob_clean();
    echo json_encode(["success" => false, "message" => "無效的資料格式"]);
    exit();
}

// 抓欄位
$user_id = trim($data['userId'] ?? '');
$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$department = trim($data['department'] ?? '');
$password = trim($data['password'] ?? '');
$level = trim($data['level'] ?? '');

// 檢查欄位
if (!$user_id || !$name || !$email || !$department || !$password || !$level) {
    ob_clean();
    echo json_encode(["success" => false, "message" => "請填寫所有欄位"]);
    exit();
}

try {
    // 預備語句
    $stmt = $pdo->prepare("INSERT INTO users (user_id, password, name, level, email, department) 
                           VALUES (:user_id, :password, :name, :level, :email, :department)");

    $stmt->execute([
        ':user_id'   => $user_id,
        ':password'  => $password,
        ':name'      => $name,
        ':level'     => $level,
        ':email'     => $email,
        ':department'=> $department
    ]);

    ob_clean();
    echo json_encode(["success" => true]);

} catch (PDOException $e) {
    ob_clean();
    echo json_encode(["success" => false, "message" => "新增失敗：" . $e->getMessage()]);
}
?>
