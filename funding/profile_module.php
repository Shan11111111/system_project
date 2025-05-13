<!-- filepath: c:\xampp\htdocs\analysis_project\funding\profile_module.php -->
<?php
// 啟動 Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// 確保使用者已登入
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "system_project";
// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);
// 檢查連線
if ($conn->connect_error) {
    die("資料庫連線失敗：" . $conn->connect_error);
}
// 獲取使用者 ID
$user_id = $_SESSION['user_id'];
// 檢查使用者 ID 是否有效
if (!is_numeric($user_id)) {
    die("無效的使用者 ID");
}
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
            <!-- <a href="edit_profile.php">編輯個人資料</a> -->
            <a href="../logout.php">登出</a>
        </div>
    </div>
</div>