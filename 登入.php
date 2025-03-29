<?php
session_start();

// 資料庫配置
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "user_db";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}

// 使用者註冊
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = ;
    $password = password_hash(, PASSWORD_DEFAULT);
    
    if (!empty($username) && !empty($_POST['password'])) {
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        if ($stmt->execute()) {
            echo "註冊成功";
        } else {
            echo "註冊失敗: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "請填寫所有欄位";
    }
}

// 使用者登入
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = ;
    $password = ;
    
    if (!empty($username) && !empty($password)) {
        $sql = "SELECT id, password FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                echo "登入成功";
            } else {
                echo "密碼錯誤";
            }
        } else {
            echo "用戶不存在";
        }
        $stmt->close();
    } else {
        echo "請輸入帳號與密碼";
    }
}

// 使用者登出
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
