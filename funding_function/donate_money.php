<!-- 捐贈表單傳值 -->
<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $funding_id = $_POST['funding_id'];
    $people_name = !empty($_POST['people_name']) ? $_POST['people_name'] : '匿名';
    $donate_money = $_POST['donate_money'];
    $email = $_POST['email'];
    $user_id = $_SESSION['user_id'] ?? null;
} else {
    echo "錯誤傳送";
    header("Location: homepage.php");
    exit();
}

$link = new mysqli("localhost", "root", "", "system_project");

if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}
// 檢查捐款金額是否為數字
if (!is_numeric($donate_money) || $donate_money < 0) {
    echo "<script>alert('金額必須是正整數的數字');</script>";
    echo "<script>window.location.href='../funding_detail.php?id=$funding_id';</script>";
    exit();
}


if (!empty($email)) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('請輸入有效的電子郵件地址'); window.location.href='../funding_detail.php?id=$funding_id';</script>";
        exit();
    }
}



$stmt = $link->prepare("INSERT INTO donation_record (donor, donation_amount, project_id, email,user_id) VALUES (?, ?, ?, ?, ?)");
if (!$stmt) {
    die("Prepare failed: " . $link->error);
}

$stmt->bind_param("siisi", $people_name, $donate_money, $funding_id, $email, $user_id);
if ($stmt->execute()) {

    // 假設目標金額儲存在 fundraising_projects 表的 funding_goal 欄位
    $target_stmt = $link->prepare("SELECT funding_goal FROM fundraising_projects WHERE project_id = ?");
    if (!$target_stmt) {
        echo "<script>alert('準備失敗');</script>";
        die("Prepare failed: " . $link->error);
    }
    
    $target_stmt->bind_param("i", $funding_id);
    $target_stmt->execute();
    $target_stmt->bind_result($target_amount);
    $target_stmt->fetch();
    $target_stmt->close();

    // 獲取目前的總捐款金額
    $total_stmt = $link->prepare("SELECT SUM(donation_amount) FROM donation_record WHERE project_id = ?");
    if (!$total_stmt) {
        echo "<script>alert('準備失敗');</script>";
        die("Prepare failed: " . $link->error);
    }
    $total_stmt->bind_param("i", $funding_id);
    $total_stmt->execute();
    $total_stmt->bind_result($total_donations);
    $total_stmt->fetch();
    $total_stmt->close();

    // 檢查是否達標
    if ($total_donations >= $target_amount) {
        $update_stmt = $link->prepare("UPDATE fundraising_projects SET status = '已完成' WHERE project_id = ?");
        if (!$update_stmt) {
            echo "<script>alert('更新失敗');</script>";
            die("Prepare failed: " . $link->error);
        }
        $update_stmt->bind_param("i", $funding_id);
        $update_stmt->execute();
        $update_stmt->close();
    }
    echo "<script>alert('捐款成功');</script>";
    echo "<script>window.location.href='../homepage.php';</script>";
} else {
    echo "<script>alert('捐款失敗');</script>";
    // echo "<script>window.location.href='../homepage.php';</script>";
}
$stmt->close();
$link->close();

?>