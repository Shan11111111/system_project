<?php

$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "system_project";  


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 確認是否傳遞了 id 參數
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $advice_id = intval($_GET['id']);  // 確保 id 是數字

    // SQL 查詢語句 - 根據 id 查詢建言資料
    $sql = "SELECT advice_id, user_id, advice_title, advice_content, agree, category, advice_state, announce_date 
            FROM advice WHERE advice_id = $advice_id";
    $result = $conn->query($sql);

    // 檢查是否有資料
    if ($result->num_rows > 0) {
        // 儲存結果
        $advice_data = [];
        while($row = $result->fetch_assoc()) {
            $advice_data[] = [
                'advice_id' => $row["advice_id"],
                'user_id' => $row["user_id"],
                'advice_title' => $row["advice_title"],
                'advice_content' => $row["advice_content"],
                'agree' => $row["agree"],
                'category' => $row["category"],
                'advice_state' => $row["advice_state"],
                'announce_date' => $row["announce_date"]
            ];
        }
        // 回傳 JSON 格式的資料
        echo json_encode($advice_data);
    } else {
        echo "No advice found with this ID";
    }
} else {
    echo "Invalid or missing ID";
}



// 關閉資料庫連線
$conn->close();
?>
