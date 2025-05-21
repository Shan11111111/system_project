<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'system_project');
define('DB_USER', 'root');
define('DB_PASS', '');

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
            error_log("Database connection failed: " . $e->getMessage());
            die("系統暫時無法提供服務，請稍後再試。技術訊息: " . $e->getMessage());
        }
    }
    return $pdo;
}

try {
    $pdo = getPDOConnection();

    // 建言總數
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM suggestions");
    $totalSuggestions = $stmt->fetch()['total'];

    // 最新建言時間
    $stmt2 = $pdo->query("SELECT MAX(created_at) AS latest FROM suggestions");
    $latestSuggestion = $stmt2->fetch()['latest'];

} catch (PDOException $e) {
    echo "<p style='color: red;'>操作失敗: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>數據分析</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: white;
            padding-top: 1rem;
        }
        .sidebar a { color: white; text-decoration: none; padding: 10px 20px; display: block; }
        .sidebar a:hover { background-color: #495057; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- 側邊欄 -->
        <nav class="col-md-2 d-none d-md-block sidebar">
            <h4 class="text-center">管理選單</h4>
            <a href="suggestions.php">建言管理</a>
            <a href="#">使用者管理</a>
            <a href="#">系統設定</a>
            <a href="analytics.php">數據分析</a>
            <a href="#">登出</a>
        </nav>
        <!-- 主內容區 -->
        <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2>數據分析儀表板</h2>
            <div class="card mt-4 shadow-sm">
                <div class="card-body">
                    <h5>建言總數：<span class="text-primary"><?= $totalSuggestions ?></span></h5>
                    <h5>最新建言時間：<span class="text-success"><?= $latestSuggestion ? $latestSuggestion : "無" ?></span></h5>
                    <!-- 這裡可以再加入更多統計、圖表等分析資料 -->
                </div>
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>