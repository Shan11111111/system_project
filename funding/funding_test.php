<?php session_start(); ?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>募資資料</title>
</head>
<body>
    <h1>募資資料</h1>
    <div id="advice-container"></div>

    <script>
        // 使用 fetch API 獲取資料
        fetch('funding_show.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(result => {
                const container = document.getElementById('advice-container');
                const data = result.data; // 提取 data 屬性
                if (!Array.isArray(data) || data.length === 0) {
                    container.innerHTML = '<p>目前沒有募資資料。</p>';
                } else {
                    data.forEach(item => {
                        const adviceDiv = document.createElement('div');
                        adviceDiv.innerHTML = `
                            <h2>${item.advice_title}</h2>
                            <p>${item.advice_content}</p>
                            <p>分類: ${item.category}</p>
                            <p>公告日期: ${item.announce_date}</p>
                            <p>目標金額: ${item.target}</p>
                        `;
                        container.appendChild(adviceDiv);
                    });
                }
            })
            .catch(error => {
                console.error('發生錯誤:', error);
                const container = document.getElementById('advice-container');
                container.innerHTML = `<p>無法載入資料。錯誤訊息: ${error.message}</p>`;
            });
    </script>
</body>
</html>