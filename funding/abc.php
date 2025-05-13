<?php
// abc.php 範例
session_start();
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// ...資料庫連線與 session 啟動...
if (isset($_POST['advice_id']) && is_array($_POST['advice_id'])) {
    $advice_ids = $_POST['advice_id'];
    $office_id = $_SESSION['user_id']; // 請確認這是 office_id
    foreach ($advice_ids as $advice_id) {
        // 先檢查是否已存在
        $check = $conn->prepare("SELECT 1 FROM suggestion_assignments WHERE advice_id = ?");
        $check->bind_param("i", $advice_id);
        $check->execute();
        $check->store_result();
        if ($check->num_rows == 0) {
            // 不存在才插入
            $stmt = $conn->prepare("INSERT INTO suggestion_assignments (advice_id, office_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $advice_id, $office_id);
            if ($stmt->execute()) {
                // 插入成功後，更新 advice 狀態
                $update = $conn->prepare("UPDATE advice SET advice_state = '已分派' WHERE advice_id = ?");
                $update->bind_param("i", $advice_id);
                $update->execute();
                $update->close();
            } else {
                echo "插入失敗：" . $stmt->error;
            }
            $stmt->close();
        }
        $check->close();
    }
    echo "<script>alert('全部達標建言已加入提案！');window.location.href='office_assignments.php';</script>";
} else {
    echo "<script>alert('沒有可加入的建言。');window.location.href='office_assignments.php';</script>";
}
$conn->close();
?>