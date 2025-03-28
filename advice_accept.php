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
        if (!empty($_FILES['image']['name'])) {
            $imageName = $_FILES['image']['name'];
            $imageTmpName = $_FILES['image']['tmp_name'];
            $fileExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($fileExt, $allowedExtensions)) {
                $imageData = file_get_contents($imageTmpName);

                // 插入圖片到 advice_images 表
                $sql_image = "INSERT INTO advice_images (img_name, img_data, advice_id) VALUES (?, ?, ?)";
                $stmt_image = mysqli_prepare($link, $sql_image);
                mysqli_stmt_bind_param($stmt_image, "ssi", $imageName, $imageData, $advice_id);

                if (!mysqli_stmt_execute($stmt_image)) {
                    die("圖片存入資料庫失敗: " . mysqli_error($link));
                }
                mysqli_stmt_close($stmt_image);
            } else {
                die("不支援的圖片格式，請上傳 JPG, JPEG, PNG 或 GIF 檔案!");
            }
        }

        echo "<div align=center><div class=message>提交成功!請稍後...</div></div>";
        header('refresh:2;url=homepage.php');
    } else {
        echo "<div align=center><div class=message>提交失敗!請稍後...</div></div>";
        header('refresh:2;url=submitadvice.php');
    }

    // 釋放資源
    mysqli_stmt_close($stmt_advice);
    mysqli_close($link);
}
?>
