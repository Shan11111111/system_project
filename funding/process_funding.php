<?php
// 資料庫連線設定
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "system_project"; // 替換為您的資料庫名稱

// 建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 檢查是否有提交表單
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $advice_id = $_POST['advice_id'];
    $target = $_POST['target'];

    if ($advice_id > 0 && $target >= 0) {
        // 檢查是否已存在相同的 advice_id
        $check_stmt = $conn->prepare("SELECT COUNT(*) FROM funding WHERE advice_id = ?");
        $check_stmt->bind_param("i", $advice_id);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();

        if ($count > 0) {
            echo "建言 ID $advice_id 已經存在於 funding 表中，無法重複插入";
        } else {
            // 插入資料到 funding 表
            $stmt = $conn->prepare("INSERT INTO funding (advice_id, target) VALUES (?, ?)");
            $stmt->bind_param("id", $advice_id, $target);

            if ($stmt->execute()) {
                // 更新 advice 表的 funding_state
                $update_stmt = $conn->prepare("UPDATE advice SET advice_state = ? WHERE advice_id = ?");
                $funding_state = '募資中';
                $update_stmt->bind_param("si", $funding_state, $advice_id);

                if ($update_stmt->execute()) {

?>

<!DOCTYPE html>
        <html lang="zh-Hant">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>處理中...</title>
            <link rel="stylesheet" href="../css/styles.css">
        </head>

        <body>
            <div class="logo-container">
                <img src="../img/c01.png" alt="Logo" class="logo" id="logo">

                <p id="myobject">填寫成功! 資料處理中~~~</p>
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
                            window.location.href = "funding_check.php"; // Redirect to homepage.php
                        }, 1000); // Wait for fade-out duration before redirecting
                    }, 3000); // Duration of rotation
                });
            </script>
        </body>

        </html>
<?php
                    echo "建言 ID $advice_id 的金額 $target 已成功插入，並更新為募資中";
                } else {
?>

<!DOCTYPE html>
        <html lang="zh-Hant">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>處理中...</title>
            <link rel="stylesheet" href="./css/styles.css">
        </head>

        <body>
            <div class="logo-container">
                <img src="./img/c01.png" alt="Logo" class="logo" id="logo">

                <p id="myobject">填寫錯誤! 重新導向中~~~</p>
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
                            window.location.href = "funding_check.php"; // Redirect to homepage.php
                        }, 1000); // Wait for fade-out duration before redirecting
                    }, 3000); // Duration of rotation
                });
            </script>
        </body>

        </html>

<?php
                    
                    echo "建言 ID $advice_id 的更新失敗: " . $update_stmt->error;
                }

                $update_stmt->close();
            } else {
                echo "建言 ID $advice_id 插入失敗: " . $stmt->error;
            }

            $stmt->close();
        }
    } else {
        echo "無效的輸入";
    }
} else {
    echo "未提交任何資料";
}

$conn->close();
?>