<!-- 捐贈表單傳值 -->
<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $funding_id = $_POST['funding_id'];
    $people_name = $_POST['people_name'];
    $donate_money = $_POST['donate_money'];
    $email= $_POST['email'];
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

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('請輸入有效的電子郵件地址');</script>";
    echo "<script>window.location.href='../funding_detail.php?id=$funding_id';</script>";
    exit();
}

$stmt = $link->prepare("INSERT INTO donation_record (donor, donation_amount, project_id, email) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    die("Prepare failed: " . $link->error);
}

$stmt->bind_param("siis", $people_name, $donate_money, $funding_id, $email);
if ($stmt->execute()) {
    echo "<script>alert('捐款成功');</script>";
    echo "<script>window.location.href='../homepage.php';</script>";
} else {
    echo "<script>alert('捐款失敗');</script>";
    echo "<script>window.location.href='../homepage.php';</script>";
}
$stmt->close();
$link->close();

?>