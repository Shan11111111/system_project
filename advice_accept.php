<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 檢查輸入是否存在，若存在則去除前後空白後取得字串，避免空白造成查詢錯誤
    $advice_title = isset($_POST['advice_title']) ? trim($_POST['advice_title']) : '';
    $advice_content = isset($_POST['advice_content']) ? trim($_POST['advice_content']) : '';
    $category = isset($_POST['category']) ? trim($_POST['category']) : '';
    $user_id = $_SESSION['user_id']; // 假設這是登入的使用者 ID (之後要用 $_SESSION 取得)

    // 資料庫連線
    $link = mysqli_connect('localhost', 'root', '', 'system_project');
    if (!$link) {
        die("資料庫連線失敗: " . mysqli_connect_error());
    }

    // 插入建言 (advice) 資料
    $sql_advice = "INSERT INTO advice (user_id, advice_title, advice_content, category) 
                   VALUES ($user_id, $advice_title, $advice_content,$category)";
    //防止SQL直接注入
    $stmt_advice = mysqli_prepare($link, $sql_advice);
    mysqli_stmt_bind_param($stmt_advice, "isss", $user_id, $advice_title, $advice_content, $category);

    if (mysqli_stmt_execute($stmt_advice)) {
        $advice_id = mysqli_insert_id($link); // 獲取剛插入的 advice_id

        // 如果有上傳圖片
        if (!empty($_FILES['file']['name'])) {
            $fileTmpPath = $_FILES['file']['tmp_name'];
            $fileName = basename($_FILES['file']['name']);
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // 允許的圖片格式
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($fileExt, $allowedExtensions)) {
                $imageData = file_get_contents($fileTmpPath); // 讀取圖片為二進制數據

                // 插入圖片到 images 表
                $sql_image = "INSERT INTO images (advice_id, image_name, image_data) VALUES (?, ?, ?)";
                $stmt_image = mysqli_prepare($link, $sql_image);
                mysqli_stmt_bind_param($stmt_image, "iss", $advice_id, $fileName, $imageData);
                
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
        header('refresh:2;url=homepage.php');
    }

    // 關閉連線
    mysqli_stmt_close($stmt_advice);
    mysqli_close($link);
}
?>



<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $advice_title=$_POST['advice_title'];
    $advice_content=$_POST['advice_content'];
    $category=$_POST['category'];
    $img=$_POST['file'];
    $user_id=$_SESSION['user_id'];

    // 連接到資料庫
    //Step 1
    $link = mysqli_connect('localhost', 'root', '', 'system_project');
    if (!$link) {
        die("資料庫連線失敗: " . mysqli_connect_error());
    }

    //Step 3:// 使用 prepared statement 防止 SQL 注入
    $sql = "insert into advice(user_id,advice_title,advice_content,category)values('$user_id','$advice_title','$advice_content','$category')";

    if (mysqli_query($link, $sql)) {
        echo "<pre>
    
    
    </pre><div align=center>"
            // <video autoplay muted playsinline width=640 height=360>
            //     <source src= type=video/mp4>
            // </video>
            , " <div class=message>提交成功!請稍後~loading, please wait...</div>
        </div>";
        header('refresh:2;url=homepage.php');
    } else {
        echo "<pre>
    
    
    </pre><div align=center>"
            // <video autoplay muted playsinline width=640 height=360>
            //     <source src= type=video/mp4>
            // </video>
            , "<div class=message>提交失敗!請稍後~loading, please wait...</div>
        </div>";
        header('refresh:2;url=homepage.php');
    }


}

echo "test"
    ?>