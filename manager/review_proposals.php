<!-- filepath: c:\xampp\htdocs\analysis_project\manager\review_proposals.php -->
<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <title>審核提案</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .content {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #007BFF;
            color: #fff;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #ddd;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="content">
        <h1>審核提案</h1>
        <table>
            <thead>
                <tr>
                    <th>提案 ID</th>
                    <th>建言 ID</th>
                    <th>提案內容</th>
                    <th>資金金額</th>
                    <th>檔案</th>
                    <th>狀態</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // 資料庫連線
                $conn = new mysqli("localhost", "root", "", "system_project");
                if ($conn->connect_error) {
                    die("資料庫連線失敗: " . $conn->connect_error);
                }

                // 查詢所有待審核的提案
                $sql = "SELECT suggestion_assignments_id, advice_id, proposal_text, funding_amount, proposal_file_path, status 
                        FROM suggestion_assignments 
                        WHERE status = '審核中'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['suggestion_assignments_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['advice_id']) . "</td>";
                        echo "<td>" . nl2br(htmlspecialchars($row['proposal_text'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['funding_amount']) . "</td>";
                        echo "<td><a href='" . htmlspecialchars($row['proposal_file_path']) . "' download>下載檔案</a></td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "<td>
                                <a class='btn' href='approve_proposal.php?suggestion_assignments_id=" . $row['suggestion_assignments_id'] . "'>批准</a>
                                <a class='btn' href='reject_proposal.php?suggestion_assignments_id=" . $row['suggestion_assignments_id'] . "'>退回</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>目前沒有待審核的提案</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>