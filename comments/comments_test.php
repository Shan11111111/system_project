<?php session_start(); ?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>留言資料</title>
</head>
<body>
    <h1>留言資料</h1>
    <div id="comment-container"></div>

    <script>
        // 使用 fetch API 獲取資料
        // 假設你要獲取的建議ID為24
        fetch('comments/comments_show.php?advice_id=24')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(result => {
                const container = document.getElementById('comment-container');
                const data = result.data; // 提取 data 屬性
                if (!Array.isArray(data) || data.length === 0) {
                    container.innerHTML = '<p>目前沒有留言資料。</p>';
                } else {
                    data.forEach(item => {
                        const adviceDiv = document.createElement('div');
                        adviceDiv.innerHTML = `
                            <h2>${item.comment_content}</h2>
                            <p>${item.user_id}</p>
                            <p>建議ID: ${item.advice_id}</p>
                            
                            <p>留言時間: ${item.comment_time}</p>
                        `;
                        container.appendChild(adviceDiv);
                    });
                }
            })
            .catch(error => {
                console.error('發生錯誤:', error);
                const container = document.getElementById('comment-container');
                container.innerHTML = `<p>無法載入資料。錯誤訊息: ${error.message}</p>`;
                console.log('請求的 URL:', 'comments/comments_show.php?advice_id=24');
            });
    </script>
</body>
</html>