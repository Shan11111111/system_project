<?php
// 資料庫連線設定
$conn = new mysqli("localhost", "root", "", "system_project");
if ($conn->connect_error) {
  die("連線失敗：" . $conn->connect_error);
}
$suggestions = $conn->query("SELECT * FROM suggestions ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
  <meta charset="UTF-8">
  <title>建言後台管理</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .sidebar {
      height: 100vh;
      background-color: #343a40;
      color: white;
      padding-top: 1rem;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      padding: 10px 20px;
      display: block;
    }
    .sidebar a:hover {
      background-color: #495057;
    }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    <!-- 側邊欄 -->
    <nav class="col-md-2 d-none d-md-block sidebar">
      <h4 class="text-center">管理選單</h4>
      <a href="#">建言管理</a>
      <a href="#">使用者管理</a>
      <a href="#">系統設定</a>
      <a href="#">登出</a>
    </nav>

    <!-- 主內容區 -->
    <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4 py-4">
      <h2>建言總覽</h2>
      <div class="card mt-4 shadow-sm">
        <div class="card-body table-responsive">
          <table class="table table-hover table-bordered align-middle">
            <thead class="table-primary">
              <tr>
                <th>ID</th>
                <th>姓名</th>
                <th>Email</th>
                <th>建言內容</th>
                <th>時間</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($suggestions->num_rows > 0): ?>
                <?php while ($s = $suggestions->fetch_assoc()): ?>
                  <tr>
                    <td><?= $s['id'] ?></td>
                    <td><?= htmlspecialchars($s['name']) ?></td>
                    <td><?= htmlspecialchars($s['email']) ?></td>
                    <td><?= nl2br(htmlspecialchars($s['message'])) ?></td>
                    <td><?= $s['created_at'] ?></td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="5" class="text-center">目前沒有建言資料。</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
