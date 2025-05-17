<?php
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}

// 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = intval($_POST['request_id']);
    $end_date = $_POST['end_date'];
    $admin_response = htmlspecialchars($_POST['admin_response']);
    $action = $_POST['action'];

    if ($action === 'accept') {
        $status = '已接受';
    } elseif ($action === 'reject') {
        $status = '已拒絕';
    } else {
        die("無效的操作。");
    }

    // 更新資料庫中的申請狀態
    $stmt = $conn->prepare("UPDATE fundraising_extension_requests SET status = ?, admin_response = ? WHERE id = ?");
    if (!$stmt) {
        die("SQL prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ssi", $status, $admin_response, $request_id);

    if ($stmt->execute()) {
        if ($action === 'accept') {
            $requested_extension_date = $end_date;
            // 更新募款專案的截止日
            $update_stmt = $conn->prepare(
                "UPDATE fundraising_projects f 
                 SET f.status = CASE WHEN f.status != '已完成' THEN '進行中' ELSE f.status END, 
                     f.end_date = ? 
                 WHERE f.project_id = (SELECT fer.fundraising_project_id FROM fundraising_extension_requests fer WHERE fer.id = ?)"
            );
            if (!$update_stmt) {
                die("SQL prepare failed: " . $conn->error);
            }
            $update_stmt->bind_param("si", $requested_extension_date, $request_id);
            if ($update_stmt->execute()) {
                echo "<script>alert('募款專案的截止日已更新。');</script>";
                echo "<script>window.location.href='review_extension_requests.php';</script>";
            } else {
                echo "<script>alert('更新募款專案的截止日失敗: " . addslashes($conn->error) . "');</script>";
                echo "<script>window.location.href='review_extension_requests.php';</script>";

            }
            $update_stmt->close();
        } else {
            echo "<script>alert('已退回募資專案的申請。');</script>";
            echo "<script>window.location.href='review_extension_requests.php';</script>";
        }
    } else {
        echo "<script>alert('更新失敗: " . addslashes($conn->error) . "');</script>";
        echo "<script>window.location.href='review_extension_requests.php';</script>";

    }

    $stmt->close();

}
?>