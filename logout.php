<?php session_start();?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>加載中Loading...</title>
    <style>
        body {
            margin: 0;
            overflow: hidden;
        }
        #loading-screen {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #ffdb7e; /* 黃色背景 */
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            color: white;
            font-family: Arial, sans-serif;
            z-index: 10;
        }
        #progress-bar {
            width: 80%;
            height: 20px;
            background-color: #ffa73c; /* 白色進度條背景 */
            border-radius: 10px;
            overflow: hidden;
            margin-top: 10px;
        }
        #progress-bar-inner {
            height: 100%;
            width: 0%;
            background-color: #ffffff; /* 黑色進度條 */
            transition: width 0.1s;
        }
        #loading-message {
            margin-top: 15px;
            font-size: 16px;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
        }
    </style>
</head>
<body>


<?php
$_SESSION=[];

session_destroy();

?>

<div id="loading-screen">
        <div id="progress-text">1%</div>
        <div id="progress-bar">
            <div id="progress-bar-inner"></div>
        </div>
        <div id="loading-message">登出成功...</div> <!-- 新增的文字 -->
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>
        // 初始化 Three.js 場景
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer();
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.body.appendChild(renderer.domElement);

        // 模擬加載進度
        let progress = 1;
        const progressText = document.getElementById('progress-text');
        const progressBarInner = document.getElementById('progress-bar-inner');
        const loadingScreen = document.getElementById('loading-screen');

        function simulateLoading() {
            if (progress <= 100) {
                progressText.textContent = `${progress}%`;
                progressBarInner.style.width = `${progress}%`;
                progress++;
                setTimeout(simulateLoading, 3); // 模擬加載速度
            } else {
                // 加載完成後跳轉到 homepage.php
                window.location.href = 'homepage.php';
            }
        }

        // 開始模擬加載
        simulateLoading();
    </script>
    </body>
exit;

?>