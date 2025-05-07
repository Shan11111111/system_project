<!-- filepath: c:\xampp\htdocs\analysis_project\funding\profile_module.php -->
<?php
// 獲取使用者名稱
$stmt = $conn->prepare("SELECT name, department FROM users WHERE user_id = ?");
if (!$stmt) {
    die("SQL 準備失敗：" . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $department);
$stmt->fetch();
$stmt->close();
?>
<div class="header">
    <div class="profile">
        <div class="profile-info" onclick="toggleDropdown()">
            <p><?php echo htmlspecialchars($user_id); ?> <?php echo htmlspecialchars($name); ?> / <?php echo htmlspecialchars($department); ?></p>
            <img src="../img/logo.png" alt="個人頭像">
        </div>
        <div id="dropdownMenu" class="dropdown">
            <a href="edit_profile.php">編輯個人資料</a>
            <a href="../logout.php">登出</a>
        </div>
    </div>
</div>