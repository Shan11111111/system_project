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
//接收註冊表單資料
$user_id = $_POST['user_id'];
$password = $_POST['password'];
$method = $_POST['method'];
$level = $_POST['level'];
$department = $_POST['department'];
$email = $_POST['email'];
$name = $_POST['name'];

//Step 1
$link = mysqli_connect('localhost', 'root', '', 'system_project');
//Step 3
$sql = "insert into users (user_id, password, level, department, email, name) values ('$user_id', '$password', '$level', '$department', '$email', '$name')";
$result = mysqli_query($link, $sql);

// Step 3: 檢查 INSERT 是否成功
if ($result) {
    // 註冊成功，將用戶資訊存入 Session
    $_SESSION['user_id'] = $user_id;
    $_SESSION['name'] = $name;
    $_SESSION['level'] = $level;
    $_SESSION['department'] = $department;
    $_SESSION['email'] = $email;

    // 顯示加載畫面並跳轉
    ?>
    <div id="loading-screen">
        <div id="progress-text">1%</div>
        <div id="progress-bar">
            <div id="progress-bar-inner"></div>
        </div>
        <div id="loading-message">即將進入孵仁...</div>
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
                setTimeout(simulateLoading, 30); // 模擬加載速度
            } else {
                                window.location.href = 'homepage.php';
            }
        }

        // 開始模擬加載
        simulateLoading();
    </script>
    </body>
    <?php
}else{
    echo "<script>alert('註冊失敗，請檢查學號或密碼');</script>";
    echo "<script>window.location.href='register.php';</script>";
}
?>
