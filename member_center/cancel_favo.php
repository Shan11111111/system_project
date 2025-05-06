<?php
session_start();
header('Content-Type: application/json');
include('../db_connection.php');

$user_id = $_SESSION['user_id'] ?? null;
$advice_id = $_GET['advice_id'] ?? null;

if (!$user_id || !$advice_id) {
    echo json_encode(['success' => false, 'message' => '缺少參數或未登入']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM collection WHERE user_id = ? AND advice_id = ?");
    $stmt->execute([$user_id, $advice_id]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '刪除失敗: ' . $e->getMessage()]);
}
