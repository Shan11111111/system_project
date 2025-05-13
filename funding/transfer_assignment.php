
<?php
session_start();
if (!isset($_POST['suggestion_assignments_id']) || !isset($_POST['new_office_id'])) {
    header("Location: office_assignments.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}

$suggestion_assignments_id = $_POST['suggestion_assignments_id'];
$new_office_id = $_POST['new_office_id'];

// 更新 office_id
$stmt = $conn->prepare("UPDATE suggestion_assignments SET office_id = ? WHERE suggestion_assignments_id = ?");
$stmt->bind_param("si", $new_office_id, $suggestion_assignments_id);
$stmt->execute();
$stmt->close();
$conn->close();
echo "<script>alert('轉移成功!)</script>";
header("Location: office_assignments.php?msg=轉移成功");
exit;
?>
