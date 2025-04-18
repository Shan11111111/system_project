<!-- 發送留言 -->

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('請先登入');</script>";
    echo "<script>window.location.href='login.php';</script>";
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comment_content = $_POST['comment_content'];
    $advice_id = $_POST['advice_id'];
    $reply_to = isset($_POST['reply_to']) ? $_POST['reply_to'] : null; // 新增 reply_to
    $user_id = $_SESSION['user_id'];

    // 檢查留言是否為空
    if (empty($comment_content)) {
        echo "<script>alert('留言內容不能為空');</script>";
        header("Location: homepage.php");
        exit();
    }

    // 連接資料庫
    $pdo = new mysqli("localhost", "root", "", "system_project");
    if ($pdo->connect_error) {
        die("Connection failed: " . $pdo->connect_error);
    }

    // 檢查留言內容是否為字串
    if (!is_string($comment_content)) {
        echo "<script>alert('留言內容必須是字串');</script>";
        header("Location: homepage.php");
        exit();
    }
    // 檢查留言內容長度
    if (strlen($comment_content) > 255) {
        echo "<script>alert('留言內容不能超過255個字元');</script>";
        header("Location: homepage.php");
        exit();
    }
    // 檢查留言內容是否包含特殊字元
    if (preg_match('/[\'\"\\\]/', $comment_content)) {
        echo "<script>alert('留言內容不能包含特殊字元');</script>";
        header("Location: homepage.php");
        exit();
    }
    // 檢查留言內容是否包含HTML標籤
    if (strip_tags($comment_content) != $comment_content) {
        echo "<script>alert('留言內容不能包含HTML標籤');</script>";
        header("Location: homepage.php");
        exit();
    }

    // 使用 mysqli_stmt 進行資料插入，新增 reply_to 欄位
    $stmt = $pdo->prepare("INSERT INTO comments (comment_content, advice_id, user_id, reply_to) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siis", $comment_content, $advice_id, $user_id, $reply_to);
    $stmt->execute();

    // 使用 mysqli 的 affected_rows
    if ($stmt->affected_rows > 0) {
        echo "<script>alert('留言成功');</script>";
        // echo "<script>window.location.href='homepage.php';</script>";
    } else {
        echo "<script>alert('留言失敗');</script>";
        // echo "<script>window.location.href='homepage.php';</script>";
    }
    $stmt->close();
    $pdo->close();
}
?>