<?php
// add_user.php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

// 基本欄位驗證
if (
    !isset($data['userId'], $data['name'], $data['email'], $data['department'], $data['level']) ||
    empty(trim($data['userId'])) ||
    empty(trim($data['name'])) ||
    empty(trim($data['email'])) ||
    empty(trim($data['department'])) ||
    empty(trim($data['level']))
) {
    echo json_encode(['success' => false, 'message' => '所有欄位皆為必填']);
    exit;
}

// 資料庫連線
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => '資料庫連線失敗']);
    exit;
}

// 確認 user_id 是否已存在，避免重複
$check = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
$check->bind_param("s", $data['userId']);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => '此帳號已存在']);
    $check->close();
    $conn->close();
    exit;
}
$check->close();

// 預設密碼同 user_id
$password = $data['userId'];

$stmt = $conn->prepare("INSERT INTO users (user_id, password, name, email, department, level) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $data['userId'], $password, $data['name'], $data['email'], $data['department'], $data['level']);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => '新增失敗：' . $stmt->error]);
}

$stmt->close();
$conn->close();
