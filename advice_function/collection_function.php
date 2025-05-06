<?php
session_start();
require_once('../db_connection.php'); 

$user_id = $_SESSION['user_id'] ?? null;
$advice_id = $_POST['advice_id'] ?? null;

// 若未登入導回原頁
if (!$user_id || !$advice_id) {
    echo "<script>alert('請先登入'); window.location.href = '../advice_detail.php?advice_id=" . urlencode($advice_id) . "';</script>";
    exit;
}


// 檢查是否已經收藏
$sql = "SELECT * FROM collection WHERE user_id = ? AND advice_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $advice_id]);
$exists = $stmt->fetch();

if ($exists) {
    // 已收藏 → 取消收藏
    $delete = $pdo->prepare("DELETE FROM collection WHERE user_id = ? AND advice_id = ?");
    $delete->execute([$user_id, $advice_id]);
} else {
    // 尚未收藏 → 新增收藏
    $insert = $pdo->prepare("INSERT INTO collection (advice_id, user_id) VALUES (?, ?)");
    $insert->execute([$advice_id, $user_id]);
}

// 回建言詳情頁
header("Location: ../advice_detail.php?advice_id=" . urlencode($advice_id));

exit;
?>
