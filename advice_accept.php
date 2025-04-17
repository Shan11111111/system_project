<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 確保使用者已登入
    if (!isset($_SESSION['user_id'])) {
        die("請先登入!");
    }
    $user_id = $_SESSION['user_id'];

    // 檢查輸入
    $advice_title = isset($_POST['advice_title']) ? trim($_POST['advice_title']) : '';
    $advice_content = isset($_POST['advice_content']) ? trim($_POST['advice_content']) : '';
    $category = isset($_POST['category']) ? trim($_POST['category']) : '';

    if (empty($advice_title) || empty($advice_content) || empty($category)) {
        die("所有欄位皆必填!");
    }

    // 資料庫連線
    $link = mysqli_connect('localhost', 'root', '', 'system_project');
    if (!$link) {
        die("資料庫連線失敗: " . mysqli_connect_error());
    }

    // 插入建言 (advice) 資料
    $sql_advice = "INSERT INTO advice (user_id, advice_title, advice_content, category) VALUES (?, ?, ?, ?)";
    $stmt_advice = mysqli_prepare($link, $sql_advice);
    mysqli_stmt_bind_param($stmt_advice, "isss", $user_id, $advice_title, $advice_content, $category);

    if (mysqli_stmt_execute($stmt_advice)) {
        $advice_id = mysqli_insert_id($link); // 獲取剛插入的 advice_id

        // 如果有上傳圖片
        if (!empty($_FILES['file']['name'])) {
            $imageName = $_FILES['file']['name'];
            $imageTmpName = $_FILES['file']['tmp_name'];
            $fileExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($fileExt, $allowedExtensions)) {
                // 設定上傳目錄
                $uploadDir = 'uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true); // 建立目錄（如果不存在）
                }

                // 生成唯一檔案名稱
                $uniqueFileName = uniqid('img_', true) . '.' . $fileExt;
                $uploadPath = $uploadDir . $uniqueFileName;

                // 移動檔案到上傳目錄
                if (move_uploaded_file($imageTmpName, $uploadPath)) {
                    // 插入圖片路徑到 advice_images 表
                    $sql_image = "INSERT INTO advice_image (img_name, img_path, advice_id) VALUES (?, ?, ?)";
                    $stmt_image = mysqli_prepare($link, $sql_image);
                    mysqli_stmt_bind_param($stmt_image, "ssi", $imageName, $uploadPath, $advice_id);

                    if (mysqli_stmt_execute($stmt_image)) {
                        // echo "圖片已成功存入資料夾並記錄到資料庫！";
                    } else {
                        die("圖片記錄到資料庫失敗: " . mysqli_error($link));
                    }

                    mysqli_stmt_close($stmt_image);
                } else {
                    die("圖片上傳失敗！");
                }
            } else {
                die("不支援的圖片格式，請上傳 JPG, JPEG, PNG 或 GIF 檔案!");
            }
        }
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

                <p id="myobject">上傳成功! 資料處理中~~~</p>
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
                            window.location.href = "homepage.php"; // Redirect to homepage.php
                        }, 1000); // Wait for fade-out duration before redirecting
                    }, 3000); // Duration of rotation
                });
            </script>
        </body>

        </html>

        <?php
    } else {
        echo "<div align=center><div class=message>提交失敗!請稍後...</div></div>";
        header('refresh:2;url=submitadvice.php');
    }

    // 釋放資源
    mysqli_stmt_close($stmt_advice);
    mysqli_close($link);
}
?>