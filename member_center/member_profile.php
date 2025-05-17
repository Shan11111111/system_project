<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>孵仁</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../css/member_profile.css">
    <!-- Swiper -->
    <link rel="stylesheet" href="https://unpkg.com/swiper@11/swiper-bundle.min.css">

    <!-- cdn link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- 引入 SweetAlert2 :美觀彈出未登入警告圖示-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php
    session_start();
    require_once '../db_connection.php';

    // 確保已登入
    $user_id = $_SESSION['user_id'] ?? 0;
    if (!$user_id) {
        die("未登入或使用者 ID 遺失");
    }

    // 撈會員基本資料
    $stmt = $pdo->prepare("SELECT user_id, name, level, email FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 身分轉換：英文 → 中文
    $levelMap = [
        'office' => '管理者',
        'student' => '學生',
        'teacher' => '老師'
    ];
    $levelZh = $levelMap[$user['level']] ?? $user['level'];

    // 提交建言數
    $stmt1 = $pdo->prepare("SELECT COUNT(*) FROM advice WHERE user_id = ?");
    $stmt1->execute([$user_id]);
    $adviceCount = (int) $stmt1->fetchColumn();

    // 收藏數
    $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM collection WHERE user_id = ?");
    $stmt2->execute([$user_id]);
    $collectionCount = (int) $stmt2->fetchColumn();

    // 附議紀錄數
    $stmt3 = $pdo->prepare("SELECT COUNT(*) FROM agree_record WHERE user_id = ?");
    $stmt3->execute([$user_id]);
    $agreeCount = (int) $stmt3->fetchColumn();

    //募資紀錄
    $stmt4 = $pdo->prepare("SELECT COUNT(*) FROM donation_record WHERE user_id = ?");
    $stmt4->execute([$user_id]);
    $donationCount = (int) $stmt4->fetchColumn();
    ?>



    <div class="profile_container">
        <div class="profile-header">
            <div class="user"><i class="fa-solid fa-user fa-3x" style="color:white"></i></i></div>
            <div class="user-info">
                <h2><strong><?= htmlspecialchars($levelZh) . '   ' . htmlspecialchars($user['name']) ?></strong></h2>
                <p>帳號：<?= htmlspecialchars($user['user_id']) ?></p>
                <p>Email：<?= htmlspecialchars($user['email']) ?></p>
            </div>
        </div>


        <div class="profile-stats">
            <a href="member_favorites.php" class="stat-card" style="text-decoration: none;">
                <div class="stat-title">收藏建言</div>
                <div class="stat-value"><?= $collectionCount ?> 件</div>
            </a>
            <a href="member_advice.php" class="stat-card" style="text-decoration: none;">
                <div class="stat-title">提交的建言</div>
                <div class="stat-value"><?= $adviceCount ?> 件</div>
            </a>
            <a href="member_agreements.php" class="stat-card" style="text-decoration: none;">
                <div class="stat-title">附議紀錄</div>
                <div class="stat-value"><?= $agreeCount ?> 件</div>
            </a>
            <a href="member_fundings.php" class="stat-card" style="text-decoration: none;">
                <div class="stat-title">募資紀錄</div>
                <div class="stat-value"><?= $donationCount ?> 件</div>
            </a>

        </div>
    </div>

</body>

</html>