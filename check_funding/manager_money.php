<!-- 管理員傳送達標金額發布募資 -->
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<>script>alert('請先登入');</script>";
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

if ($server["request_method"] == "POST") {
    $target = $_POST['target'];
    $advice_id = $_POST['advice_id'];


    // 檢查金額是否為數字
    if (!is_numeric($target) || $target < 0) {
        echo "<script>alert('金額必須是正整數的數字');</script>";
        header("Location: homepage.php");
        exit();
    }


    // 連接資料庫
    $link = new mysqli("localhost", "root", "", "system_project");
    if ($link->connect_error) {
        die("Connection failed: " . $link->connect_error);
    }

    // 插入資料
    $stmt = $link->prepare("INSERT INTO funding (target, advice_id) VALUES (?, ?)");
    $stmt->bind_param("is", $target, $advice_id);

    if ($stmt->execute()) {
        // 更新 advice 表的 state 欄位
        $update_stmt = $link->prepare("UPDATE advice SET advice_state = '募資中' WHERE advice_id = ?");
        $update_stmt->bind_param("s", $advice_id);

        if ($update_stmt->execute()) {
            echo "<script>alert('新增成功，狀態已更新');</script>";
            header("Location: homepage.php");
            exit();
        } else {
            echo "<script>alert('新增成功，但狀態更新失敗');</script>";
        }

        echo "<script>alert('新增成功');</script>";
        echo "<script>window.location.href='homepage.php';</script>";
    } else {
        echo "<script>alert('新增失敗');</script>";
        echo "<script>window.location.href='homepage.php';</script>";
    }
}



?>