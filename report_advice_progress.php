<?php
require_once("db_connection.php");

if (!isset($_GET['advice_id'])) {
    echo "未指定建言 ID";
    exit;
}

$advice_id = (int) $_GET['advice_id'];

// 取得建言資訊
$stmt = $pdo->prepare("SELECT advice_title, advice_content FROM advice WHERE advice_id = ?");
$stmt->execute([$advice_id]);
$advice = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$advice) {
    echo "找不到該建言資料";
    exit;
}

// 回報處理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content']);
    if ($content !== '') {
        try {
            $pdo->beginTransaction();

            $stmt1 = $pdo->prepare("INSERT INTO advice_state (content, advice_id) VALUES (?, ?)");
            $stmt1->execute([$content, $advice_id]);

            $stmt2 = $pdo->prepare("UPDATE advice SET advice_state = '已回覆' WHERE advice_id = ?");
            $stmt2->execute([$advice_id]);

            $pdo->commit();
            header("Location: select_advice_to_report.php?status=success");
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            $errorMsg = "回報失敗：" . $e->getMessage();
        }
    } else {
        $errorMsg = "內容不可為空";
    }
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>建言狀態回報</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/report_advice.css">
</head>
<body>
    <div class="report-container">

        <!-- 返回 -->
        <div class="back-top">
            <a href="select_advice_to_report.php">← 返回建言列表</a>
        </div>

        <!-- 建言資訊 -->
        <section class="advice-card">
            <h2 class="advice-title"><?= htmlspecialchars($advice['advice_title']) ?></h2>
            <div class="advice-content">
                <?= nl2br(htmlspecialchars($advice['advice_content'])) ?>
            </div>
        </section>

        <!-- 回報表單 -->
        <section class="report-form-section">
            <h3>狀態回報</h3>
            <p class="desc">送出後狀態將自動設為「已回覆」。</p>

            <?php if (!empty($errorMsg)): ?>
                <div class="form-error"><?= htmlspecialchars($errorMsg) ?></div>
            <?php endif; ?>

            <form method="post" class="report-form">
                <label for="content">狀態內容/</label>
                <textarea name="content" id="content" rows="6" required placeholder="例如：已與學務處接洽，目前進入內部討論階段。"></textarea>
                <button type="submit" class="submit-btn"> 送出回報</button>
            </form>
        </section>

    </div>
</body>
</html>
