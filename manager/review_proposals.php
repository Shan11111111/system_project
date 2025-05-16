<?php
session_start();
?>

<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <title>審核提案</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css\review.css">

</head>

<body>
    <!-- 左側導覽列 -->
    <div class="sidebar">
        <h2>管理系統</h2>
        <a href="../homepage.php">孵仁首頁</a>
        <a href="../manager/advice_manager.php">建言管理</a>
        <a href="review_proposals.php">募資專案審核</a>
        <a href="review_extension_requests.php">延後募資申請審核</a>
        <a href="people_manager.php">人員處理</a>
        <a href="../funding/announcement.php">發布公告</a>
    </div>

    <!-- 頁面內容 -->

    <div class="content">
        <h1>募資專案審核</h1>

        <div class="tab-wrapper">
            <button class="tab-btn active" onclick="showTab('review')">審核提案</button>
            <button class="tab-btn" onclick="showTab('approved')">已批准提案</button>
        </div>

        <!-- 審核中區塊 -->
        <div id="review-section">
            <div class="search-bar">
                <form action="review_proposals.php" method="GET">
                    <input type="text" name="search" placeholder="搜尋提案..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit">搜尋</button>
                </form>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>提案編號</th>
                        <th>建言連結</th>
                        <th>提案內容</th>
                        <th>提案金額</th>
                        <th>企劃書</th>
                        <th>提案處所</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conn = new mysqli("localhost", "root", "", "system_project");
                    if ($conn->connect_error) die("資料庫連線失敗: " . $conn->connect_error);

                    $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
                    $limit = 5;
                    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                    $offset = ($page - 1) * $limit;

                    $sql = "SELECT department, suggestion_assignments_id, advice_id, proposal_text, funding_amount, proposal_file_path FROM suggestion_assignments JOIN users ON suggestion_assignments.office_id = users.user_id WHERE status = '審核中'";
                    if (!empty($search)) {
                        $sql .= " AND (proposal_text LIKE '%$search%' OR advice_id LIKE '%$search%')";
                    }
                    $sql .= " LIMIT $limit OFFSET $offset";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$row['suggestion_assignments_id']}</td>";
                            echo "<td><a href='../advice_detail.php?advice_id=" . urlencode($row['advice_id']) . "'>" . htmlspecialchars($row['advice_id']) . "</a></td>";
                            $fullText = htmlspecialchars($row['proposal_text']);
                            echo "<td><span td style='text-align: left;'class='table-ellipsis' onclick='showFullText(`$fullText`)'>$fullText</span></td>";
                            echo "<td>{$row['funding_amount']}</td>";
                            if (!empty($row['proposal_file_path']) && file_exists("../" . $row['proposal_file_path'])) {
                                $safePath = htmlspecialchars("../" . $row['proposal_file_path']);
                                $fileName = basename($row['proposal_file_path']);
                                echo "<td><a href='$safePath' download='$fileName'>下載檔案</a></td>";
                            } else {
                                echo "<td>無檔案</td>";
                            }
                            echo "<td>{$row['department']}</td>";
                            echo "<td>    
                            <button class='btn'onclick='showApproveModal({$row['suggestion_assignments_id']})'>批准</button>
                            <button class='btn reject' onclick='showRejectModal({$row['suggestion_assignments_id']})'>退回</button>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>目前沒有待審核的提案</td></tr>";
                    }

                    $total_sql = "SELECT COUNT(*) AS total FROM suggestion_assignments WHERE status = '審核中'";
                    if (!empty($search)) {
                        $total_sql .= " AND (proposal_text LIKE '%$search%' OR advice_id LIKE '%$search%')";
                    }
                    $total_result = $conn->query($total_sql);
                    $total_row = $total_result->fetch_assoc();
                    $total_pages = ceil($total_row['total'] / $limit);
                    ?>
                    <script>
                        function showFullText(text) {
                            Swal.fire({
                                title: '提案內容',
                                html: `<div style="text-align:center; max-height:300px; overflow:auto;">${text}</div>`,
                                confirmButtonText: '關閉'
                            });
                        }

                        function showApproveModal(id) {
                            Swal.fire({
                                title: '批准提案',
                                input: 'textarea',
                                inputLabel: '請輸入回饋意見：',
                                inputPlaceholder: '請輸入內容...',
                                inputAttributes: {
                                    'aria-label': '請輸入內容'
                                },
                                showCancelButton: true,
                                confirmButtonText: '送出批准',
                                cancelButtonText: '取消',
                                preConfirm: (feedback) => {
                                    if (!feedback.trim()) {
                                        Swal.showValidationMessage('請輸入回饋意見');
                                        return false;
                                    }

                                    // 建立並送出表單
                                    const form = document.createElement('form');
                                    form.method = 'POST';
                                    form.action = 'approve_proposal.php';

                                    const idInput = document.createElement('input');
                                    idInput.type = 'hidden';
                                    idInput.name = 'suggestion_assignments_id';
                                    idInput.value = id;

                                    const feedbackInput = document.createElement('input');
                                    feedbackInput.type = 'hidden';
                                    feedbackInput.name = 'admin_feedback';
                                    feedbackInput.value = feedback;

                                    form.appendChild(idInput);
                                    form.appendChild(feedbackInput);
                                    document.body.appendChild(form);
                                    form.submit();
                                }
                            });
                        }

                        function showRejectModal(id) {
                            Swal.fire({
                                title: '退回提案',
                                input: 'textarea',
                                inputLabel: '請輸入退回原因：',
                                inputPlaceholder: '請輸入內容...',
                                inputAttributes: {
                                    'aria-label': '請輸入內容'
                                },
                                showCancelButton: true,
                                confirmButtonText: '送出退回',
                                cancelButtonText: '取消',
                                preConfirm: (reason) => {
                                    if (!reason.trim()) {
                                        Swal.showValidationMessage('請輸入退回原因');
                                        return false;
                                    }

                                    const form = document.createElement('form');
                                    form.method = 'POST';
                                    form.action = 'reject_proposal.php';

                                    const idInput = document.createElement('input');
                                    idInput.type = 'hidden';
                                    idInput.name = 'suggestion_assignments_id';
                                    idInput.value = id;

                                    const feedbackInput = document.createElement('input');
                                    feedbackInput.type = 'hidden';
                                    feedbackInput.name = 'admin_feedback';
                                    feedbackInput.value = reason;

                                    form.appendChild(idInput);
                                    form.appendChild(feedbackInput);
                                    document.body.appendChild(form);
                                    form.submit();
                                }
                            });
                        }
                    </script>

                </tbody>
            </table>

            <div class="pagination">
                <?php
                $max_buttons = 5;
                $half = floor($max_buttons / 2);

                $start = max(1, $page - $half);
                $end = min($total_pages, $start + $max_buttons - 1);
                // 若尾端不足 5 頁，從前面補
                if ($end - $start + 1 < $max_buttons) {
                    $start = max(1, $end - $max_buttons + 1);
                }
                // 上一頁
                if ($page > 1) {
                    echo "<a href='review_proposals.php?page=" . ($page - 1) . "&search=" . urlencode($search) . "'>上一頁</a>";
                }
                // 分頁按鈕
                for ($i = $start; $i <= $end; $i++) {
                    $active = ($i == $page) ? 'active' : '';
                    echo "<a href='review_proposals.php?page=$i&search=" . htmlspecialchars($search) . "' class='$active'>$i</a>";
                }
                // 下一頁
                if ($page < $total_pages) {
                    echo "<a href='review_proposals.php?page=" . ($page + 1) . "&search=" . urlencode($search) . "'>下一頁</a>";
                }
                ?>
            </div>
        </div>

        <!-- 已批准區塊 -->
        <div id="approved-section" style="display: none;">
            <table>
                <thead>
                    <tr>
                        <th>提案編號</th>
                        <th>建言連結</th>
                        <th>提案內容</th>
                        <th>提案金額</th>
                        <th>企劃書</th>
                        <th>負責處所</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conn = new mysqli("localhost", "root", "", "system_project");
                    if ($conn->connect_error) die("資料庫連線失敗: " . $conn->connect_error);

                    $approved_search = isset($_GET['approved_search']) ? $conn->real_escape_string($_GET['approved_search']) : '';
                    $approved_limit = 5;
                    $approved_page = isset($_GET['approved_page']) ? intval($_GET['approved_page']) : 1;
                    $approved_offset = ($approved_page - 1) * $approved_limit;

                    $approved_sql = "SELECT department, suggestion_assignments_id, advice_id, proposal_text, funding_amount, proposal_file_path FROM suggestion_assignments JOIN users ON suggestion_assignments.office_id = users.user_id WHERE status = '已通過'";
                    if (!empty($approved_search)) {
                        $approved_sql .= " AND (proposal_text LIKE '%$approved_search%' OR advice_id LIKE '%$approved_search%')";
                    }
                    $approved_sql .= " LIMIT $approved_limit OFFSET $approved_offset";
                    $approved_result = $conn->query($approved_sql);

                    if ($approved_result->num_rows > 0) {
                        while ($row = $approved_result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$row['suggestion_assignments_id']}</td>";
                            echo "<td><a href='../advice_detail.php?advice_id=" . urlencode($row['advice_id']) . "'>" . htmlspecialchars($row['advice_id']) . "</a></td>";
                            $fullText = htmlspecialchars($row['proposal_text']);
                            echo "<td style='text-align: left;'><span  class='table-ellipsis' onclick='showFullText(`$fullText`)'>$fullText</span></td>";
                            echo "<td>{$row['funding_amount']}</td>";
                            if (!empty($row['proposal_file_path']) && file_exists("../" . $row['proposal_file_path'])) {
                                $safePath = htmlspecialchars("../" . $row['proposal_file_path']);
                                $fileName = basename($row['proposal_file_path']);
                                echo "<td><a href='$safePath' download='$fileName'>下載檔案</a></td>";
                            } else {
                                echo "<td>無檔案</td>";
                            }
                            echo "<td>{$row['department']}</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>目前沒有已批准的提案</td></tr>";
                    }

                    $approved_total_sql = "SELECT COUNT(*) AS total FROM suggestion_assignments WHERE status = '已通過'";
                    if (!empty($approved_search)) {
                        $approved_total_sql .= " AND (proposal_text LIKE '%$approved_search%' OR advice_id LIKE '%$approved_search%')";
                    }
                    $approved_total_result = $conn->query($approved_total_sql);
                    $approved_total_row = $approved_total_result->fetch_assoc();
                    $approved_total_pages = ceil($approved_total_row['total'] / $approved_limit);
                    ?>
                </tbody>
            </table>

            <div class="pagination">
                <?php
                $max_buttons = 5;
                $half = floor($max_buttons / 2);

                // 動態計算開始與結束頁碼
                $approved_start = max(1, $approved_page - $half);
                $approved_end = min($approved_total_pages, $approved_start + $max_buttons - 1);

                // 修正當結尾太小時往前補齊
                if ($approved_end - $approved_start + 1 < $max_buttons) {
                    $approved_start = max(1, $approved_end - $max_buttons + 1);
                }
                // 上一頁
                if ($approved_page > 1) {
                    echo "<a href='review_proposals.php?approved_page=" . ($approved_page - 1) . "&approved_search=" . urlencode($approved_search) . "'>上一頁</a>";
                }
                // 中間頁碼
                for ($i = $approved_start; $i <= $approved_end; $i++) {
                    $active = ($i == $approved_page) ? 'active' : '';
                    echo "<a href='review_proposals.php?approved_page=$i&approved_search=" . htmlspecialchars($approved_search) . "' class='$active'>$i</a>";
                }
                // 下一頁
                if ($approved_page < $approved_total_pages) {
                    echo "<a href='review_proposals.php?approved_page=" . ($approved_page + 1) . "&approved_search=" . urlencode($approved_search) . "'>下一頁</a>";
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            const review = document.getElementById('review-section');
            const approved = document.getElementById('approved-section');
            const buttons = document.querySelectorAll('.tab-btn');

            review.style.display = (tabName === 'review') ? 'block' : 'none';
            approved.style.display = (tabName === 'approved') ? 'block' : 'none';

            buttons.forEach(btn => btn.classList.remove('active'));
            document.querySelector(`.tab-btn[onclick=\"showTab('${tabName}')\"]`).classList.add('active');
        }

        // 改成這樣，根據網址參數決定初始顯示的區塊
        const params = new URLSearchParams(window.location.search);
        if (params.has('approved_page') || params.has('approved_search')) {
            showTab('approved');
        } else {
            showTab('review');
        }
    </script>

</body>

</html>