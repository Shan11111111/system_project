<?php
session_start();

// 通用檔案上傳函式
function uploadFile($file, $allowedExtensions, $uploadDir, $advice_id, $tableName, $link) {
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (in_array($fileExt, $allowedExtensions)) {
        // 確保上傳目錄存在
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // 生成唯一檔案名稱
        $uniqueFileName = uniqid('', true) . '.' . $fileExt;
        $uploadPath = $uploadDir . $uniqueFileName;

        // 移動檔案到上傳目錄
        if (move_uploaded_file($fileTmpName, $uploadPath)) {
            // 插入檔案路徑到對應的資料表
            $sql = "INSERT INTO $tableName (file_name, file_path, advice_id) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($link, $sql);

            // 檢查 mysqli_prepare 是否成功
            if (!$stmt) {
                die("SQL 準備失敗: " . mysqli_error($link));
            }

            mysqli_stmt_bind_param($stmt, "ssi", $fileName, $uploadPath, $advice_id);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                return true; // 上傳成功
            } else {
                mysqli_stmt_close($stmt);
                die("檔案記錄到資料庫失敗: " . mysqli_error($link));
            }
        } else {
            die("檔案上傳失敗！");
        }
    } else {
        die("不支援的檔案格式！");
    }
}

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

                <p id="myobject">請填寫全部! 重新導向中~~~</p>
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
                            window.location.href = "submitadvice.php"; // Redirect to homepage.php
                        }, 1000); // Wait for fade-out duration before redirecting
                    }, 3000); // Duration of rotation
                });
            </script>
        </body>

        </html>
        <?php
        exit;
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

        // 處理圖片上傳
        if (!empty($_FILES['file']['name'])) {
            if (!uploadFile($_FILES['file'], ['jpg', 'jpeg', 'png', 'gif'], 'uploads/', $advice_id, 'advice_image', $link)) {
                die("圖片上傳失敗！");
            }
        }

        // 處理文件上傳
        if (!empty($_FILES['file2']['name'])) {
            if (!uploadFile($_FILES['file2'], ['pdf', 'doc', 'docx', 'ppt', 'pptx'], 'file_upload/', $advice_id, 'files', $link)) {
                die("文件上傳失敗！");
            }
        }

        // 提交成功後，顯示上傳成功的訊息並轉跳到首頁
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