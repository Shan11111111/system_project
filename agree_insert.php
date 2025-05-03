<?php
session_start(); // 啟動 session，確保 $_SESSION["user_id"] 可用

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION["user_id"])) {
        die("錯誤：未登入");
    }

    $advice_id = $_POST["advice_id"];
    $user_id = $_SESSION["user_id"];

    // 連接資料庫
    $conn = new mysqli("localhost", "root", "", "system_project");
    if ($conn->connect_error) {
        die("連接失敗：" . $conn->connect_error);
    }

    // 檢查是否已經存在相同 user_id 和 advice_id，避免重複插入
    $check_stmt = $conn->prepare("SELECT COUNT(*) FROM agree_record WHERE advice_id = ? AND user_id = ?");
    $check_stmt->bind_param("ii", $advice_id, $user_id);
    $check_stmt->execute();
    $check_stmt->bind_result($count);
    $check_stmt->fetch();
    $check_stmt->close();


    ?>
    <?php
    if ($count > 0) {

        ?>

        <!DOCTYPE html>
        <html lang="zh-Hant">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>動態網頁專案</title>
            <link rel="stylesheet" href="css/styles.css">
        </head>

        <body>
            <div class="logo-container">
                <img src="./img/c01.png" alt="Logo" class="logo" id="logo">

                <p id="myobject">您已附議過了~~請勿重複附議!</p>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const logo = document.getElementById("logo");
                    const myobject = document.getElementById("myobject");

                    // Start the animation
                    logo.classList.add("rotate");

                    // After 3 seconds, fade out the logo and redirect
                    setTimeout(() => {
                        logo.classList.add("fade-out");
                        myobject.classList.add("fade-out");
                        setTimeout(() => {
                            window.location.href = "advice_detail.php?advice_id=<?php echo urlencode($advice_id); ?>"; // Redirect to homepage.php
                        }, 1000); // Wait for fade-out duration before redirecting
                    }, 1000); // Duration of rotation
                });
            </script>
        </body>

        </html>


        <?php
        exit(); // 確保程式不會繼續執行
    }



    // 插入數據
    $stmt = $conn->prepare("INSERT INTO agree_record (advice_id, user_id) VALUES (?, ?)");
    $stmt->bind_param("ss", $advice_id, $user_id);

    if ($stmt->execute()) {
        // 更新 advice 表中的 agree 欄位
        $update_stmt = $conn->prepare("UPDATE advice SET agree = agree + 1 WHERE advice_id = ?");
        $update_stmt->bind_param("i", $advice_id);
        $update_stmt->execute();
        $update_stmt->close();

        // 檢查更新後的 agree 值是否等於 3
        $check_agree_stmt = $conn->prepare("SELECT agree FROM advice WHERE advice_id = ?");
        $check_agree_stmt->bind_param("i", $advice_id);
        $check_agree_stmt->execute();
        $check_agree_stmt->bind_result($current_agree);
        $check_agree_stmt->fetch();
        $check_agree_stmt->close();

        if ($current_agree == 3) {
            // 插入狀態到狀態表
            $insert_status_stmt = $conn->prepare("INSERT INTO advice_state(advice_id, content) VALUES (?, ?)");
            $status = "附議達標";
            $insert_status_stmt->bind_param("is", $advice_id, $status);
            $insert_status_stmt->execute();
            $insert_status_stmt->close();
        }

        // 顯示成功訊息
        ?>
        <!DOCTYPE html>
        <html lang="zh-Hant">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>處理中...</title>
            <link rel="stylesheet" href="css/styles.css">
        </head>

        <body>
            <div class="logo-container">
                <img src="./img/c01.png" alt="Logo" class="logo" id="logo">

                <p id="myobject">收到您的附議了! 附議成功!</p>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const logo = document.getElementById("logo");
                    const myobject = document.getElementById("myobject");

                    // Start the animation
                    logo.classList.add("rotate");

                    // After 3 seconds, fade out the logo and redirect
                    setTimeout(() => {
                        logo.classList.add("fade-out");
                        myobject.classList.add("fade-out");
                        setTimeout(() => {
                            window.location.href = "advice_detail.php?advice_id=<?php echo urlencode($advice_id); ?>";
                        }, 1000); // Wait for fade-out duration before redirecting
                    }, 1000); // Duration of rotation
                });
            </script>
        </body>

        </html>
        <?php
    } else {
        echo "錯誤：" . $stmt->error;
        echo "<script>alert('錯誤：無法附議');</script>";
        echo "<script>window.location.href = 'advice_detail.php?advice_id=" . urlencode($advice_id) . "';</script>";

    }

    $stmt->close();
    $conn->close();
}
?>