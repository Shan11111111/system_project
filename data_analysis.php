<?php
// 資料庫連線設定 (使用常數定義)
define('DB_HOST', 'localhost');
define('DB_NAME', 'system_project');
define('DB_USER', 'root');
define('DB_PASS', '');

// 建立資料庫連線 (使用單例模式概念)
function getPDOConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            // 記錄錯誤到日誌 (實際應用中)
            error_log("Database connection failed: " . $e->getMessage());
            
            // 顯示用戶友好訊息
            die("系統暫時無法提供服務，請稍後再試。技術訊息: " . $e->getMessage());
        }
    }
    
    return $pdo;
}

// 主程式
try {
    $pdo = getPDOConnection();
    
    // 這裡可以放置你的主要業務邏輯
    // 例如: 查詢其他表格的資料
    
    echo "<!DOCTYPE html>
    <html lang='zh-Hant'>
    <head>
        <meta charset='UTF-8'>
        <title>系統資料分析</title>
        <style>
            body { font-family: Arial; padding: 20px; }
            h1 { color: #2c3e50; }
            .success { color: green; }
        </style>
    </head>
    <body>
        <h1>資料庫連接成功</h1>
        <p class='success'>已成功建立資料庫連接，可以開始進行資料操作。</p>
    </body>
    </html>";

} catch (PDOException $e) {
    // 處理執行期間的錯誤
    echo "<p style='color: red;'>操作失敗: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>