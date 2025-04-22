<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <title>提交提案</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .content {
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        form label {
            font-size: 1.1em;
            color: #333;
            display: block;
            margin-bottom: 10px;
        }

        form textarea, form input[type="text"], form input[type="file"] {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        form button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="content">
        <h1>提交提案</h1>
        <form action="process_proposal.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="suggestion_assignments_id" value="<?php echo htmlspecialchars($_GET['suggestion_assignments_id']); ?>">
            <label for="proposal_text">提案內容:</label>
            <textarea name="proposal_text" id="proposal_text" rows="5" required><?php
                // 如果是重新提交，預填舊的提案內容
                $conn = new mysqli("localhost", "root", "", "system_project");
                $suggestion_assignments_id = intval($_GET['suggestion_assignments_id']);
                $sql = "SELECT proposal_text FROM suggestion_assignments WHERE suggestion_assignments_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $suggestion_assignments_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo htmlspecialchars($row['proposal_text']);
                }
                $stmt->close();
                $conn->close();
            ?></textarea>
            <label for="funding_amount">資金金額:</label>
            <input type="text" name="funding_amount" id="funding_amount" required>
            <label for="proposal_file">上傳提案文件:</label>
            <input type="file" name="proposal_file" id="proposal_file" required>
            <button type="submit">提交提案</button>
        </form>
    </div>
</body>

</html>